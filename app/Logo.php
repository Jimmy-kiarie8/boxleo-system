<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class Logo
{
    public function logo()
    {
        $company_logo = Auth::user()->company->logo;
        $url = env('APP_URL')  . '/'  . $company_logo;
        // dd($url);
        // $url = 'http://' . tenant('id') . env('CENTRAL_DOMAIN') . '/' .  $company_logo;
        return 'data:image/png;base64,' . base64_encode(file_get_contents($url));
    }
}
