<?php

namespace App;

use App\Models\BranchSale;
use App\Models\Sale;
use App\Notifications\Branch\BranchResetPassword;
use App\Notifications\Branch\BranchVerifyEmail;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class Branch extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'phone', 'delivery_charges', 'return_charges'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new BranchResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new BranchVerifyEmail);
    }


    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'branch_sales', 'sale_id', 'branch_id', 'sale_id');
    }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('d M Y');
    }

    public function branch_update(Request $request, $id)
    {
        // dd($request->all());
        $product_ = [];
        $product_arr = [];
        foreach ($request->products as  $product) {
            $product_['product_id'] = $product['id'];
            $product_['quantity_sent'] = $product['pivot']['quantity_sent'];
            $product_['quantity_returned'] = $product['pivot']['quantity_returned'];
            $product_['quantity_delivered'] = $product['pivot']['quantity_delivered'];
            // $product_['quantity_received'] = 0;
            $product_arr[] = $product_;
        }
        // return $product_arr;
        BranchSale::updateOrCreate(
            [
                'branch_id' => $request->branch_id,
                'sale_id' => $request->id
            ],
            [
                'delivery_status' => 'Pending',
                'receive_status' => 'pending',
                // 'sent' => '$request->sent',
                // 'received' => $request->received,
                'comment' => $request->comment,
                'products' => $product_arr
            ]
        );
        $branch = Branch::find($request->branch_id);
        // return $branch->delivery_charges;
        $sale = Sale::find($id);
        $sale->history_comment = 'Order assigned to ' . $branch->name . ' branch';
        // $sale->shipping_charges += $branch->delivery_charges;
        $sale->save();
    }
}
