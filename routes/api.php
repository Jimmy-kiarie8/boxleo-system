<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Callcentre\CallController;
use App\Http\Controllers\Callcentre\VoiceController;
use App\Models\User;
use App\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use Illuminate\Validation\ValidationException;

/*
Route::group(['prefix' => 'api'], function () {
    Route::get('tenant_exists/{id}', function ($tenant) {
    dd(1);
    return $tenant = Tenant::find('some-id');
});
});

Route::group(['prefix' => 'api'], function () {
    Route::middleware([
        'api',
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
    ])->group(function () {
        // Route::get('order/{order}', 'Api\ApiController@order_tracking');
        Route::get('order/{order}', 'Api\ApiController@order_tracking');
        Route::group(['middleware' => ['auth:api']], function () {
            Route::resource('orders', 'Api\SaleController');
            Route::post('orders_store', 'Api\SaleController@orders_store');

            Route::get('warehouse', 'Api\ApiController@warehouse');
            Route::get('ou', 'Api\ApiController@ou');
            Route::get('clients', 'Api\ApiController@clients');
            Route::get('products', 'Api\ApiController@products');
        });

        Route::any('webhook_orders', 'Api\woocommerceController@webhook_orders')->name('webhook_orders');
    });
});

*/



Route::group(['prefix' => 'v2'], function () {
        Route::any('elementer-order/create', 'Api\APiSaleController@elementor');
});



Route::post('admin-login', 'Api\LoginController@userLogin');



Route::group(['middleware' => ['auth:api']], function () {
    Route::post('analysis/{report}', [AnalysisController::class, 'analysis']);
    Route::get('agents', [AnalysisController::class, 'agents']);
});


Route::put('user/{user}/{status}', [CallController::class, '__invoke']);

Route::any('status-pusher', [CallController::class, 'status_pusher']);


Route::any('/voice', [VoiceController::class, 'voice']);
Route::any('/event', [VoiceController::class, 'event']);


Route::any('/bins', 'Warehouse\WarehouseController@bins');
Route::any('/levels', 'Warehouse\WarehouseController@levels');

Route::any('/stk_response/{order_no}', 'MpesaController@stk_response');
Route::any('plugin_orders', 'Marketplace\WoocommerceController@plugin_orders');


Route::any('/charges/{city}', 'Api\ApiController@charges');

Route::any('/validation', 'MpesaController@validation_url')->name('validation_url');
// Route::any('/confirmation', 'MpesaController@confirmation')->name('confirmation');
Route::any('/confirmation', 'MpesaController@confirmation')->name('confirmation')->middleware('mpesaIp');



Route::group(['prefix' => 'v1'], function () {
    Route::resource('webhook', 'Api\WebhookController');

    Route::post('login', 'Api\LoginController@login');
    Route::any('woocommerce_callback', 'Marketplace\WoocommerceController@woocommerce_callback');
    // Route::any('woocommerce_callback/{data}', 'Marketplace\WoocommerceController@woocommerce_callback');
    Route::any('woocommerce_return', 'Marketplace\WoocommerceController@woocommerce_return');
    Route::any('shop_order_webhook', 'Marketplace\ShopifyappController@shop_order_webhook');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::resource('orders', 'Api\SaleController');
        // Route::post('orders_store', 'Api\SaleController@orders_store');

        Route::get('warehouse', 'Api\ApiController@warehouse');
        Route::get('ou', 'Api\ApiController@ou');
        Route::get('clients', 'Api\ApiController@clients');
        Route::get('products', 'Api\ApiController@products');
    });
    Route::get('order/{order}', 'Api\ApiController@order_tracking');

    Route::any('webhook_orders', 'Marketplace\woocommerceController@webhook_orders')->name('webhook_orders');

    Route::group(['prefix' => 'callcenter'], function () {
        Route::post('login', 'Api\AuthController@login');


        Route::get('statuses', 'Api\CallcentreController@status');
        Route::group(['middleware' => ['auth:api-callcentre']], function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
            Route::get('sales/{status}', 'Api\CallcentreController@sales');
            Route::post('status_update/{id}', 'Api\CallcentreController@status_update');
        });
    });
});





Route::group(['prefix' => 'v2'], function () {
    // Route::get('product/{id', 'Api\APiSaleController@ml_orders');
    Route::post('login', 'Api\LoginController@login');
    Route::get('product_analytics/{status}', [DashboardController::class, 'product_analytics']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('latest_orders', 'Api\APiSaleController@latest_orders');
        Route::get('user', 'Api\APiSaleController@user');

        Route::get('products', 'Api\APiSaleController@products');
        Route::get('order/search/{search}', 'Api\APiSaleController@search');
        Route::any('dashboard_count', [DashboardController::class, 'dashboard_count']);

        Route::post('order/create', 'Api\APiSaleController@store');
        Route::get('order/details/{order_no}', 'Api\APiSaleController@show');
        Route::post('order/details', 'Api\APiSaleController@bulk_search');
        Route::post('products/create', 'Api\APiSaleController@product');
        Route::post('add_product/{order_no}', 'Api\APiSaleController@add_product');
        Route::post('bulk_orders/create', 'Api\APiSaleController@bulk_orders');
        Route::post('bulk_products', 'Api\APiSaleController@bulk_products');

        Route::post('order/cancel/{order_no}', 'Api\APiSaleController@cancel');
        Route::get('status', 'Api\APiSaleController@status');
        Route::post('webhook', 'Api\APiSaleController@webhook_create');

        Route::post('shipping_charges', 'Api\APiSaleController@shipping_charges');

        Route::post('waybills', 'Api\APiSaleController@waybills');

        Route::get('product_variants/{search}', 'Api\ApiController@product_variants');



    });
});



