<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Callcentre\AnalyticController;
use App\Http\Controllers\Callcentre\AudioController;
use App\Http\Controllers\Callcentre\CallController;
use App\Http\Controllers\Callcentre\MissedcallController;
use App\Http\Controllers\Callcentre\VoiceController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\MigrateController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\OrderConfirmController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ShippingReqController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\Warehouse\ReportController as WarehouseReportController;
use App\Http\Controllers\Warehouse\ShipmentRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Jetstream;

use App\Http\Controllers\WmsController;
use App\Http\Controllers\CourierController;




// Route::get('/sms-test', [SmsController::class, 'index']);

// Route::get('token', [MpesaController::class, 'token']);

// Route::get('counts/{id}', [TestController::class, 'counts']);

// Route::get('getRoute/{address}', [TestController::class, 'getRoute']);

Route::get('2fa/setup', [TwoFactorController::class, 'showSetupForm'])->name('2fa.setup')->middleware('auth');
Route::post('2fa/setup', [TwoFactorController::class, 'verify'])->name('2fa.verify')->middleware('auth');


Route::get('/2fa-challenge', [TwoFactorController::class, 'showChallenge'])->name('2fa.verifyForm')->middleware('auth');
Route::post('/2fa-challenge', [TwoFactorController::class, 'verifyChallenge'])->middleware('auth');




Route::get('thank-you', [OrderConfirmController::class, 'thanks'])->name('thanks');
Route::get('online-form/{order_no}', [OrderConfirmController::class, 'show'])->name('order-confirmation');
Route::post('online-form/{order_no}', [OrderConfirmController::class, 'update'])->name('online-form');


Route::view('/track', 'track');
Route::get('waybill/{waybill}', 'Admin\AdminController@track');


Route::view('/lables', 'pdf.shippinglable');

Route::post('remit', 'ReportController@remit');




Route::any('/stk_push/{id}/{phone}', 'MpesaController@stk_push');


// Route::view('waybill_1', 'pdf.templates/waybill1');
// Route::get('waybill_templates', 'DownloadController@waybill_templates');

// Route::get('lables/{id}', 'DownloadController@lables');



Route::view('/new_signin', 'admin.newsignin');
Route::get('/session_exists', function () {
    $user = Auth::user();
    return view('auth.session', compact('user'));
});

Route::post('delete_session', 'UserController@delete_session')->name('delete_session');


Route::get('welcome/{user}', 'WelcomeController@showWelcomeForm')->name('welcome');
Route::post('welcome/{user}', 'WelcomeController@savePassword');

Route::get('wel', 'WelcomeController@wel');


Route::resource('ous', 'OuController');

Route::post('/zone_setup', 'SetupController@zones');


Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])
            ->name('profile.show');

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
            Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
            Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
        }
    });
});





Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('company_details', 'SetupController@company_details');


Route::get('/account_setup', 'SetupController@account_setup')->name('account_setup');
Route::post('/account', 'SetupController@account')->name('account');
Route::get('/tenant_sub', 'SetupController@tenant_sub')->name('tenant_sub');


