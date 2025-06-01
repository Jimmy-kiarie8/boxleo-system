<?php

Route::group(['namespace' => 'Rider'], function() {
    Route::get('/', 'HomeController@index')->name('rider.dashboard');

    Route::resource('riders', 'RiderController');
    Route::patch('rider_update/{id}', 'RiderController@rider_update')->name('rider_update');

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
    Route::get('email/resend','Auth\VerificationController@resend')->name('rider.verification.resend');
    Route::get('email/verify','Auth\VerificationController@show')->name('rider.verification.notice');
    Route::get('email/verify/{id}','Auth\VerificationController@verify')->name('rider.verification.verify');

});
