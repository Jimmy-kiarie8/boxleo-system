<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'site', 'africas_talkig_username', 'africas_talkig_api_key', 'twilio_sid', 'twilio_auth_token', 'twilio_number', 'google_folder_id', 'file_path', 'woocommerce_url', 'woocommerce_consumer_secret', 'woocommerce_consumer_key', 'shopify_key', 'shopify_url', 'shopify_secret', 'sms_default', 'celcomafrica_short_code', 'celcomafrica_partner_id', 'celcomafrica_api_key'];


    public function get_json()
    {
        $file = Setting::where('file_path', '!=', 'null')->first('file_path');

        if ($file) {
            $path = $file->file_path;
            return  json_decode(file_get_contents($path), true);
        } else {
            return '';
        }
    }
}
