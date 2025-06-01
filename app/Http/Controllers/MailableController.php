<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Client;
use App\Models\settings\Mailable;
use App\Models\User;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Mailable::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return ($request->all());
        $data = $request->all();
        $data['user_id'] = Auth::id();
        return Mailable::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\settings\Mailable  $mailable
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Mailable::where('model', $id)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\settings\Mailable  $mailable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\settings\Mailable  $mailable
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mailable::find($id)->delete();
    }

    function getModelName($table)
    {
        return Str::studly(Str::singular($table));
    }
    public function testmail($id)
    {
        $mail = Mailable::first();
        $content = $mail->template;
        $model = $mail->model;
        $recipients = $mail->recipient;

        $table_model = "App\Models" . "\\" . $this->getModelName($model);

        // $table = DB::table($mail->model)->with(['clients'])->first();
        $table = new $table_model;
        $table = $table->where('id',$id)->get();
        // $table = $table->take(1)->get();

        $transform_sales = new SaleController;

        $table = $transform_sales->transform_sales($table)[0];

        //    dd($table = $table[0]);
        // dd($table);
        $recipient_arr = [];
        foreach ($recipients as $recipient) {
            if ($recipient == 'You') {
                $recipient_arr[] = Auth::user()->email;
            } elseif ($recipient == 'Client') {
                $recipient_arr[] = Client::find($table->client_id)->email;
            } elseif ($recipient == 'Vendor') {
                $recipient_arr[] = Seller::find($table->seller_id)->email;
            } elseif ($recipient == 'Created by') {
                $recipient_arr[] = User::find($table->user_id)->email;
            } elseif (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $recipient_arr[] = $recipient;
            } elseif ($recipient == 'Owner') {
            }
        }

        $recipient_arr = array_values(array_filter($recipient_arr));
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
        // return $content;
        Mail::send(new TestMail($content, $mail->subject, $recipient_arr));
    }
}
