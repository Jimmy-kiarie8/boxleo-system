<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Mail\ContactMail;
use App\Mail\LeadMail;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadRequest $request)
    {
        // return $request->all();

        $lead = Lead::create($request->all());

        $subject = 'New Lead';
        $name = $request->company;
        $phone = $request->phone;
        $email = $request->email;
        $website = $request->website;
        $country = $request->country;
        $content = 'Website: ' . $website . '. Country: ' . $country . '. New free trial account';

        Mail::to($email)
            ->send(new LeadMail($lead));



        $sender = false;
        Mail::to(env('ADMIN_MAIL', 'support@logixsaas.com'))->send(new ContactMail($subject, $name, $email, $phone, $content, $sender));

        return redirect()->back()->with(['message' => 'We have received your details. We are excited to inform you that your free trial account will be created as soon as possible. Check your email for updates.']);   

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeadRequest  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }
}
