<?php

namespace App\Http\Controllers\Warehouse;

use App\Events\MobileEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse\Area;
use Illuminate\Http\Request;
use App\Notifications\Mobile\Notify;
use Illuminate\Support\Facades\Notification;

class MobileController extends Controller
{
    public function warehouse_orders()
    {
        $sales = Sale::setEagerLoads([])->with(['client', 'products'])->paginate(10);

        $sale_transform = new Sale;
        $sale_transform->trans_sales($sales, 'custom', '');
        return SaleResource::collection($sales);
    }
    public function status(Request $request, $id)
    {
        $user = auth('web')->user();
        $status = $request->status;
        $data = ['delivery_status' => $status, 'status' => $status];

        $status = new Status();
        $status->status_update(new Request($data), $id);

        $message = 'Order updated ' . $status . ' by <b style="color: red">' . $user->name . '</b>';
        $action = $id;
        $users = User::all();
        broadcast(new MobileEvent($user, $message, $action))->toOthers();
        Notification::send($users, new Notify($user, $message, $action));
        
    }
}
