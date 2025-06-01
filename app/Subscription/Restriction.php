<?php

namespace App\Subscription;

use App\Tenant;

class Restriction
{
    public function portals()
    {
        $tenant = new Tenant();
        return $tenant->limit('sellers');
    }
    public function users()
    {
        $tenant = new Tenant();
        return $tenant->limit('users');        
    }
    public function orders()
    {
        // return false;
        $tenant = new Tenant();
        return $tenant->limit('sales');        
    }
    public function warehouses()
    {
        $tenant = new Tenant();
        return $tenant->limit('warehouses');        
    }
    public function shopify()
    {
        $tenant = new Tenant();
        return $tenant->limit('shopifies');          
    }
    public function woocommerce()
    {
        $tenant = new Tenant();
        return $tenant->limit('woocommerces');           
    }
    public function api()
    {
        
    }
    public function automations()
    {
        $tenant = new Tenant();
        return $tenant->limit('automations');        
    }
    public function sms()
    {
        
    }
    public function tracking()
    {
        
    }
    public function emails()
    {
        
    }
}
