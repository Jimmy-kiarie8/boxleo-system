<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'portals', 'users', 'tracking', 'warehouses', 'ous', 'shopify_integrations', 'wordpress_integrations', 'api_integrations', 'automations', 'sms', 'emails', 'lables'];
}
