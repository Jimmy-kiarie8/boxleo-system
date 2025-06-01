<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\WebhookServer\WebhookCall;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'condition',
        'model',
        'vendor_id',
        'active',
        'auth_token'
    ];


    public function create($data)
    {
        $webhook_exist = Webhook::where('url', $data['url'])->exists();

        if ($webhook_exist) {
            abort(422, 'Webhook exists');
        }
        $webhook = new Webhook;
        $webhook->url = $data['url'];
        $webhook->condition = $data['condition'];
        $webhook->model = $data['model'];
        $webhook->vendor_id = $data['vendor_id'];
        $webhook->auth_token = $data['auth_token'] ?? null;
        // $webhook->secret_key = $data['secret_key'];
        $webhook->save();
        return $webhook;
    }


    public function webhook_create($data, $id)
    {
        $webhook_exist = Webhook::where('url', $data['url'])->exists();

        if ($webhook_exist) {
            abort(422, 'Webhook exists');
        }
        $webhook = new Webhook;
        $webhook->url = (array_key_exists('url', $data)) ?  $data['url'] : null;
        $webhook->condition = (array_key_exists('condition', $data)) ?  $data['condition'] : null;
        $webhook->model = (array_key_exists('model', $data)) ?  $data['model'] : null;
        $webhook->auth_token = (array_key_exists('auth_token', $data)) ?  $data['auth_token'] : null;
        $webhook->vendor_id = $id;
        // $webhook->secret_key = $data['secret_key'];
        $webhook->save();
        return $webhook;
    }


    public function webhook($id, $data)
    {
        $webhook = Webhook::where('vendor_id', $id)->first();
        WebhookCall::create()
            ->url($webhook->url)
            ->payload($data)
            // ->useSecret('sign-using-this-secret')
            ->dispatch();
    }
}
