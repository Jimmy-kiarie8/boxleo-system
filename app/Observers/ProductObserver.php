<?php

namespace App\Observers;

use App\Models\AutoGenerate;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function logged_user()
    {
        $user = new User;
        return  $user->logged_user();
    }
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {

        if (Auth::guard('seller')->check()) {
            $user = User::where('email', env('ADMIN_MAIL'))->first();
        } else {
            $user = User::find($product->user_id);
        }

        $history = new ProductHistory();
        $history->action = 'Product Created';
        $reference_no = IdGenerator::generate(['table' => 'product_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $product->comment;
        
        $history->update_comment = 'Product created by ' . '<b style="color: red;">' . $user['name'] . '</b>';
        $history->reference_no = $reference_no;
        $history->user_id = $user->id;

        $history->product_id = $product->id;
        $history->save();
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        // dd('test');
        // $arrayName = array('onhand', 'updated_at');
        // $result = array_diff_key($arrayName, array_keys($product->getDirty()));
        // // dd(array_flatten(array_keys($product->getDirty())));
        // dd($result);
        // if (empty($result)) {
        //     return;
        // }
        if ($product->getDirty()) {
            $user = $this->logged_user();

            $history = new ProductHistory();

            $history->action = 'product Created';
            $reference_no = IdGenerator::generate(['table' => 'product_histories', 'field' => 'reference_no', 'length' => 8, 'prefix' => 'REF-']);
            $history->comment = $product->comment;
            $history->update_comment = $product->update_comment;
            $history->reference_no = $reference_no;

            $history->user_id = $user->id;
            $history->product_id = $product->id;
            $history->save();
        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        // dd('$product->instructions');
        $user = $this->logged_user();
        $history = new ProductHistory();
        $history->action = 'product Created';
        $reference_no = IdGenerator::generate(['table' => 'product_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $product->comment;
        $history->update_comment = 'Product deleted';
        $history->reference_no = $reference_no;

        $history->user_id = $user->id;
        $history->product_id = $product->id;
        $history->save();
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        $history = new History();
        $history->user_id = Auth::id();
        $history->action = 'Restored';
        $history->product_id = $product->id;
        $reference_no = new AutoGenerate;
        $history->instructions = $product->instructions;
        $history->reference_no = $reference_no->ProdReferenceNo();
        $history->save();
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        $history = new History();
        $history->user_id = Auth::id();
        $history->product_id = $product->id;
        $history->action = 'Force Deleted';
        $reference_no = new AutoGenerate;
        $history->instructions = $product->instructions;
        $history->reference_no = $reference_no->ProdReferenceNo();
        $history->save();
    }
}
