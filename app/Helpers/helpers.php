<?php

use App\Models\Ou;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('settings')) {
    function settings()
    {
        $settings = cache()->remember('settings', 24 * 60, function () {
            $ou_id = logged_user()->ou_id;
            return Ou::find($ou_id);
            // return \app\Models\Ou::firstOrFail();
        });

        return $settings;
    }
}

if (!function_exists('format_currency')) {
    function format_currency($value, $format = true)
    {
        if (!$format) {
            return $value;
        }
        $settings = settings();
        $currency_code = ($settings) ? $settings->currency_code : '';
        $formatted_value = $currency_code . ' ' . number_format((int) $value);
        return $formatted_value;
    }
}

if (!function_exists('make_reference_id')) {
    function make_reference_id($prefix, $number)
    {
        $padded_text = $prefix . '-' . str_pad($number, 5, 0, STR_PAD_LEFT);

        return $padded_text;
    }
}

if (!function_exists('logged_user')) {
    function logged_user()
    {
        if (Auth::check()) {
            $user =  Auth::user();
        } elseif (Auth::guard('seller')->check()) {
            $user =  Auth::guard('seller')->user();
        } else {
            $user = User::first();
        }
        return $user;
    }
}



if (!function_exists('tenant')) {
    function tenant($id)
    {
        return 'boxleo';
    }
}