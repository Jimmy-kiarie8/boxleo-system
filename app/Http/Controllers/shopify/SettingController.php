<?php

namespace App\Http\Controllers\shopify;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopify;
use App\Shopify\Webhook;

class SettingController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $shop = Auth::user();

        $themes = $shop->api()->rest('GET', '/admin/themes.json');

        // dd($shopApi);
        $active_theme_id = null;
        foreach ($themes['body']->container['themes'] as $theme) {
            // dd($theme['role']);
            if ($theme['role'] == 'main') {
                $active_theme_id = $theme['id'];
            }
        }

        // dd($active_theme_id);
        $snippet = 'My code';
        $array = array('asset' => array('key' => 'snippets/jimlab-lux.liquid', 'value' => $snippet));

        $shop->api()->rest('PUT', 'admin/themes/' . $active_theme_id . '/assets.json', $array);


        Setting::updateOrCreate(
            ['shop_id' => $shop->name],
            ['active' => true]
        );
        return response()->json(['message' => 'Theme setup successifully complete'], 200);
    }


    public function webhook($id)
    {
        $webhook = new Webhook;
        $data = $webhook->webhook($id);
        return response()->json(['webhook' => $data]);

    }


    public function webhook_deactivate($id)
    {

        $webhook = new Webhook;
        $webhook->webhook_deactivate($id);
    }


    public function get_webhooks($id)
    {
        $webhook = new Webhook;
        $webhook->get_webhooks($id);
    }
}
