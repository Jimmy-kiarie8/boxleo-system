<?php

namespace App\Http\Controllers\Callcentre;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\Missedcall;
use App\Models\orderStats;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class AnalyticController extends Controller
{
    public function is_agent()
    {
        return Auth::user()->hasRole('Call center');
    }
    public function callcentre()
    {
        $today = date('Y-m-d');

        $sales = Sale::setEagerLoads([])
            ->select(DB::raw('count(id) as count, delivery_status'))
            ->whereIn('delivery_status', ['Scheduled', 'Cancelled', 'Inprogress'])
            ->groupBy('delivery_status')
            ->whereDate('created_at', $today)
            ->get();

        $lables = [];
        $rows = [];

        foreach ($sales as $sale) {
            $lables[] = $sale->delivery_status;
            $rows[] = $sale->count;
        }

        $data = [
            'lables' => $lables,
            'rows' => $rows
        ];

        return response()->json(['data' => $data]);
    }

    public function callcenter_dashboard1(Request $request)
    {
        $date_range = json_decode($request->daterange);
        $start_date = $date_range ? Carbon::parse($date_range[0]) : Carbon::today();
        $end_date = $date_range ? Carbon::parse($date_range[1]) : Carbon::tomorrow();

        // Fetch all required data in a single query
        $sales_data = Sale::selectRaw('
            COUNT(*) as total_orders,
            SUM(CASE WHEN delivery_status = "Cancelled" THEN 1 ELSE 0 END) as cancelled,
            SUM(CASE WHEN delivery_status IN ("Scheduled", "Delivered", "Dispatched", "In Transit", "Returned", "Awaiting Dispatch") THEN 1 ELSE 0 END) as scheduled,
            SUM(CASE WHEN delivery_status = "Delivered" THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN delivery_status = "Pending" THEN 1 ELSE 0 END) as pending
        ')
            ->whereYear('created_at', Carbon::now()->year)
            ->when($this->is_agent(), function ($query) {
                return $query->where('agent_id', Auth::id());
            })
            ->first();

        // Fetch call data
        $call_data = Call::selectRaw('
            COUNT(*) as total_calls,
            SUM(CASE WHEN call_status = "unanswered" THEN 1 ELSE 0 END) as unanswered_calls,
            SUM(durationInSeconds) as total_duration,
            COUNT(CASE WHEN call_status = "Answered" THEN 1 END) as answered_calls
        ')
            ->whereYear('created_at', Carbon::now()->year)
            ->when($this->is_agent(), function ($query) {
                return $query->where('callerNumber', Auth::user()->agent_sip);
            })
            ->first();

        // Calculate rates
        $cancel_rate = $sales_data->total_orders > 0 ? round(($sales_data->cancelled / $sales_data->total_orders) * 100, 2) : 0;
        $schedule_rate = $sales_data->total_orders > 0 ? round(($sales_data->scheduled / $sales_data->total_orders) * 100, 2) : 0;
        $call_rate = $call_data->answered_calls > 0 ? round(($call_data->total_duration / 60) / $call_data->answered_calls, 2) : 0;
        $abandonment_rate = $call_data->total_calls > 0 ? round(($call_data->unanswered_calls / $call_data->total_calls) * 100, 2) : 0;

        // Fetch resolution rate data
        $resolution_rate = $this->calculate_resolution_rate([$start_date, $end_date]);

        return [
            'orders' => $sales_data->total_orders,
            'cancel_rate' => $cancel_rate,
            'schedule_rate' => $schedule_rate,
            'cancelled' => $sales_data->cancelled,
            'delivered' => $sales_data->delivered,
            'scheduled' => $sales_data->scheduled,
            'pending' => $sales_data->pending,
            'call_rate' => $call_rate . ' min',
            'abandonment_rate' => $abandonment_rate . '%',
            'resolution_rate' => $resolution_rate . '%',
            'volume' => $call_data->total_calls,
        ];
    }

    private function calculate_resolution_rate($date_range)
    {
        $statuses = ['Scheduled', 'Delivered', 'Dispatched', 'In Transit', 'Returned', 'Awaiting Dispatch'];

        $data = Sale::whereBetween('created_at', $date_range)
            ->selectRaw('
            COUNT(*) as total_sales,
            SUM(CASE WHEN delivery_status IN (?, ?, ?, ?, ?, ?) AND EXISTS (SELECT 1 FROM calls WHERE calls.sale_id = sales.id AND calls.durationInSeconds > 0) THEN 1 ELSE 0 END) as resolved_calls
        ', $statuses)
            ->first();

        return $data->total_sales > 0 ? round(($data->resolved_calls / $data->total_sales * 100), 2) : 0;
    }

    public function callcentre_performance()
    {
        $today = date('Y-m-d');
        $statuses = ['Scheduled', 'Delivered', 'Dispatched', 'In Transit', 'Returned', 'Awaiting Dispatch'];
    
        // Create a subquery to get all counts in one go
        $salesSubquery = Sale::selectRaw('
                agent_id,
                COUNT(*) as assign_count,
                COUNT(CASE WHEN delivery_status IN (' . implode(',', array_map(fn($status) => "'" . $status . "'", $statuses)) . ') THEN 1 END) as schedule_count,
                COUNT(CASE WHEN delivery_status = "Cancelled" THEN 1 END) as cancelled_count
            ')
            ->whereDate('created_at', $today)
            ->groupBy('agent_id');
    
        // Main query with join
        $users = User::role(['Call center', 'Follow UP'])
            ->where('active', true)
            ->where('ou_id', Auth::user()->ou_id)
            ->setEagerLoads([])
            ->leftJoinSub($salesSubquery, 'sales_counts', function ($join) {
                $join->on('users.id', '=', 'sales_counts.agent_id');
            })
            ->select([
                'users.*',
                'sales_counts.assign_count',
                'sales_counts.schedule_count',
                'sales_counts.cancelled_count'
            ])
            ->orderBy('sales_counts.schedule_count', 'desc')
            ->paginate(5);
    
        // Set default values for null counts
        $users->transform(function ($user) {
            $user->assign_count = $user->assign_count ?? 0;
            $user->schedule_count = $user->schedule_count ?? 0;
            $user->cancelled_count = $user->cancelled_count ?? 0;
            return $user;
        });
    
        return $users;
    }

    

    public function orders($date_range)
    {
        $today = [Carbon::today(), Carbon::tomorrow()];
        if (!empty($date_range)) {
            $today = $date_range;
        }
        return Sale::whereBetween('created_at', $today)->when($this->is_agent(), function ($q) {
            return $q->where('agent_id', Auth::id());
        })->count();
    }

    public function rate_calc($status, $date_range)
    {
        $orders = $this->orders($date_range);
        $rates = $this->statusCount($status, []);
        if ($orders) {
            return round(($rates / $orders) * 100, 2);
        }
        return 0;
    }

    public function call_rate($date_range)
    {
        $today = [Carbon::today(), Carbon::tomorrow()];
        if (!empty($date_range)) {
            $today = $date_range;
        }

        $calls = new Call;
        $calls = $calls->whereBetween('created_at', $today);
        $calls_count = $calls->whereBetween('created_at', $today);
        if ($this->is_agent()) {
            $calls = $calls->where('callerNumber', Auth::user()->agent_sip);
            $calls_count = $calls_count->where('callerNumber', Auth::user()->agent_sip)->where('call_status', 'Answered');
        }

        if ($calls_count->count() > 0) {
            $rate = ($calls->sum('durationInSeconds') / 60) / $calls_count->count();
        } else {
            $rate = 0;
        }


        return round($rate, 2) . ' min';
    }

    public function abandonment_rate()
    {
        $totalCalls = Call::whereDate('created_at', Carbon::today())->count();
        $unansweredCalls = Call::whereDate('created_at', Carbon::today())->where('call_status', 'unanswered')->count();
        $unansweredRate = ($totalCalls > 0) ? ($unansweredCalls / $totalCalls) * 100 : 0;
        return round($unansweredRate, 2) . '%';
    }

    public function resolution_rate()
    {
        // $sales = Sale::setEagerLoads([])->pluck('id');
        // Call::whereIn('sale_id', $sales)->;

        $statuses = ['Scheduled', 'Delivered', 'Dispatched', 'In Transit', 'Returned', 'Awaiting Dispatch'];


        $calls = Sale::whereDate('created_at', Carbon::today())->whereIn('delivery_status', $statuses)->whereHas('calls', function (Builder $query) {
            $query->where('durationInSeconds', '>', 0);
        })->count();
        $total_sale = Sale::whereDate('created_at', Carbon::today())->count();

        if ($total_sale > 0) {
            return round(($calls / $total_sale * 100), 2) . '%';
        }
        return 0 . '%';
    }

    public function volume()
    {
        $calls = new Call;
        // $calls = $calls->whereDate('created_at', Carbon::today());
        if (!Auth::user()->hasRole('Super admin')) {
            $calls = $calls->where('callerNumber', Auth::user()->agent_sip);
        }

        return $calls->count();;
    }


    public function callcenter_dashboard(Request $request)
    {
        $date_range = json_decode($request->daterange);

        return [
            'orders' => $this->orders($date_range),
            'cancel_rate' => $this->rate_calc('Cancelled', $date_range),
            'schedule_rate' => $this->rate_calc('Scheduled', $date_range),
            'cancelled' => $this->statusCount('Cancelled', $date_range),
            'delivered' => $this->statusCount('Delivered', $date_range),
            'scheduled' => $this->statusCount('Scheduled', $date_range),
            'pending' => $this->statusCount('Pending', $date_range),
            'call_rate' => $this->call_rate($date_range),
            'abandonment_rate' => $this->abandonment_rate(),
            'resolution_rate' => $this->resolution_rate(),
            'volume' => $this->volume(),
            // 'callcentre_performance' => $this->callcentre_performance()
        ];
    }

    public function statusCount($status, $date_range)
    {
        $today = [Carbon::today(), Carbon::tomorrow()];
        if (!empty($date_range)) {
            $today = $date_range;
        }
        $statuses = [];
        if ($status == 'Scheduled') {
            $statuses = ['Scheduled', 'Delivered', 'Dispatched', 'In Transit', 'Returned', 'Awaiting Dispatch'];
        }
        $sales = Sale::setEagerLoads([])
            ->select('id', 'ou_id')
            ->when(!empty($statuses), function ($q) use ($statuses, $today) {
                return $q->whereIn('delivery_status', $statuses)->whereBetween('created_at', $today);;
            })->when($status == 'Delivered', function ($q) use ($today, $status) {
                return $q->where('delivery_status', $status)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month);
            })->when($status == 'Cancelled', function ($q) use ($today, $status) {
                return $q->where('delivery_status', $status)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereBetween('created_at', $today);
            })->when($status == 'Pending', function ($q) use ($today, $status) {
                return $q->whereBetween('created_at', $today)
                    ->where('delivery_status', $status)
                    ->whereYear('created_at', Carbon::now()->year);
            });
        // ->where('delivery_status', $status);
        return $sales->count();
    }


    public function agent_chart($status)
    {
        $statuses = [];

        if ($status == 'Scheduled') {
            $statuses = ['Scheduled', 'Delivered', 'Dispatched', 'In Transit', 'Returned', 'Awaiting Dispatch'];
        }

        $today = date('Y-m-d');
        $counts = DB::table('sales')
            ->select('users.name as agent_name', DB::raw('COUNT(*) as count'))
            ->join('users', 'sales.agent_id', '=', 'users.id')
            ->when(!empty($statuses), function ($q) use ($statuses) {
                return $q->whereIn('sales.delivery_status', $statuses);
            })->when(empty($statuses), function ($q) use ($status) {
                return $q->where('sales.delivery_status', $status);
            })
            ->where('users.ou_id', Auth::user()->id)

            ->groupBy('sales.agent_id', 'users.name')
            ->whereDate('sales.created_at', $today)
            ->get();

        $lables = $counts->pluck('agent_name')->toArray();
        $rows = $counts->pluck('count')->toArray();

        $data = [
            'lables' => $lables,
            'rows' => $rows,
        ];

        return response()->json(['data' => $data]);
    }







    public function user_online()
    {
        return true;
    }


    public function total_calls($agent_name)
    {
        $today = date('Y-m-d');
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            })->whereDate('created_at', $today);
        }
        return $call = number_format($call->count());
    }
    public function today_calls($agent_name)
    {
        // DB::enableQueryLog(); // Enable query log
        $today = date('Y-m-d');
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            })->whereDate('created_at', $today);
        }
        return  number_format($call->whereDate('created_at', Carbon::today())->count());
        // dd(DB::getQueryLog()); // Show results of log
    }
    public function today_answered($agent_name)
    {
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            });
        }
        return number_format($call->whereDate('created_at', Carbon::today())->where('call_status', 'Answered')->count());
    }
    public function today_unanswered($agent_name)
    {
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            });
        }
        return number_format($call->whereDate('created_at', Carbon::today())->where('call_status', 'No answer')->count());
    }
    public function today_missed($agent_name)
    {
        return number_format(Missedcall::count());
    }
    public function today_incoming($agent_name)
    {
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            });
        }
        return number_format($call->whereDate('created_at', Carbon::today())->where('direction', 'Inbound')->count());
    }
    public function today_outgoing($agent_name)
    {
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            });
        }
        $agent_name = Auth::user();
        return number_format($call->whereDate('created_at', Carbon::today())->where('direction', 'Outbound')->count());
    }
    public function outgoing_talk_time($agent_name)
    {
        $call = new Call;
        if ($agent_name != 'all') {
            $agent = $agent_name->agent_sip;
            $call = $call->where(function ($q) use ($agent) {
                $q->where('dialDestinationNumber', $agent)->orWhere('callerNumber', $agent);
            });
        }
        $deration = $call->whereDate('created_at', Carbon::today())->where('direction', 'Outbound')->where('call_status', 'No answer')->sum('durationInSeconds');

        return round($deration / 3600, 2);
        // return $deration / 60;
    }


    public function agent_performance($agent_name)
    {
        $agents = User::setEagerLoads([])->with(['calls' => function ($q) {
            $q->whereDate('created_at', Carbon::today());
        }])->where('agent_sip', '!=', null)->inRandomOrder()->take(3)->get();
        $agents->transform(function ($agent) {
            $calls = 0;
            $answered = 0;
            $talktime = 0;
            $unanswered = 0;
            foreach ($agent->calls as $item) {
                $calls += 1;
                $talktime += $item->durationInSeconds;
                if ($item['call_status'] == 'Answered') {
                    $answered += 1;
                }
                if ($item['call_status'] == 'No answer') {
                    $unanswered += 1;
                }
            }
            $agent->total_calls = $calls;
            $agent->answered = $answered;
            $agent->unanswered = $unanswered;
            $agent->talktime = number_format($talktime / 3600, 2);
            return $agent;
        });
        return $agents;
    }

    public function call_graph($agent_name)
    {
        $order = DB::table('calls')->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'));
        $order = $order->orderBy('id', 'asc')->groupBy('date')->get()->toArray();
        $value_arr = [];
        foreach ($order as $value) {
            $value_arr[] = [$value->date, $value->count];
        }
        return $value_arr;
    }

    public function best_performing()
    {
        $users = User::select('name', 'status', 'on_break', 'call_active', 'agent_sip')->setEagerLoads([])->withCount('inbounds')->withCount('outbounds')->get();
        // ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
        $users->transform(function ($user) {
            $user->calls_count = (int)$user->inbounds_count + (int)$user->outbounds_count;
            return $user;
        });
        $user = collect($users)->sortByDesc('calls_count')->first();
        return $user->name;
    }
    public function agents()
    {
        return number_format(User::setEagerLoads([])->where('agent_sip', '!=', null)->count());
    }
    public function total_spent($agent)
    {
        $call = new Call;
        if ($agent != 'all') {
            $call = $call->where('dialDestinationNumber', $agent->agent_sip)->orWhere('callerNumber', $agent->agent_sip);
        }
        return $call = format_currency($call->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->sum('amount'));
    }

    public function inbound()
    {
        return number_format(Call::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->where('direction', 'Inbound')->count());
    }
    public function outbound()
    {
        return number_format(Call::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->where('direction', 'Outbound')->count());
    }

    // public function credit()
    // {
    //     $credit = Credit::first();

    //     return $credit;
    // }

    // public function data(Request $request)
    // {
    //     $month_leads = Lead::whereDate('created_at', now()->month)->count();
    //     $month_closed_leads = Lead::where('status', 'Closed')->whereDate('created_at', now()->month)->count();
    //     $month_open_leads = Lead::where('agent_id', null)->whereDate('created_at', now()->month)->count();
    //     $month_pending_leads = Lead::where('status', 'Closed')->whereDate('created_at', now()->month)->count();
    //     $month_recalls_leads = Lead::whereDate('created_at', now()->month)->count();
    // }

    public function dashboard_data($agent)
    {
        // if (!Auth::user()->hasRole('Super admin')) {
        //     //  return Auth::user()->agent_sip;
        //     $agent = Auth::user();
        // }
        // dd($agent);
        $data['total_calls'] = $this->total_calls($agent);
        $data['today_calls'] = $this->today_calls($agent);
        $data['today_answered'] = $this->today_answered($agent);
        $data['today_unanswered'] = $this->today_unanswered($agent);
        $data['today_missed'] = $this->today_missed($agent);
        $data['today_incoming'] = $this->today_incoming($agent);
        $data['today_outgoing'] = $this->today_outgoing($agent);
        $data['outgoing_talk_time'] = $this->outgoing_talk_time($agent);
        // $data['agent_performance'] = $this->agent_performance($agent);
        $data['call_analysis'] = ['Answered' => $this->today_answered($agent), 'Missed' => $this->today_unanswered($agent)];
        $data['best_performing'] = $this->best_performing();
        $data['total_talk_time'] = $this->outgoing_talk_time('all');
        $data['agents'] = $this->agents();
        $data['total_spent'] = $this->total_spent($agent);
        $data['inbound'] = $this->inbound();
        $data['outbound'] = $this->outbound();
        // $data['credit'] = $this->credit();
        return $data;
    }

    public function agent_data()
    {
        // return Auth::user();
        $agent = Auth::user();
        $data['today_calls'] = $this->today_calls($agent);
        $data['today_unanswered'] = $this->today_unanswered($agent);
        $data['today_incoming'] = $this->today_incoming($agent);
        $data['today_answered'] = $this->today_answered($agent);
        $data['today_missed'] = $this->today_missed($agent);
        return $data;
    }
}
