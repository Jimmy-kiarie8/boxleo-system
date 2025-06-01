<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    public function contact(Request $request)
    {
        // return back()->withErrors('your error message');
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => 'required|captcha',
            'email' => 'required',
        ]);
 
        if ($validator->fails()) {
            return redirect('/contact')
                        ->withErrors($validator)
                        ->withInput();
        }
        $subject = $request->subject;
        $name = $request->name;
        $email = $request->email;
        $content = $request->content;
        $phone = $request->phone;

        $sender = false;
        Mail::to(env('ADMIN_MAIL', 'support@logixsaas.com'))->send(new ContactMail($subject, $name, $email, $phone, $content, $sender));

        $content_mail = '';

        $sender = true;
        Mail::to($email)->send(new ContactMail('MAIL RECEIVED', $name, $email, $phone, $content_mail, $sender));

        // return redirect()->back();
        return redirect()->back()->with(['message' => 'Thank you for contacting us. We will contact you as soon as possible.']);   

    }
}
