<?php


Route::group(['namespace' => 'Branch'], function() {
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
    Route::get('email/resend','Auth\VerificationController@resend')->name('branch.verification.resend');
    Route::get('email/verify','Auth\VerificationController@show')->name('branch.verification.notice');
    Route::get('email/verify/{id}','Auth\VerificationController@verify')->name('branch.verification.verify');

});