Route::group(['middleware' => []], function () {

    // Auth::routes(['verify' => true]);
    // Route::post('logged_in', [LoginController::class, 'authenticate']);

    Auth::routes();
    Route::group(['middleware' => ['authcheck']], function () {
    // Route::group(['middleware' => ['authcheck', '2fa']], function () {
        Route::impersonate();


        Route::get('/payment', 'Subscription\SubscriptionController@account_expired')->name('account_expired');


        Route::get('/home', 'HomeController@index');
        Route::get('/', 'HomeController@index')->name('boxleo');
        Route::resource('users', 'UserController');
        Route::resource('roles', 'RoleController');
        Route::resource('clients', 'ClientController');
        Route::resource('groups', 'GroupController');
        Route::resource('drawers', 'DrawerController');
        Route::resource('discounts', 'OfferController');
        Route::resource('variants', 'VariantController');
        Route::resource('sku', 'SkuController');
        Route::resource('menu', 'MenuController');
        Route::resource('categories', 'CategoryController');
        Route::resource('subcategories', 'SubcategoryController');
        Route::resource('brands', 'BrandController');
        Route::resource('images', 'ImageController');
        Route::resource('slider', 'SliderController');
        Route::resource('currencies', 'CurrencyController');
        // Route::resource('warehouses', 'WarehouseController');
        Route::resource('comments', 'CommentController');
        Route::resource('invoices', 'InvoiceController');
        Route::resource('packages', 'PackageController');
        Route::resource('columns', 'CustomViewController');
        Route::resource('subscriptions', 'SubscriptionController');
        Route::resource('setup', 'SetupController');
        Route::resource('returns', 'ReturnSaleController');
        Route::patch('return_status_update/{id}', 'ReturnSaleController@return_status_update')->name('return_status_update');
        Route::patch('returns_receive/{id}', 'ReturnSaleController@returns_receive')->name('returns_receive');


        Route::resource('transfers',TransferController::class);

        Route::post('/stock_transfer', [TransferController::class, 'stock_transfer']);
        Route::post('/receive_transfer/{reference}', [TransferController::class, 'receive_transfer']);


        Route::get('updateCell', 'Marketplace\SheetController@updateCell');

        Route::resource('sheets', 'Marketplace\SheetController');
        Route::get('zone_sheets', 'Marketplace\SheetController@zone_sheets');
        Route::post('zone_sheets_update', 'Marketplace\SheetController@zone_sheets_create');
        Route::patch('zone_sheets_update/{id}', 'Marketplace\SheetController@zone_sheets_update');
        Route::patch('google_status/{id}', 'Marketplace\SheetController@google_status');
        Route::patch('google_current/{id}', 'Marketplace\SheetController@google_current');
        Route::post('google_sync/{id}', 'Marketplace\SheetController@google_sync');
        Route::patch('sheet_update/{id}', 'Marketplace\SheetController@sheet_update');

        Route::post('sheet_sync/{id}', 'Marketplace\SheetController@sheet_sync');


        Route::get('/marketplace', 'Marketplace\MarketplaceController@marketplace');
        Route::get('/shopifyapp_orders', 'Marketplace\ShopifyappController@orders');

        Route::post('sync_products', 'Marketplace\ShopifyappController@sync_products');
        Route::any('sync_orders', 'Marketplace\ShopifyappController@sync_orders');
        Route::any('close_order/{id}', 'Marketplace\ShopifyappController@close_order');
        Route::any('update_order/{id}', 'Marketplace\ShopifyappController@update_order');
        Route::any('payment_order/{id}', 'Marketplace\ShopifyappController@payment_order');
        Route::any('fulfill_order/{id}', 'Marketplace\ShopifyappController@fulfill_order');

        Route::post('print_mult_change', 'StatusController@print_mult_change')->name('print_mult_change');
        Route::post('sales_update', 'StatusController@sales_update')->name('sales_update');
        Route::patch('undispatch_status/{id}', 'StatusController@undispatch_status')->name('undispatch_status');
        Route::post('undispatch_multiple_status', 'StatusController@undispatch_multiple_status')->name('undispatch_multiple_status');

        Route::patch('assign_agent/{id}', 'StatusController@assign_agent')->name('assign_agent');
        Route::patch('assign_agent', 'StatusController@unassign_agent')->name('assign_agent');

        Route::any('woocommerce_sync_products', 'Marketplace\WoocommerceController@sync_products');
        Route::any('woocommerce_sync_orders', 'Marketplace\WoocommerceController@sync_orders');

        Route::any('webhook_list', 'Marketplace\WoocommerceController@webhook_list');
        Route::any('woocommerce_webhook', 'Marketplace\WoocommerceController@woocommerce_webhook')->name('woocommerce_webhook');
        Route::patch('woocommerce_settings/{id}', 'Marketplace\WoocommerceController@woocommerce_settings');


        Route::post('/backup', 'SettingController@backup')->name('backup');
        Route::post('/dbrestore', 'SettingController@dbrestore')->name('dbrestore');
        Route::get('/dbbackups', 'SettingController@dbbackups')->name('dbbackups');
        Route::patch('webhook_create/{id}', 'shopify\SettingController@webhook');
        Route::get('webhook_deactivate/{id}', 'shopify\SettingController@webhook_deactivate');
        Route::get('get_webhooks', 'shopify\SettingController@get_webhooks');

        Route::post('/ou_switch', 'OuController@ou_switch');


        Route::patch('shopify_status/{id}', 'Marketplace\ShopifyappController@shopify_status');
        Route::patch('shopify_settings/{id}', 'Marketplace\ShopifyappController@shopify_settings');

        Route::get('shopify_stores', 'Marketplace\ShopifyappController@shopify_stores');
        Route::get('shopify_webhook', 'Marketplace\ShopifyappController@shopify_webhook');

        Route::get('shopify_webhook_delete', 'Marketplace\ShopifyappController@shopify_webhook_delete');
        Route::get('app_shop', 'Marketplace\ShopifyappController@app_shop');

        Route::any('woocommerce_orders', 'Marketplace\WoocommerceController@woocommerce_orders');
        Route::any('woocommerce_auth/{vendor_id}', 'Marketplace\WoocommerceController@woocommerce_auth');
        Route::any('woocommerce_connect/{vendor_id}', 'Marketplace\WoocommerceController@woocommerce_auth');
        Route::any('woocommerce_stores', 'Marketplace\WoocommerceController@woocommerce_stores');
        Route::patch('woocommerce_status/{id}', 'Marketplace\WoocommerceController@woocommerce_status');


        Route::resource('branch_sale', 'BranchSaleController');
        Route::resource('zones', 'ZoneController');
        Route::resource('sale_zones', 'SaleZoneController');
        Route::resource('cities', 'CityController');
        Route::resource('product_history', 'ProductHistoryController');
        Route::resource('sms_template', 'SmsTemplateController');
        Route::put('sms_update/{id}', 'SmsTemplateController@update');
        Route::delete('sms/{id}', 'SmsTemplateController@destroy');

        Route::patch('zones_default/{id}', 'ZoneController@zones_default');



        Route::resource('charges', 'ChargeController');

        Route::resource('services', 'ServiceController');
        Route::get('checker/{id}', 'ServiceController@charge_service');
        Route::post('charges_apply', 'ServiceController@charges_apply');
        Route::post('vendor_services', 'ServiceController@vendor_services');

        Route::resource('company', 'CompanyController');
        Route::post('company_logo', 'CompanyController@company_logo')->name('company_logo');

        Route::resource('automation', 'AutomationController');
        Route::get('action/{id}', 'AutomationController@action')->name('action');


        Route::resource('mailable', 'MailableController');
        Route::get('testmail', 'MailableController@testmail')->name('testmail');

        Route::resource('custom_reports', 'ReportController');
        Route::post('reports', 'ReportController@reports')->name('reports');
        Route::post('count_data', 'ReportController@count_data');
        Route::any('custom_report/{id}', 'ReportController@custom_report')->name('custom_report');
        Route::post('/remmited', 'ReportController@remmited')->name('remmited');
        Route::post('ex_download', 'ReportController@ex_download')->name('ex_download');
        Route::post('report_download', 'ReportController@report_download')->name('report_download');
        Route::post('download-excel', 'ReportController@downloadExcel');



        Route::post('sale_filter', 'FilterController@sale_filter')->name('sale_filter');
        Route::get('searchLeads/{phone}', 'FilterController@searchLeads')->name('searchLeads');
        Route::post('/mobile_filter', 'FilterController@mobile_filter');
        Route::post('/status_counts', 'FilterController@status_counts');
        Route::get('/files', 'FilterController@files');
        Route::get('search_order/{phone}', 'FilterController@search_order')->name('search_order');

        Route::resource('app_custom', 'AppCustomController');
        Route::get('cusom_edit/{id}', 'AppCustomController@cusom_edit')->name('cusom_edit');

        Route::resource('statuses', 'StatusController');
        Route::any('status_update/{id}', 'StatusController@status_update')->name('status_update');
        Route::post('dispatch_orders', 'StatusController@dispatch_orders')->name('dispatch_orders');
        Route::patch('print_change/{id}', 'StatusController@print_change')->name('print_change');
        Route::any('update_order_status', 'StatusController@update_order_status');
        Route::post('/approve_delivery/{id}', 'StatusController@approve_delivery');

        Route::get('dispatch-print/{id}', [DispatchController::class, 'print_dispatch']);

        Route::get('tenantplan/{id}', 'Admin\PlanController@tenantplan');

        Route::post('/send_returns', [AgentController::class, 'send_returns']);

        Route::resource('dispatches', DispatchController::class);

        Route::post('/dispatch_approval', [DispatchController::class, 'dispatch_approval']);


        Route::resource('sales', 'SaleController');
        Route::patch('confirm/{id}', 'SaleController@confirm')->name('confirm');
        Route::post('pos_sale', 'SaleController@pos_sale')->name('pos_sale');
        Route::get('tracking/{status}', 'SaleController@tracking')->name('tracking');
        Route::post('sales_delete', 'SaleController@sales_delete')->name('sales_delete');
        Route::any('order_search/{search}', 'SaleController@order_search')->name('order_search');
        Route::get('scan/{search}', 'SaleController@scan')->name('scan');
        Route::get('undispatch/{search}', 'SaleController@undispatch')->name('undispatch');
        Route::get('scan_status/{search}', 'SaleController@scan_status')->name('scan_status');
        Route::patch('order_update/{id}', 'SaleController@order_update')->name('order_update');
        Route::patch('notes_update/{id}', 'SaleController@notes_update')->name('notes_update');
        Route::get('deleted_sales', 'SaleController@deleted_sales')->name('deleted_sales');
        Route::patch('sales_restore/{id}', 'SaleController@sales_restore')->name('sales_restore');
        Route::patch('delivery_date/{id}', 'SaleController@delivery_date');
        Route::patch('order_edit/{id}', 'SaleController@order_edit');
        Route::post('in_transit', 'SaleController@in_transit');
        Route::post('sale_docs/{id}', 'SaleController@sale_docs');
        Route::patch('upsell/{id}', 'SaleController@upsell');

        Route::post('undispatch_sales', 'SaleController@undispatch_sales');
        Route::post('remaining_orders', 'SaleController@remaining_orders');


        // Route::get('sales_api', 'SaleController@index');

        Route::get('week_inc/{status}', 'DashboardController@week_inc');

        Route::get('week_stats/{status}', 'DashboardController@week_stats');
        Route::post('vendorPerformance', 'DashboardController@vendorPerformance');


        Route::resource('returns', 'ReturnSaleController');
        Route::patch('return_status_update/{id}', 'ReturnSaleController@return_status_update')->name('return_status_update');
        Route::patch('returns_receive/{id}', 'ReturnSaleController@returns_receive')->name('returns_receive');


        Route::post('google_sheets', 'GoogledriveController@google_sheets')->name('google_sheets');
        // Route::post('/googleUpload', 'GoogledriveController@googleUpload')->name('googleUpload');
        Route::get('get_json', 'GoogledriveController@get_json')->name('get_json');


        Route::post('auto_generate', 'AutoGenerateController@auto_generate')->name('auto_generate');
        Route::get('unique_sku/{id}', 'AutoGenerateController@unique_sku')->name('unique_sku');

        Route::get('client_products/{id}', 'ProductController@client_products')->name('client_products');
        Route::get('product_arr/{status}', 'ProductController@product_arr');
        Route::post('products_store', 'ProductController@store')->name('store');
        Route::get('product_search/{search}', 'ProductController@product_search')->name('product_search');
        Route::post('product_filter_search', 'ProductController@product_filter_search')->name('product_filter_search');
        Route::get('product_transactions/{d}', 'ProductController@product_transactions')->name('product_transactions');
        Route::get('product_table', 'ProductController@product_table')->name('product_table');
        Route::post('stock_update', 'ProductController@stock_update')->name('stock_update');
        Route::get('category_products/{category}', 'ProductController@category_products')->name('category_products');
        Route::any('low_stock', 'ProductController@low_stock')->name('low_stock');
        Route::any('product_filter', 'ProductController@product_filter');
        Route::post('product_status/{id}', 'ProductController@product_status');
        Route::post('product_delete', 'ProductController@product_delete');
        Route::get('checkProductMatch', 'ProductController@checkProductMatch');
        Route::patch('product_update/{id}', 'ProductController@product_update');
        Route::post('product_inventory', 'ProductController@product_inventory');
        Route::get('product_analysis/{id}', 'ProductController@product_analysis');
        Route::get('product_stats/{id}', 'ProductController@product_stats');
        Route::post('get_products', 'ProductUploadController@get_products');
        Route::post('productsUpload', 'ProductUploadController@productsUpload');

        Route::get('product_details/{id}', 'Warehouse\ProductWarehouseController@product_details');
        Route::resource('shipment-request', ShippingReqController::class);
        Route::resource('shipments', ShippingReqController::class);
        Route::patch('receive/{id}', [ShipmentRequestController::class, 'receive']);
        Route::post('shipment-document/{id}', [ShippingReqController::class, 'shipment_document']);
        Route::post('shipment-approve/{id}', [ShippingReqController::class, 'shipment_approve']);
        Route::patch('shipment-edit/{id}', [ShippingReqController::class, 'shipment_update']);

        Route::post('images/{id}', 'ImageController@images')->name('images');
        Route::post('product_image/{id}', 'ImageController@product_image')->name('product_image');


        Route::get('client_search/{search}', 'ClientController@client_search')->name('client_search');
        Route::get('client_orders/{id}', 'ClientController@client_orders');


        Route::get('options', 'VariantController@options')->name('options');
        Route::get('option_values', 'VariantController@option_values')->name('option_values');
        Route::get('product_variant/{id}', 'VariantController@product_variant')->name('product_variant');
        Route::get('variant_sku/{id}', 'VariantController@variant_sku')->name('variant_sku');

        Route::post('variants_values/{id}', 'SkuController@variants_values')->name('variants_values');


        // Route::get('permissions', 'RoleController@permissions')->name('permissions');
        // Route::post('getRolesPerm/{id}', 'RoleController@getRolesPerm')->name('getRolesPerm');


        Route::get('permissions/{guard}', 'RoleController@permissions');
        Route::any('getRolesPerm/{guard}/{id}', 'RoleController@getRolesPerm')->name('getRolesPerm');


        Route::patch('change_password/{id}', 'UserController@change_password')->name('change_password');
        Route::get('getUserPerm/{id}', 'UserController@getUserPerm')->name('getUserPerm');
        Route::post('permisions/{id}', 'UserController@permisions')->name('permisions');
        Route::patch('undeletedUser/{id}', 'UserController@undeletedUser')->name('undeletedUser');
        Route::delete('force_user/{id}', 'UserController@force_user')->name('force_user');
        Route::get('deletedUsers', 'UserController@deletedUsers')->name('deletedUsers');
        Route::patch('user-active/{id}', 'UserController@user_active')->name('user-active');
        Route::post('userpermisions/{id}', 'UserController@userpermisions')->name('userpermisions');

        Route::get('agents', 'UserController@agents')->name('agents');
        Route::get('zone_agents', 'UserController@zone_agents')->name('zone_agents');
        Route::post('assign_zone_agent', 'UserController@assign_zone_agent')->name('assign_zone_agent');

        Route::get('couriers', [CourierController::class, 'index'])->name('couriers');

        Route::post('featured', 'ProductSettingsController@featured')->name('featured');
        Route::post('newproduct', 'ProductSettingsController@newproduct')->name('newproduct');
        Route::post('bestsellers', 'ProductSettingsController@bestsellers')->name('bestsellers');
        Route::post('product_settings', 'ProductSettingsController@product_settings')->name('product_settings');

        Route::get('/mpesa', 'MpesaController@mpesa');
        Route::post('/mpesa-store', 'MpesaController@store');
        Route::any('/transactions', 'MpesaController@index');
        Route::post('/transactions-filter', 'MpesaController@transactions_filter');


        Route::post('/sticker_download', 'DownloadController@sticker_download');
        Route::post('/package_download', 'DownloadController@package_download')->name('package_download');
        Route::get('/pack_download/{id}', 'DownloadController@pack_download')->name('pack_download');
        Route::post('/picking_list', 'DownloadController@picking_list')->name('picking_list');
        Route::post('/export_dispatch', 'DownloadController@export_dispatch')->name('export_dispatch');
        Route::post('/dispatch_list', 'DownloadController@dispatch_list')->name('dispatch_list');
        Route::post('/dispatchList', 'DownloadController@dispatchList')->name('dispatchList');
        Route::post('/dispatch_filter', 'DownloadController@dispatch_filter')->name('dispatch_filter');
        Route::post('/get_dispatch', 'DownloadController@get_dispatch')->name('get_dispatch');
        Route::post('/remittance_download', 'DownloadController@remittance_download');
        Route::get('/sticker_pdf/{id}', 'DownloadController@sticker_pdf');
        Route::match(['get', 'post'], '/dispatch_print_list', 'DownloadController@dispatch_print_list')->name('dispatch_print_list');

        Route::post('/pickingList', 'DownloadController@pickingList')->name('pickingList');

        Route::post('/sale-report', 'DownloadController@report');

        Route::post('/download-payment-slip', 'DownloadController@download_payment_slip');

        Route::get('dashboard_count', 'DashboardController@dashboard_count');
        Route::get('vendor_performance', 'DashboardController@vendor_performance');

        // Call center
        Route::post('/agent_active/{id}', [CallController::class, 'agent_active']);
        Route::get('/realtime', [CallController::class, 'realtime']);
        Route::post('/on_call/{id}', [CallController::class, 'on_call']);
        Route::patch('/on_break/{id}', [CallController::class, 'on_break']);

        Route::resource('calls', CallController::class);
        Route::resource('missed', MissedcallController::class);

        Route::any('callcentre_performance', [AnalyticController::class, 'callcentre_performance']);

        Route::get('callcentre', [AnalyticController::class, 'callcentre']);
        Route::get('agent_chart/{status}', [AnalyticController::class, 'agent_chart']);
        Route::get('callcenter_dashboard', [AnalyticController::class, 'callcenter_dashboard']);
        Route::get('agent_data', [AnalyticController::class, 'agent_data']);
        // Route::get('user_online', [AnalyticController::class, 'user_online']);

        Route::get('user_online', [VoiceController::class, 'user_online']);
        Route::get('/queue', [VoiceController::class, 'queue']);
        Route::post('/transfer', [VoiceController::class, 'transfer']);
        Route::any('/queue_call', [VoiceController::class, 'queue_call']);
        Route::get('af_token', [VoiceController::class, 'af_token']);
        Route::any('/dequeue', [VoiceController::class, 'dequeue']);

        Route::get('/audio/{url}', [AudioController::class, 'proxy'])->name('audio.proxy');


        Route::get('/downloadAudio', [AudioController::class, 'downloadAudio'])->name('audio.download');



        Route::any('analysis/{report}', [AnalysisController::class, 'analysis']);
        // Route::any('generateRiderPerformanceReport', [AnalysisController::class, 'generateRiderPerformanceReport']);

        // Route::any('generateLeadStatusReport', [AnalysisController::class, 'generateLeadStatusReport']);
        // Route::any('generateSystemCallsTrendReport', [AnalysisController::class, 'generateSystemCallsTrendReport']);
        // Route::any('generateLeadsConversionComparisonReport', [AnalysisController::class, 'generateLeadsConversionComparisonReport']);
        // Route::any('generateFirstCallResolutionRateReport', [AnalysisController::class, 'generateFirstCallResolutionRateReport']);
        // Route::any('generateAverageCallTimeReport', [AnalysisController::class, 'generateAverageCallTimeReport']);
        // Route::any('generateCallAbandonmentRateReport', [AnalysisController::class, 'generateCallAbandonmentRateReport']);
        // Route::any('generateAgentActivityOverTimeReport', [AnalysisController::class, 'generateAgentActivityOverTimeReport']);
        // Route::any('generateTotalAmountSpentReport', [AnalysisController::class, 'generateTotalAmountSpentReport']);
        // Route::any('generateOverallSystemSummaryReport', [AnalysisController::class, 'generateOverallSystemSummaryReport']);



        Route::any('sales_chart', 'DashboardController@sales_chart')->name('sales_chart');
        Route::any('delivery_chart', 'DashboardController@delivery_chart')->name('delivery_chart');
        Route::any('return_chart', 'DashboardController@return_chart')->name('return_chart');


        Route::post('/salesUpload', 'OrderUploadController@salesUpload')->name('salesUpload');
        Route::post('/pod-upload/{id}', 'PodController@pod_upload');
        Route::get('/pods/{id}', 'PodController@pod');
        // Route::post('/sales_upload', 'UploadController@sales_upload')->name('sales_upload');

        Route::post('/get_orders', 'OrderUploadController@get_orders')->name('get_orders');


        Route::get('/table_column', 'CustomViewController@table_column')->name('table_column');

        Route::get('/table_rows/{table}', 'CustomViewController@table_rows')->name('table_rows');
        // Route::get('/custom_sale/{id}', 'CustomViewController@custom_sale')->name('custom_sale');

        Route::post('/custom_view', 'CustomViewController@custom_view')->name('custom_view');


        Route::post('/package_list', 'PackageController@package_list')->name('package_list');



        Route::get('myServices/{id}', 'Seller\SellerController@myServices')->name('myServices');
        Route::patch('portal_status/{id}', 'Seller\SellerController@portal_status');
        Route::patch('seller_password/{id}', 'Seller\SellerController@seller_password');
        Route::get('seller/getVendorPerm/{id}', 'Seller\SellerController@getVendorPerm')->name('getVendorPerm');
        Route::get('seller_search/{search}', 'Seller\SellerController@seller_search')->name('seller_search');
        Route::get('seller/userpermisions/{search}', 'Seller\SellerController@seller_search')->name('seller_search');
        Route::post('seller/userpermisions/{id}', 'Seller\SellerController@userpermisions')->name('sellerpermisions');


        Route::any('get_sellers', 'SellerUploadController@get_sellers')->name('get_sellers');
        Route::any('sellersUpload', 'SellerUploadController@sellersUpload')->name('sellersUpload');


        // Dashboard

        Route::get('/notifications', 'NotificationController@notifications')->name('notifications');
        Route::get('/read_noty', 'NotificationController@read_noty')->name('read_noty');


        Route::any('/inv_status/{id}', 'InvoiceController@inv_status')->name('inv_status');
        Route::any('/main_inv/{id}', 'InvoiceController@main_inv')->name('main_inv');
        Route::any('/download_pdf/{id}', 'InvoiceController@download_pdf')->name('download_pdf');



        Route::post('google_service', 'SettingController@google_service')->name('google_service');
        Route::post('api_connect/{site}', 'SettingController@api_connect')->name('api_connect');
        Route::get('api_exist/{id}', 'SettingController@api_exist')->name('api_exist');
        Route::get('api_check', 'SettingController@api_check')->name('api_check');

        Route::get('locate', 'SettingController@locate')->name('locate');


        Route::any('shopify_orders', 'ShopifyController@shopify_orders')->name('shopify_orders');
        Route::any('shopify_products', 'ShopifyController@shopify_products')->name('shopify_products');
        Route::post('shopify_pro_import', 'ShopifyController@shopify_pro_import')->name('shopify_pro_import');
        Route::post('shopify_import', 'ShopifyController@shopify_import')->name('shopify_import');

        Route::post('woocommerce_orders', 'Api\woocommerceController@woocommerce_orders')->name('woocommerce_orders');
        Route::post('woocommerce_import', 'Api\woocommerceController@woocommerce_import')->name('woocommerce_import');

        Route::any('woocommerce_products', 'Api\woocommerceController@woocommerce_products')->name('woocommerce_products');
        Route::post('import_products', 'Api\woocommerceController@import_products')->name('import_products');



        Route::get('auth_app', 'Api\woocommerceController@auth_app');


        Route::get('maps', function () {
            return view('maps.index');
        });




        Route::get('woocommerce', function () {
            $api_ = DB::table('api_test')->first();

            return unserialize($api_->item);
        });

        Route::any('woo_api', 'Api\woocommerceController@woocommerce')->name('woocommerce');
        // Route::get('woo_api','SubscriptionController@executeAgreement');



        /** Warehouse **/

        Route::get('/warehouse', 'Warehouse\WarehouseController@warehouse');
        Route::resource('warehouses', 'Warehouse\WarehouseController');
        Route::resource('sellers', 'Seller\SellerController');
        Route::resource('areas', 'Warehouse\AreaController');
        Route::resource('rows', 'Warehouse\RowController');
        Route::resource('bays', 'Warehouse\BayController');
        Route::resource('levels', 'Warehouse\LevelController');
        Route::resource('bins', 'Warehouse\BinController');
        Route::resource('warehouse_pickup', 'Warehouse\WarehousepickupController');
        Route::resource('products', 'ProductController');
        Route::resource('productsbin', 'Warehouse\ProductBinController');
        Route::resource('asn', 'Warehouse\AsnController');

        Route::get('/warehouse_bin/{id}', 'Warehouse\BinController@warehouse_bin');
        Route::post('/bins_check', 'Warehouse\BinController@bins_check');
        Route::get('/product_bins/{id}', 'Warehouse\BinController@product_bins');
        Route::get('/transfer_bin', 'Warehouse\BinController@transfer_bin');




        Route::get('/reports/daily-product-status', [WarehouseReportController::class, 'dailyProductStatusReport']);
        Route::get('/reports/product-sales', [WarehouseReportController::class, 'productSalesReport']);
        Route::get('/reports/monthly-product-movement', [WarehouseReportController::class, 'monthlyProductMovementReport']);
        Route::get('/reports/product-availability', [WarehouseReportController::class, 'productAvailabilityReport']);
        Route::get('/reports/product-performance', [WarehouseReportController::class, 'productPerformanceReport']);



        Route::get('/transfer-data', [WmsController::class, 'index']);


        Route::get('/pickup_note/{id}', 'Warehouse\ProductBinController@pickup_note');
        Route::get('/product_sticker/{id}', 'Warehouse\ProductBinController@product_sticker');
        Route::get('/vendor_product/{id}', 'Seller\SellerController@vendor_product');
        Route::patch('/shopify_email/{id}', 'Seller\SellerController@shopify_email');

        Route::post('/product_quantity', 'Warehouse\ProductBinController@product_quantity');
        Route::post('/stock_update', 'Warehouse\ProductBinController@stock_update');

        Route::get('/reduce_qty', 'Warehouse\ProductWarehouseController@reduce_qty');

        // Route::get('/lowStock', 'Warehouse\DashboardController@lowStock');
        // Route::get('/stock_count', 'Warehouse\DashboardController@stock_count');
        // Route::get('/warehouse_count', 'Warehouse\DashboardController@warehouse_count');
        // Route::get('/bins_count', 'Warehouse\DashboardController@bins_count');
        // Route::get('/commited_count', 'Warehouse\DashboardController@commited_count');
        // Route::get('/available_count', 'Warehouse\DashboardController@available_count');
        // Route::get('/dispatch_count', 'Warehouse\DashboardController@dispatch_count');
        // Route::get('/product_count', 'Warehouse\DashboardController@product_count');
        // Route::get('/onhand_count', 'Warehouse\DashboardController@onhand_count');

        Route::get('/dashboard_data', 'Warehouse\DashboardController@dashboard_data');



        Route::get('/sticker/{id}', 'Warehouse\StickerController@sticker');

        /** Warehouse **/


        /** Vendor Routes **/

        /** Vendor Routes **/


        Route::group(['namespace' => 'Branch', 'prefix' => 'branch'], function () {
            Route::get('/', 'HomeController@index')->name('branch.dashboard');

            Route::resource('branches', 'BranchController');
            Route::patch('branch_update/{id}', 'BranchController@branch_update')->name('branch_update');
            // Login
            Route::get('login', 'Auth\LoginController@showLoginForm')->name('branch.login');
            Route::post('login', 'Auth\LoginController@login');
            Route::post('logout', 'Auth\LoginController@logout')->name('branch.logout');

            // Register
            Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('branch.register');
            Route::post('register', 'Auth\RegisterController@register');

            // Passwords
            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('branch.password.email');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');
            Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('branch.password.request');
            Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('branch.password.reset');

            // Must verify email
            Route::get('email/resend', 'Auth\VerificationController@resend')->name('branch.verification.resend');
            Route::get('email/verify', 'Auth\VerificationController@show')->name('branch.verification.notice');
            Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('branch.verification.verify');
        });
        Route::get('agents_dashboard', 'Rider\AnalyticController@agents_dashboard')->name('agents_dashboard');
        Route::get('agent_dashboard_chart/{status}', 'Rider\AnalyticController@agent_chart')->name('agent_dashboard_chart');
        Route::get('agents_charts', 'Rider\AnalyticController@agents_charts')->name('agents_charts');
        Route::any('agents_dashboard_performance', 'Rider\AnalyticController@agents_performance')->name('agents_performance');

        Route::delete('riders/{id}', 'Rider\RiderController@destroy');


        Route::group(['namespace' => 'Rider', 'prefix' => 'rider'], function () {
            Route::get('/', 'HomeController@index')->name('rider.dashboard');

            Route::resource('riders', 'RiderController');
            Route::patch('rider_update/{id}', 'RiderController@rider_update')->name('rider_update');
            Route::patch('rider_password/{id}', 'RiderController@rider_password');
            Route::patch('mobile_account/{id}', 'RiderController@mobile_account');


            // Login
            Route::get('login', 'Auth\LoginController@showLoginForm')->name('rider.login');
            Route::post('login', 'Auth\LoginController@login');
            Route::post('logout', 'Auth\LoginController@logout')->name('rider.logout');

            // Register
            Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('rider.register');
            Route::post('register', 'Auth\RegisterController@register');

            // Passwords
            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('rider.password.email');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');
            Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('rider.password.request');
            Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('rider.password.reset');

            // Must verify email
            Route::get('email/resend', 'Auth\VerificationController@resend')->name('rider.verification.resend');
            Route::get('email/verify', 'Auth\VerificationController@show')->name('rider.verification.notice');
            Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('rider.verification.verify');
        });



        Route::any('/url_register', 'MpesaController@url_register')->name('url_register');

        Route::get('transaction_search/{search}', 'MpesaController@transaction_search');
        Route::get('transaction_order/{code}', 'MpesaController@transaction_order');


        // Route::get('upgrade', 'Admin\SubscriptionController@upgrade')->name('upgrade');

    });
});
