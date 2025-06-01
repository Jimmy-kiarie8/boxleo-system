<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function send_returns(Request $request)
    {
        $data = $request->all();
        $ids = collect($data)->pluck('id');
        $statuses = ['In Transit', 'Dispatched'];
        return Sale::whereIn('delivery_status', $statuses)->whereIn('id', $ids)->update(['status' => 'Awaiting Return', 'delivery_status' => 'Awaiting Return', 'return_date' => now()]);
    }
}
