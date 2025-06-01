<?php

namespace App\Setting;

use Illuminate\Http\Request;

class Setting
{
    
    /**
     * {@inheritdoc}
     */
    public function locate(Request $request)
    {
        $location = geoip($ip = $request->ip());
        return $location->timezone;
    }

}