Route::get('ou', 'OuController@index');
Route::get('warehouse/{id}', 'Warehouse\WarehouseController@index');
Route::any('shopify_app', 'Marketplace\ShopifyOrderController@shopify_app');

Route::group(['prefix' => 'shopify'], function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => auth('api')->user()
        ]);
        // return $request->user();
    });
    Route::post('login', 'Api\LoginController@shop_login');
    Route::post('webhook/{id}', 'Api\WebhookController@webhook');
    Route::post('fulfillment', 'Api\WebhookController@fulfillment');
    Route::get('analytics', 'Marketplace\AnalyticController@analytics');
    Route::get('app_assigned', 'Marketplace\ShopifyOrderController@app_assigned');
});




// Route::group(['prefix' => 'warehouse'], function () {
//     Route::post('/sanctum/token', function (Request $request) {
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//             'device_name' => 'required',
//         ]);

//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             throw ValidationException::withMessages([
//                 'email' => ['The provided credentials are incorrect.'],
//             ]);
//         }

//         $token = $user->createToken($request->device_name)->plainTextToken;

//         return response()->json([
//             'success' => true,
//             'token' => $token,
//             // 'user' => $user
//         ]);
//         // return ['token' => $user->createToken($request->device_name)->plainTextToken, 'user'=>$user];
//     });
// });

Route::group(['prefix' => 'mobile'], function () {
    Route::post('login', 'Api\AuthController@login');

    Route::post('/sanctum/token', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = Rider::where('email', $request->email)->where('active', true)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            // 'user' => $user
        ]);
        // return ['token' => $user->createToken($request->device_name)->plainTextToken, 'user'=>$user];
    });






    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('logout', 'Api\ApiController@logout_rider');
        Route::get('user', 'Api\ApiController@user');
        Route::post('on_duty', 'Api\ApiController@on_duty');
        Route::post('order_filter', 'Api\ApiController@order_filter');
        Route::post('api_sms/{id}', 'Api\ApiController@api_sms');
        Route::post('search_order/{search}', 'Api\ApiController@search_order');

        Route::get('complete/{status}', 'Api\ApiController@complete');
        Route::any('/stk_push/{id}/{phone}', 'MpesaController@stk_push');

        Route::get('orders', 'Api\ApiController@orders');

        Route::get('orders/{id}', 'Api\ApiController@order');
        Route::get('order_scan/{order_no}', 'Api\ApiController@order_scan');
        Route::get('order_status/{status}', 'Api\ApiController@order_status');
        // Route::any('upload', 'Api\ApiController@upload');
        Route::any('image/{id}', 'Api\ApiController@image');
        Route::get('dashboard_stats', 'Api\ApiController@dashboard_stats');
        Route::any('mobile_status/{id}', 'Api\ApiController@mobile_status');
        Route::get('payment_confirmation/{id}', 'Api\ApiController@payment_confirmation');


        Route::post('updateCoordinates/{id}', 'Api\ApiController@updateCoordinates');



        // Warehouse app
    });

    Route::group(['middleware' => ['api']], function () {
        Route::get('warehouse_orders', 'Warehouse\MobileController@warehouse_orders');
        Route::get('status/{id}', 'Warehouse\MobileController@status');
    });
});



Route::group(['prefix' => 'agent'], function () {
    Route::post('login', 'Api\AuthController@login');

    Route::post('/sanctum/token', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            // 'user' => $user
        ]);
        // return ['token' => $user->createToken($request->device_name)->plainTextToken, 'user'=>$user];
    });



    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('logout', 'Api\Agent\AgentApiController@logout_rider');
    Route::get('user', 'Api\Agent\AgentApiController@user');
    Route::post('order_filter', 'Api\Agent\AgentApiController@order_filter');
    Route::post('api_sms/{id}', 'Api\Agent\AgentApiController@api_sms');
    Route::post('search_order/{search}', 'Api\Agent\AgentApiController@search_order');

    Route::get('complete/{status}', 'Api\Agent\AgentApiController@complete');
    Route::any('/stk_push/{id}/{phone}', 'MpesaController@stk_push');

    Route::get('orders', 'Api\Agent\AgentApiController@orders');
    Route::post('order-create', 'Api\Agent\AgentApiController@store');
    Route::get('order/{id}', 'Api\Agent\AgentApiController@order');
    Route::post('order/comment/{id}', 'Api\Agent\AgentApiController@comment');
    Route::get('order_scan/{order_no}', 'Api\Agent\AgentApiController@order_scan');
    Route::get('order_status/{status}', 'Api\Agent\AgentApiController@order_status');
    // Route::any('upload', 'Api\Agent\AgentApiController@upload');
    Route::any('image/{id}', 'Api\Agent\AgentApiController@image');
    Route::get('dashboard_stats', 'Api\Agent\AgentApiController@dashboard_stats');
    Route::any('mobile_status/{id}', 'Api\Agent\AgentApiController@mobile_status');
    Route::get('payment_confirmation/{id}', 'Api\Agent\AgentApiController@payment_confirmation');



    // Warehouse app
    // });

    Route::group(['middleware' => ['api']], function () {
        Route::get('warehouse_orders', 'Warehouse\MobileController@warehouse_orders');
        Route::get('status/{id}', 'Warehouse\MobileController@status');
    });
});
