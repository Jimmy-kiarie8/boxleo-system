<?php

namespace App\Http\Controllers;

use App\Models\Mpesa;
use App\Models\OrderGeocoder;
use App\Models\Setting;
use App\Models\Shopify;
use App\Models\Woocommerce;
use App\Setting\Setting as SettingSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function google_service(Request $request)
    {
        // $path = $request->file('file')->storeAs(
        //     'avatars', Auth::id()
        // );

        //         return $request->file->getClientOriginalExtension();
        //         Storage::putFile('photos', new File($request->file('file')));
        // return;
        //         // dd($path);
        //         $path = $request->file('file')->store('avatars');

        //         dd($path);

        $company_id = Auth::user()->company_id;

        if ($request->hasFile('file')) {
            $file = Setting::where('company_id', $company_id)->first();
            if (!$file) {
                $file = new Setting();
            } else {
                // dd(1);
                $file = Setting::where('company_id', $company_id)->first();

                $file_path = $file->file_path;
                // $file_path = $file_arr[1];
                // dd($file_path);
                // dd(Storage::disk(env('STORAGE_DISK'))->exists($file_path));
                if (Storage::disk(env('STORAGE_DISK'))->exists($file_path)) {
                    $file_path = $file->file_path;
                    Storage::disk(env('STORAGE_DISK'))->delete($file_path);
                }
            }

            $file_up = $request->file;
            $company_name = Auth::user()->company->name;
            return $filename = Storage::disk(env('STORAGE_DISK'))->put($company_name . '/google_service', $file_up, 'public');
            // $filename = Storage::disk(env('STORAGE_DISK'))->put($company_name . '/google_service', $file_up, 'public');
            // return $filename =  Storage::putFileAs('photos', new File($file_up), 'p_hoto.png');
            // $filename = Storage::putFile($company_name . '/google_service', $file_up, new File($file_up));


            // return $filename;
            // $fileArr = explode('/', $filename);
            // $file_name = $fileArr[2];


            // return $uploaded_file = env('STORAGE_PATH') . $filename;

            dd($filename);
            $uploaded_file =  $filename;
            $file->file_path = '/storage/' . $uploaded_file;
            $file->company_id = $company_id;
            $file->save();
            return $file;
        }
        return 'error';
    }

    public function api_connect(Request $request, $site)
    {




        // return $request->all();
        // return $wp_url;
        // return array_key_exists('scheme', $wp_url);
        if ($site == 'shopify') {

            $request->validate([
                'shopify_secret' => 'required',
                'vendor_id' => 'required|unique:shopifies',
                'shopify_url' => 'required|unique:shopifies'
            ]);

            $this->authorize('create', Shopify::class);
            $data = $request->all();
            $shopify_url = $request->shopify_url . '.myshopify.com';
            $data['shopify_url'] = $shopify_url;
            $data['shopify_name'] = $request->shopify_url;

            $shop_exists = Shopify::where('shopify_name', $request->shopify_url)->exists();
            if ($shop_exists) {
                abort(422, 'Shop already connected');
            }
            $shopify_ = new Shopify();
            return $shopify_->connect($data);
        } elseif ($site == 'woocommerce') {
            $this->authorize('create', Woocommerce::class);
            $woocommerce_url = parse_url($request->woocommerce_url);
            $data = $request->all();
            $data['woocommerce_url'] = $woocommerce_url;
            $data['woocommerce_name'] = $request->woocommerce_url;

            $shop_exists = Woocommerce::where('woocommerce_name', $request->woocommerce_url)->exists();
            if ($shop_exists) {
                abort(422, 'Shop already connected');
            }

            $woocommerce_ = new Woocommerce();
            return $woocommerce_->connect($data);
        }
        /* $wp_url = [];
        if ($request->woocommerce_url) {
            $wp_url = parse_url($request->woocommerce_url);
        } */ elseif ($site == "google_sheets") {

            // Storage::disk('tenant')->put('google/' . tenant('id') . '.json', ($request->file));
            // $path = 'storage/google/' . tenant('id') .'.json';
            // // if (File::exists($image->image)) {
            // //     $image_path = $image->image;
            // //     File::delete($image_path);
            // // }

            // $settings = Setting::first();
            // if (!$settings) {
            //     $settings = new Setting;
            // }


            // // $settings = (Setting::where('file_path', '!=', 'null')->exists()) ? Setting::where('file_path', '!=', 'null')->first() : new Setting;
            //  $settings->file_path = $path;
            // $settings->save();
            // return $settings;
        } elseif ($site == "twilio" || $site == "africas_talking" || $site == "celcomafrica") {
            $settings = Setting::first();
            if (!$settings) {
                $sms_default = $site;
                return Setting::create(
                    [
                        'africas_talkig_username' => $request->africas_talkig_username,
                        'africas_talkig_api_key' => $request->africas_talkig_api_key,
                        'twilio_sid' => $request->twilio_sid,
                        'twilio_auth_token' => $request->twilio_auth_token,
                        'twilio_number' => $request->twilio_number,
                        'celcomafrica_partner_id' => $request->celcomafrica_partner_id,
                        'celcomafrica_api_key' => $request->celcomafrica_api_key,
                        'celcomafrica_short_code' => $request->celcomafrica_short_code,
                        'sms_default' => $sms_default
                    ]
                );
            } else {

                if ($request->sms_default) {
                    $sms_default = $site;
                } else {
                    $sms_default = $settings->sms_default;
                }
                return $settings->update(
                    [
                        'africas_talkig_username' => $request->africas_talkig_username,
                        'africas_talkig_api_key' => $request->africas_talkig_api_key,
                        'twilio_sid' => $request->twilio_sid,
                        'twilio_auth_token' => $request->twilio_auth_token,
                        'twilio_number' => $request->twilio_number,
                        'celcomafrica_partner_id' => $request->celcomafrica_partner_id,
                        'celcomafrica_api_key' => $request->celcomafrica_api_key,
                        'celcomafrica_short_code' => $request->celcomafrica_short_code,
                        'sms_default' => $sms_default
                    ]
                );
            }
        }
        // return ($api);
        // return $request->all();
        // $url = $this->remove_http($request->shopify_url);
        // $url = $shopify_url;
        $vendor_id = $request->vendor_id;
        // $shopify_url = 'https://' . $request->shopify_key . ':' . $request->shopify_secret . '@' . $url . '/admin/api/';

        // https://3ff66f2c922c7c716159ea4b01719833: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@speedballcourier.myshopify.com/admin/api/2021-01/orders.json
        return Setting::updateOrCreate(
            [
                'vendor_id' => $vendor_id
            ],
            [
                'africas_talkig_username' => $request->africas_talkig_username,
                'africas_talkig_api_key' => $request->africas_talkig_api_key,
                'twilio_sid' => $request->twilio_sid,
                'twilio_auth_token' => $request->twilio_auth_token,
                'twilio_number' => $request->twilio_number,
                'google_client_id' => $request->google_client_id,
                'google_client_secret' => $request->google_client_secret,
                'google_refresh_token' => $request->google_refresh_token,
                'google_folder_id' => $request->google_folder_id,
                'celcomafrica_partner_id' => $request->celcomafrica_partner_id,
                'celcomafrica_api_key' => $request->celcomafrica_api_key,
                'celcomafrica_short_code' => $request->celcomafrica_short_code,
                // 'file_path' => $path,
                // 'file_path' => $request->file_path,
                // 'woocommerce' => $request->woocommerce,

                // 'woocommerce_url' => (array_key_exists('scheme', $wp_url)) ? $request->woocommerce_url : 'https://' . $request->woocommerce_url,
                // 'woocommerce_consumer_secret' => $request->woocommerce_consumer_secret,
                // 'woocommerce_consumer_key' => $request->woocommerce_consumer_key,
                // 'shopify_key' => $request->shopify_key,
                // 'shopify_secret' => $request->shopify_secret,
                // 'shopify_redirect' => $request->shopify_redirect,
                // 'shopify_access' => $request->shopify_access,
                // 'shopify_url' => $shopify_url,
                // 'site' => $site
            ]
        );
    }

    public function remove_http($url)
    {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

    public function api_exist($id)
    {
        // return $settings = Setting::first();
        // return $settings = Setting::all();

        $set = new Setting();
        if ($id == 0 && Setting::first()) {
            $settings = Setting::first();

            if ($settings->sms_default == 'twilio') {
            } elseif ($settings->sms_default == 'twilio') {
            }


            $set = new Setting();
            $settings->file = $set->get_json();
            if ($settings) {
                return $settings;
            } else {
                return;
            }
        }
        $shopify = Shopify::where('vendor_id', $id)->first();
        // $company_id = Auth::user()->company_id;
        $settings = Setting::where('vendor_id', $id)->first();

        if ($shopify) {
            if (!$settings) {
                $settings = new Setting();
            }
            $shopify_url = explode('.', $shopify->shopify_url);
            $settings->shopify_key = $shopify->shopify_key;
            $settings->shopify_secret = $shopify->shopify_secret;
            $settings->shopify_url = $shopify_url[0];
            $settings->vendor_id = (int) $id;
        }

        if ($settings) {
            $settings->file = '';
            return $settings;
        } else {
            $settings = new Setting();
            $settings->vendor_id = (int) $id;
            return $settings;
        }
    }

    public function api_check()
    {
        // return Shopify::exists();
        // return $settings = Setting::first();
        // return $settings = Setting::all();
        // if ($api == 'shopify') {
        $apis = Setting::orWhereNotNull('woocommerce_consumer_secret')->orWhereNotNull('file_path')->get();
        $data = ['shopify' => false, 'woocommerce' => false, 'file_path' => false, 'mpesa' => false];
        foreach ($apis as $api) {
            if ($api->woocommerce_consumer_secret) {
                $data['woocommerce'] = true;
            }
            if ($api->file_path) {
                $data['google_sheets'] = true;
            }
        }
        if (Shopify::exists()) {
            $data['shopify'] = true;
        }
        if (Mpesa::exists()) {
            $data['mpesa'] = true;
        }
        return $data;
        // }
    }

    public function locate(Request $request)
    {
        $settings = new OrderGeocoder();
        return $settings->geo('');
    }

    public function backup()
    {
        Artisan::queue('tenants:run', [
            'commandname' => 'backup:database', '--tenants' => tenant('id')
        ]);
    }

    public function dbbackups()
    {

        $tenant = tenant('id');
        // $database = config('tenancy.database.prefix') . $tenant;
        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));
        foreach ($contents as $key => $value) {
            $arr = explode('-', $value['name']);
            if ($arr[0] != $tenant) {
                unset($contents[$key]);
            }
        }

        // $collection = $contents->reject(function ($element) use ($tenant) {
        //     // dd($arr[0]);
        //     // return $tenant == $arr[0];
        //     return mb_strpos($arr[0], $tenant) === false;
        // });
        //return $contents->where('type', '=', 'dir'); // directories
        $files = $contents->where('type', '=', 'file')->sortByDesc('timestamp'); // files
        if (count($files) > 0) {
            // $files = Storage::disk('google')->allFiles();

            $i = 0;
            foreach ($files as $key => $file) {
                // dd( $file['filename']);

                $filename[$i]['filename'] = $file['name'];
                $filename[$i]['path'] = $file['path'];
                $filename[$i]['Backup Date'] = Carbon::createFromTimestamp($file['timestamp'])->diffForHumans();
                $i++;
            }
            return $filename;
        }
        return [];
    }
    public function dbrestore(Request $request)
    {

        $tenant = tenant('id');
        $database = config('tenancy.database.prefix') . $tenant;
        // return $request->all();
        $backupFilename = $request->path;
        // $path = 'www';

        // dd($path);
        // $backupFilename = $this->option('path');

        $getBackupFile = Storage::disk('google')->get($backupFilename);

        // $backupFilename  = explode("/", $backupFilename);
        // $backupFilename = $backupFilename . '.sql.gz';

        // dd($getBackupFile);

        Storage::disk('tenant')->put($backupFilename, $getBackupFile);

        $mime = Storage::disk('tenant')->mimeType($backupFilename);
        // $mime = $filename[0]['mime'];
        // dd($mime);

        if ($mime == "application/x-gzip" || $mime == "application/gzip") {

            $command = "zcat " . public_path() . "/storage/" . $backupFilename . " | mysql --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . $database . "";
        } elseif ($mime == "text/plain") {

            $command = "mysql --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . $database . " < " . public_path() . "/storage/" . $backupFilename;
        } else {

            abort(422, 'Something went wrong');
            $this->error("File is not gzip or plain text");
            Storage::disk('tenant')->delete($backupFilename);
            return false;
        }

        $returnVar  = NULL;
        $output     = NULL;
        exec($command, $output, $returnVar);

        Storage::disk('tenant')->delete($backupFilename);

        if (!$returnVar) {
            return 'Database restored';
        } else {
            abort(422, 'Something went wrong');
        }

        // Artisan::call('tenants:run', [
        //     'commandname' => 'backup:restore',
        //     '--tenants' => tenant('id'),
        //     '--option' => ['--path='=>'']
        // ]);

    }
}
