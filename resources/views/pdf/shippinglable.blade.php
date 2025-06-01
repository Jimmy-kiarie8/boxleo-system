@extends('pdf.layouts.app')

@section('content')
    <div class="page-break">
        <div class="card" style="border: none;padding: 0 10px;padding-bottom: 10px;">
            <div class="card-body" style="padding: 0;width: 600px;margin: auto;">
                <table width="100%" style="border: 1px solid #605a5a59">
                    <tr>
                        <td align="left" style="border-bottom: 1px solid #605a5a59; border-right: 1px solid #605a5a59;width: 150px;text-align: center;">
                            <div>
                                <img src="{{ asset('home/logo.png') }}" alt="" style="width: 100px; height: 100px">
                            </div>
                        </td>
                        <td align="right" style="border-bottom: 1px solid #605a5a59">
                            <div style="text-align: left;">
                                To:
                                <small style="margin-left: 25px">{{ $order->client->name }}</small>
                                <address style="margin-left: 40px">{{ $order->client->phone }}</address>
                                <address style="margin-left: 40px">{{ $order->client->address }}</address>
                                <h5 style="border-top: 1px solid #605a5a59;">
                                    {{ $order->product_name }}
                                </h5>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #605a5a59;">
                        <td align="left" style="border-bottom: 1px solid #605a5a59; border-right: 1px solid #605a5a59;">
                            <p>From: <b>{{ $company->name }}</b></p>
                            <p>{{ $company->address }}</p>
                        </td>
                        <td align="right" style="border-bottom: 1px solid #605a5a59">
                            <div style="text-align: left;">
                                <p> Carrier:</p>
                                <small><strong>{{ $company->name }}</strong></small>
                                <br>
                                <small><strong>{{ $company->phone }}</strong></small>
                            </div>
                        </td>
                    </tr>

                    <tr style="border-top: 1px solid #605a5a59">
                        <td align="left" style="border-bottom: 1px solid #605a5a59; border-right: 1px solid #605a5a59">
                            <p>Order No. <b>{{ $order->order_no }}</b></p>
                            <img src="{{ $order->barcode }}" alt="" style="width: 150px; height: 50px">
                        </td>
                        <td align="right" style="border-bottom: 1px solid #605a5a59">
                            <div style="text-align: left;">
                                <p> Fragile: <b>________</b></p>
                                <p> Ship date: <b>{{ $order->delivery_date }}</b></p>
                            </div>
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #605a5a59">
                        <td>
                            <p> Notes: <b>{{ $order->customer_notes }}</b> </p>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #605a5a59">
                        <td align="left">
                            <p>Receiver info. </p>
                            <p>Name: _______________ </p>
                            <p></p>
                            <p>Signature: _______________ </p>
                        </td>
                        <td align="left">
                            <p></p>
                            <p>Date: _______________ </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>    
    </div>

@endsection
