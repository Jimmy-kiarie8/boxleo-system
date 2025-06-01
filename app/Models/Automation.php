<?php

namespace App\Models;

use App\Http\Controllers\CustomViewController;
use App\Mail\AutomaticMail;
use App\Models\settings\Mailable;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Automation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'conditions', 'sms', 'actions', 'model', 'execute_when', 'trigger_count', 'action_time', 'mailable_id', 'selected_column', 'trigger_when', 'ou_id'];

    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = serialize($value);
    }
    public function getConditionsAttribute($value)
    {
        return unserialize($value);
    }
    public function setActionsAttribute($value)
    {
        $this->attributes['actions'] = serialize($value);
    }
    public function getActionsAttribute($value)
    {
        return unserialize($value);
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function setSelectedColumnAttribute($value)
    {
        $this->attributes['selected_column'] = serialize($value);
    }
    public function getSelectedColumnAttribute($value)
    {
        return unserialize($value);
    }

    public function sms($message)
    {
    }

    public function send_mail($message)
    {
        // Mail
    }

    public function getModelName($table)
    {
        $model = Str::studly(Str::singular($table));
        return  $table_model = "App\Models" . "\\" . $model;
    }


    public function auto_mail($id, $send_id, $action, $ou_id)
    {
        if ($action == 'mail') {
            $item = Mailable::find($send_id);
            $content = $item->template;
            $model = $item->model;
            $recipients = $item->recipient;
        } elseif ($action == 'sms') {
            $item = SmsTemplate::where('ou_id', $ou_id)->where('id', $send_id)->first();
            if (!$item) {
                return;
            }
            $content = $item->sms;
            $model = $item->model;
            $recipients = $item->recipient;
        } else {
            return;
        }
        $auto = new Automation();
        $table_model = $auto->getModelName($model);

        // $table = DB::table($mail->model)->with(['clients'])->first();
        $table = new $table_model;
        $table = $table->where('id', $id)->get();
        // $table = $table->take(1)->get();

        $transform_sales = new Sale();

        $table = $transform_sales->transform_sales($table)[0];

        //    dd($table = $table[0]);
        // dd($table);
        $recipient_arr = [];
        $recipient_phone = [];
        foreach ($recipients as $recipient) {

            if ($auto->getModelName($model) == 'App\Models\Sale') {
                if ($recipient == 'You') {
                    $user = User::find($item->user_id);
                    $recipient_arr[] = $user->email;
                    $recipient_phone[] = $user->phone;
                } elseif ($recipient == 'Client') {
                    $client = Client::find($table->client_id);
                    $recipient_arr[] = $client->email;
                    $recipient_phone[] = $client->phone;
                } elseif ($recipient == 'Vendor') {
                    $seller = Seller::find($table->seller_id);
                    $recipient_arr[] = $seller->email;
                    $recipient_phone[] = $seller->phone;
                } elseif ($recipient == 'Created by') {
                    $user = User::find($table->user_id);
                    $recipient_arr[] = $user->email;
                    $recipient_phone[] = $user->phone;
                } elseif (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    $recipient_arr[] = $recipient;
                } elseif (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    $recipient_phone[] = $recipient;
                } elseif ($recipient == 'Owner') {
                }
            } else {
                if ($recipient == 'You') {
                    $user = User::find($item->user_id);
                    $recipient_arr[] = $user->email;
                    $recipient_phone[] = $user->phone;
                } elseif ($recipient == 'User') {
                    $recipient_arr[] = $table->email;
                    $recipient_phone[] = $table->phone;
                } elseif ($recipient == 'Created by') {
                    $user = User::find($table->user_id);
                    $recipient_arr[] = $user->email;
                    $recipient_phone[] = $user->phone;
                } elseif (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    $recipient_arr[] = $recipient;
                } elseif ($recipient == 'Owner') {
                }
            }
        }

        $recipient_arr = array_values(array_filter($recipient_arr));
        $recipient_phone = array_values(array_filter($recipient_phone));
        $table_rows = new CustomViewController;
        $table_rows = $table_rows->table_rows($model);

        // return $table['client_name'];

        //  $table = json_decode(json_encode($table), true);
        foreach ($table_rows as $rows) {
            // dd($rows);
            // return $table[$rows->Field];

            // var_dump('{%' . $rows->Field . '%}');

            // dd($table);
            $content = str_replace('{%' . $rows->Field . '%}', $table->{$rows->Field}, $content);
        }
        if ($action == 'mail') {
            Mail::send(new AutomaticMail($content, $item->subject, $recipient_arr));
        } elseif ($action == 'sms') {
            $sms = new Sms;
            $seller_ = Seller::where('id', $table->seller_id)->setEagerLoads([])->first('send_sms');
            if ($seller_->send_sms) {
                foreach ($recipient_phone as $key => $phone) {
                    // if ($ou_id == 3) {
                    //     $sms->africas_talking($phone, $content);
                    // } else {
                    Log::info('Sending SMS', ['phone' => $phone, 'content' => $content, 'ou_id' => $ou_id]);
                    $sms->sms($phone, $content, $ou_id);
                    // }
                }
            }
        } else {
            return;
        }
        // return $content;
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    // protected static function booted()
    // {
    //     static::addGlobalScope('automation', function (Builder $builder) {
    //         $builder->where('ou_id', Auth::user()->ou_id);
    //     });
    // }
}
