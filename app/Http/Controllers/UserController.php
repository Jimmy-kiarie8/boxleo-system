<?php

namespace App\Http\Controllers;

use App\Events\UserOnline;
use App\Mail\UserRegisterMail;
use App\Models\Company;
use App\Shipment;
use App\Models\User;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;

class UserController extends Controller
{
    public function logged_user()
    {
        // return User::find(1);
        $user = new User();
        return  $user->logged_user();
    }
    public function index()
    {
        // return User::find(1);

        // $cacheKey = 'users_' . Auth::user()->ou_id;

        // return Cache::remember($cacheKey, now()->addHours(10), function () {
        $users = User::where('email', '!=', env('ADMIN_MAIL', 'support@logixsaas.com'))->orderBy('name')->paginate(500);
        // $users = User::orderBy('name')->with('roles')->paginate(500);
        $users->transform(function ($user) {
            // dd($user->roles, count($user->roles));
            $user->role_id = (count($user->roles) > 0) ? $user->roles[0]['id'] : null;
            // $user->status = 'active';
            return $user;
        });
        return response()->json($users);
        // });
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create', User::class);
        // $user = User::first();
        // $user_mail = new User;
        // $user_mail->welcome_mail($user);
        // return;
        // return $this->generateRandomString();
        // return $request->all();
        // return Company::first()->id;
        $this->Validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'role_id' => 'required',
        ]);

        if (!Auth::user()->hasRole('Super admin')) {
            if ($request->role_id == 1) {
                abort(422, "You can't create a Super Admin user");
            }
        }


        // return $request->all();
        // $password = Str::random(8);
        $user = new User;
        // $password = $this->generateRandomString();
        // $password_hash = Hash::make($password);
        // $user->password = $password_hash;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->ou_id = $request->ou_id;
        $user->company_id = Company::first()->id;
        $user->address = $request->address;
        // $user->password = Hash::make($password);
        // $user->activation_token = str_random(60);
        $user->save();
        $user->assignRole($request->role_id);

        // Mail::send(new UserRegisterMail($user, $password));

        $user_mail = new User;
        $user_mail->welcome_mail($user);

        return $user;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Auth::user());
        // return $request->all();
        $this->Validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'role_id' => 'required'
        ]);

        if (!Auth::user()->hasRole('Super admin')) {
            if ($request->role_id == 1) {
                abort(422, "You can't create a Super Admin user");
            }
        }
        $user = User::find($id);
        // $user->city = $request->city;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->ou_id = $request->ou_id;
        $user->can_call = $request->can_call;
        $user->can_receive_calls = $request->can_receive_calls;
        $user->can_receive_orders = $request->can_receive_orders;
        $user->agent_sip = $request->agent_sip;

        $user->company_id = Company::first()->id;
        $user->save();

        if (!empty(User::find($id)->roles)) {
            $user->syncRoles($request->role_id);
        } else {
            $user->assignRole($request->role_id);
        }

        return $user;
    }

    public function AuthUserUp(Request $request)
    {
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->branch_id = $request->branch_id;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->ou = $request->ou;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', Auth::user());
        User::find($user->id)->delete();
    }

    public function getLogedinUsers()
    {
        return $this->logged_user();
    }

    public function profile(Request $request, $id)
    {
        $upload = User::find($request->id);
        if ($request->hasFile('image')) {
            // return('test');
            // $imagename = time() . $request->image->getClientOriginalName();
            // $request->image->storeAs('public/test', $imagename);
            $img = $request->image;
            // $image_path = ;

            if (File::exists('storage/profile/' . $upload->image)) {

                // return('test');
                $image_path = 'storage/profile/' . $upload->image;

                File::delete($image_path);
                // return $image_path;
            }
            // $imagename =  Storage::disk('uploads')->put('profile', $img);
            $imagename = Storage::disk('public')->put('profile', $img);
        }

        // return('noop');
        $imgArr = explode('/', $imagename);
        $image_name = $imgArr[1];
        $upload->profile = '/storage/profile/' . $image_name;
        // $upload->profile = '/healthwise/products/'.$image_name;
        $upload->save();
        return $upload;
    }


    public function password(Request $request)
    {
        $this->Validate($request, [
            'password' => 'required|string|min:6',
            // 'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function logoutOther()
    {
        return view('auth.Logout');
    }

    public function logOtherDevices(Request $request)
    {
        Auth::logoutOtherDevices($request->password);
        return redirect('/courier');
    }

    public function userpermisions(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        $user->syncPermissions($request->selected);
        return $user;
    }


    public function undeletedUser($id)
    {
        User::withTrashed()->find($id)->restore();
    }

    public function force_user($id)
    {
        User::withTrashed()->find($id)->forceDelete();
    }

    public function deletedUsers()
    {
        $users = User::onlyTrashed()->get();
        return $users;
    }


    public function getUserPerm(Request $request, $id)
    {
        $cacheKey = 'user_' . $id;
        return Cache::remember($cacheKey, now()->addHours(10), function () use ($id) {

            $user = User::find($id);
            $permissions = $user->getAllPermissions();
            $can = [];
            foreach ($permissions as $perm) {
                $can[] = $perm->name;
            }
            return $can;
        });
    }

    public function change_password(Request $request, $id)
    {
        User::find($id)->update(['password' => Hash::make($request->password)]);
    }

    public function delete_session(Request $request)
    {
        // dd($request->all());
        $user = DB::table('users')->where('id', $request->id)->first();
        // dd($user);
        // $user = DB::table('users')->where('email', $request->input('email'))->first();
        $previous_session = $user->session_id;
        Session::getHandler()->destroy($previous_session);
        Auth::loginUsingId($request->id);
        return redirect('/');
        // $request->session()->regenerate();
        // $user->session_id = Session::getId();
        // $user->save();
    }


    public function user_active(Request $request, $id)
    {
        $user = User::find($id);
        $user->active = $request->status;
        $user->save();
        return;
    }

    public function agents()
    {
        //  $agents =  User::setEagerLoads([])->where('active', true)->withCount(['inbounds', 'outbounds', 'calls'])->withSum('calls', 'durationInSeconds')->whereDate('created_at', Carbon::today())->role('Call center')->get();

        /*if (Auth::check()) {
            $cacheKey = 'agents_' . Auth::user()->ou_id;
            return Cache::remember($cacheKey, now()->addHours(10), function () {

                $user = new User;
                $user = $user->logged_user();

                $agents = User::setEagerLoads([])
                    ->where('active', true)
                    ->where('ou_id', $user->ou_id)
                    ->withCount(['inbounds', 'outbounds', 'calls'])
                    ->withSum('calls', 'durationInSeconds')
                    // ->role('Call center')
                    ->role(['Call center', 'Call center admin']) // Include both "Call center" and "Call center admin" roles
                    ->withCount([
                        'calls as today_answered' => function ($query) {
                            $query->where('call_status', 'Answered')->whereDate('created_at', Carbon::today());
                        },
                        'calls as today_unanswered' => function ($query) {
                            $query->where('call_status', 'No answer')->whereDate('created_at', Carbon::today());
                        }
                    ])

                    ->get();

                return $agents->transform(function ($agent) {
                    $agent->online = ($agent->on_break || $agent->status == 'offline' || $agent->call_active == true) ? false : true;

                    $agent->calls_sum_duration_in_seconds = number_format((int)$agent->calls_sum_duration_in_seconds / 60, 2);
                    return $agent;
                });
            });
        } else {*/

        // $user = new User;
        // $user = $user->logged_user();

        // $agents = User::setEagerLoads([])
            // ->with([
            //         'inbounds' => function($q) {
            //             $q->whereDate('created_at', Carbon::today());
            //         },
            //         'outbounds' => function($q) {
            //             $q->whereDate('created_at', Carbon::today());
            //         },
            //         'calls' => function($q) {
            //             $q->whereDate('created_at', Carbon::today());
            //         },
            //     ])
            // ->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active']) // Select only necessary columns
            // ->where('active', true)
            // ->where('ou_id', $user->ou_id)
            // ->withCount(['inbounds' => function($q) {
            //     $q->whereDate('created_at', Carbon::today());
            // }, 'outbounds' => function($q) {
            //     $q->whereDate('created_at', Carbon::today());
            // }, 'calls' => function($q) {
            //     $q->whereDate('created_at', Carbon::today());
            // }])
            // ->withSum('calls', 'durationInSeconds')
            // ->role(['Call center', 'Call center admin', 'Follow UP'])
            // ->withCount([
            //     'calls as today_answered' => function ($query) {
            //         $query->where('call_status', 'Answered')->whereDate('created_at', Carbon::today());
            //     },
            //     'calls as today_unanswered' => function ($query) {
            //         $query->where('call_status', 'No answer')->whereDate('created_at', Carbon::today());
            //     },
            // ])
            // ->get();

        // return $agents;



        $cacheKey = 'agents:' . Auth::user()->ou_id;

        // Log if the cache key exists
        // $cacheExists = Cache::has($cacheKey);
        // Log::info("Agent Cache key '{$cacheKey}' exists: " . ($cacheExists ? 'Yes' : 'No'));



        return Cache::remember($cacheKey, now()->addHours(2), function () use ($cacheKey) {
            // Log::info("Agent Cache miss for key '{$cacheKey}'. Fetching from database.");

            $user = new User;
            $user = $user->logged_user();

            $agents = User::setEagerLoads([])
                ->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active']) // Select only necessary columns
                ->where('active', true)
                ->where('ou_id', $user->ou_id)
                ->role(['Call center', 'Call center admin', 'Follow UP'])
                ->get();

            return $agents;
        });






        return $agents->transform(function ($agent) {
            $agent->online = (!$agent->on_break && $agent->status != 'offline' && !$agent->call_active);

            $agent->calls_sum_duration_in_seconds = number_format((int)$agent->calls_sum_duration_in_seconds / 60, 2);

            return $agent;
        });

        // }
    }

    public function zone_agents()
    {
        $cacheKey = 'zone_agents';
        return Cache::remember($cacheKey, now()->addHours(10), function () {
            return  User::role('Agent')->get();
        });
    }

    public function assign_zone_agent(Request $request)
    {
        // return $request->all();    
        $user_id = $request->user_id;
        $zones = $request->ids;

        Zone::whereIn('id', $zones)->update(['manager' => $user_id]);
    }


    public function status_pusher(Request $request)
    {

        $user = User::find($request->events[0]['user_id']);

        if ($user) {
            if ($user->hasRole('Call center')) {
                $lock = Cache::lock('userUpdate', 5);

                try {
                    $lock->block(5);

                    $status  = ($request->events[0]['name'] == 'member_added') ? 'online' : 'offline';
                    $user->status = $status;
                    $user->save();
                    // Lock acquired after waiting a maximum of 5 seconds...
                } catch (LockTimeoutException $e) {
                    // Log::debug($e);
                    // Unable to acquire lock...
                } finally {
                    optional($lock)->release();
                }
            }
        }
    }

    public function __invoke($id, $status)
    {
        $user = User::find($id);
        $user->status = $status;
        $user->save();


        // $user = User::select('id', 'email', 'name')->find(Auth::id());
        // broadcast(new UserOnline(Auth::id()))->toOthers();
        broadcast(new UserOnline($user->id));
    }
}
