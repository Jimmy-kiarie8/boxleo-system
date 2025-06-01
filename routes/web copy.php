<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;


Route::get('list_plans', [TestController::class, 'list_plans']);

Route::get('domain-redirect', [HomeController::class, 'domain'])->name('domain-redirect');
Route::view('/domain', 'admin/errors/domain-redirect');


Route::resource('currencies', 'CurrencyController');
Route::resource('plans', 'Admin\PlanController');

Route::get('upgrade', 'Admin\SubscriptionController@upgrade')->name('upgrade');
Route::post('renew', 'Admin\SubscriptionController@renew')->name('renew');

Route::get('thankyou/{domain}', 'Admin\SubscriptionController@thankyou');
Route::get('thankyou-payment/{domain}', 'Admin\SubscriptionController@thankyou_payment');


Route::get('rates', 'CurrencyController@rates')->name('rates');

Route::get('encrypt_domain/{domain}', 'SetupController@encrypt_domain');


Route::view('documentations', 'admin.docs.index');

// Route::get('/settings', 'Admin\AdminController@index');

Route::view('accounts', 'admin.signup.index');
// Route::view('accounts', 'admin.index');
Route::view('welcome', 'admin.welcome');
Route::resource('account', 'Admin\SubscriptionController');

Route::post('validate/{step}', 'Admin\SubscriptionController@validate_account');



Route::get('/webhook_index', 'SubscriptionController@webhook_index')->name('webhook_index');

Route::get('company_details', 'SetupController@company_details');


// Route::post('/account', 'SetupController@account')->name('account');


Route::any('woo_api', 'Api\woocommerceController@woocommerce')->name('woocommerce');
// Auth::routes();

Route::post('/mail', 'MailController@contact')->name('contact');

Route::get('/addon/{domain}', 'AddonController@addon');
Route::resource('/addons', 'AddonController');

Route::view('/sign-up', 'admin.signup.index');
Route::post('signup', 'LeadController@store')->name('signup');


Route::get('/', 'Admin\AdminController@home');
Route::view('/about', 'admin.landing.about');
Route::view('/inventory-management-software', 'admin.landing.ims');
Route::view('/warehouse-management-software', 'admin.landing.wms');
Route::view('/contact', 'admin.landing.contact');
Route::view('/privacy', 'admin.landing.privacy');
Route::get('/pricing', 'Admin\AdminController@pricing');
Route::view('/order-fulfillment-software', 'admin.landing.order-fulfillment-software');
Route::view('/customer-service-system', 'admin.landing.customer-service-system');

Route::view('/shopify', 'admin.landing.integrations.shopify');
Route::view('/woocommerce', 'admin.landing.integrations.woocommerce');
Route::view('/delivery-app', 'admin.landing.delivery-app');
Route::view('/faqs', 'admin.landing.faqs');


Route::view('/sms', 'admin.landing.integrations.sms');



Route::resource('countries', 'OuController');

Route::post('/zone_setup', 'SetupController@zones');
