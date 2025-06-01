<?php

namespace App\Http\Controllers\Callcentre;

use App\Events\UserStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::all();
        return Call::when(Auth::user()->hasRole('Call center'), function ($q) {
            $q->where('callerNumber', Auth::user()->agent_sip)->orWhere('dialDestinationNumber', Auth::user()->agent_sip);
        })->orderBy('id', 'DESC')->paginate(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return Call::where('sale_id', $id)->paginate(100);
        $order = Sale::find($id);

        if (!$order) {
            return [];
        }
    
        $client = $order->client;

    
        // Paginate destination calls
        $calls = Call::where(function ($query) use ($client) {
            $query->where('callerNumber', 'LIKE', '%' . $client->phone . '%')
                  ->orWhere('dialDestinationNumber', 'LIKE', '%' . $client->phone . '%');
        })->paginate(10);
    
        return $calls;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function agent_active($id, Request $request)
    {

        $lock = Cache::lock('userUpdate', 5);

        try {
            $lock->block(5);
            User::find($id)->update(['call_active', true, 'call_active' => $request->call_status]);
            // Lock acquired after waiting a maximum of 5 seconds...
        } catch (LockTimeoutException $e) {
            // Log::debug($e);
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    public function agent_calls(Request $request)
    {
        // return $request->all();
        $calls = new Call;
        if ($request->agent) {
            $calls = $calls->where('callerNumber', $request->agent)->orWhere('dialDestinationNumber', $request->agent);
        }
        if ($request->start_date && $request->end_date) {
            $calls = $calls->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        return $calls->paginate(200);
    }

    public function realtime()
    {
        $analytic = new AnalyticController;
        $users = User::where('agent_sip', '!=', null)->get();

        $users->transform(function ($user) use ($analytic) {
            $user->today_calls = $analytic->today_calls($user);
            $user->talk_time = $analytic->outgoing_talk_time($user);
            $user->today_answered = $analytic->today_answered($user);
            $user->today_unanswered = $analytic->today_unanswered($user);
            $user->today_missed = $analytic->today_missed($user);
            return $user;
        });
        return $users;
    }

    public function on_call($id)
    {

        $lock = Cache::lock('userUpdate', 5);

        try {
            $lock->block(5);

            $status = ($id == 1) ? true : false;
            User::find(Auth::id())->update(['call_active' => $status]);
            // Lock acquired after waiting a maximum of 5 seconds...
        } catch (LockTimeoutException $e) {
            // Log::debug($e);
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    public function status_pusher(Request $request)
    {
        $user = User::find($request->events[0]['user_id']);

        if ($user) {
            if ($user->hasRole('Call center')) {

                $pusherSecret = env('PUSHER_APP_SECRET');

                // Get the signature provided by Pusher
                $pusherSignature = $request->header('X-Pusher-Signature');

                // Get the request body
                $payload = $request->getContent();

                // Generate a local signature using your Pusher secret
                $localSignature = hash_hmac('sha256', $payload, $pusherSecret);

                // Compare signatures
                if ($localSignature === $pusherSignature) {
                    $pusherSignature = $request->header('X-Pusher-Signature');
                    $status  = ($request->events[0]['name'] == 'member_added') ? 'online' : 'offline';


                    $lock = Cache::lock('userUpdate', 5);

                    try {
                        $lock->block(5);
                        $user->status = $status;
                        $user->save();
                        // Lock acquired after waiting a maximum of 5 seconds...
                    } catch (LockTimeoutException $e) {
                        // Log::debug($e);
                        // Unable to acquire lock...
                    } finally {
                        optional($lock)->release();
                    }
                } else {
                    // Signature is not valid, ignore or log the request as suspicious
                    // Log::warning('Suspicious Pusher webhook request: ' . $payload);
                }

                // Respond with a 200 OK
                return response('OK', 200);
            }
        }
    }

    public function __invoke($id, $status)
    {
        $user = User::find($id);
        $user->status = $status;
        $user->save();
        broadcast(new UserStatusEvent($user->id));
    }

    public function on_break(Request $request, $id)
    {
        if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super admin')) {
            User::find($id)->update(['on_break' => !$request->on_break]);
        } else {
            abort(422, 'Not allowed');
        }
    }
}
