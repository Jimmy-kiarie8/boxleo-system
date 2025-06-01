<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
    {{-- <link href="{{ asset('css/pdf2.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/pdf-ke.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@900&display=swap" rel="stylesheet">

</head>

<body>
    @foreach ($data as $item)
        <div class="page-break">

            <table class="table" style="margin-top: -30px;width:100%">
                <tr>
                    {{-- <td align="left" style="border: none">
                                        <img src="{{ $company->logo }}" width="150" style="margin-top: 10px">
                                    </td> --}}
                    {{-- <td align="center" style="border: none">
                                        <div style="text-align: left">
                                            <h4 class="mt-2 mb-3">Shipped From</h4>
                                            <p class="fs-12 text-black-50">{{ $company->name }}</p>
                                            <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                            <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                            <address class="fs-12 text-black-50">{{ $company->address }}</address>

                                            @if ($ou->ou_phone)
                                                <address class="fs-12 text-black-50">{{ $ou->ou_phone }}</address>
                                            @else
                                                <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                            @endif
                                            @if ($ou->email)
                                                <address class="fs-12 text-black-50">{{ $ou->email }}</address>
                                            @else
                                                <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                            @endif
                                            @if ($ou->address)
                                                <address class="fs-12 text-black-50">{{ $ou->address }}</address>
                                            @else
                                                <address class="fs-12 text-black-50">{{ $company->address }}</address>
                                            @endif
                                        </div>
                                    </td>
                                    <td align="right" style="text-align: left;border: none">

                                        <div style="">
                                            <h4 class="mt-2 mb-3">Ship To</h4>
                                            <p class="fs-12 text-black-50">{{ $item->client->name }}</p>
                                            <address class="fs-12 text-black-50">{{ $item->client->phone }}</address>
                                            <address class="fs-12 text-black-50">{{ $item->client->email }}</address>
                                            <address class="fs-12 text-black-50">{{ $item->client->address }}</address>
                                        </div>

                                    </td> --}}
                </tr>
            </table>
            {{-- <table class="table bg-white table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Order date</th>
                                        <th scope="col">Order number</th>
                                        <th scope="col">Payment method</th>
                                        <th scope="col">Shipping Address</th>
                                        <th scope="col">City</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ $item->order_no }}</td>
                                        <td>COD</td>
                                        <td>{{ $item->client->address }}</td>
                                        <td>{{ $item->client->city }}</td>
                                    </tr>
                                </tbody>
                            </table> --}}
            <table class="table bg-white table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> {{ $company->name }} </td>
                        @if ($ou->ou_phone)
                            <td>{{ $ou->ou_phone }}</td>
                        @else
                            <td>{{ $company->phone }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>

            <table class="table bg-white table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->products as $product)
                        <tr>
                            <td> {{ $item->client->name }} </td>
                            <td> {{ $item->client->address }} </td>
                            <td> {{ $item->client->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table bg-white table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">Product name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">City</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->products as $product)
                        <tr>
                            <td> {{ $product->product_name }} </td>
                            <td> {{ $product->pivot->quantity }} </td>
                            <td> {{ $item->client->city }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <table class="">
                <tr>
                    <td align="left">

                        <img src="{{ $item->barcode }}" alt="" style="width: 430px;height: 80px;">

                    </td>
                    <td align="right">
                        <b>{{ $item->order_no }}</b> <br>
                        <div style="d-block font-weight-bold; color:black">
                            {{ $ou->currency_code }} <b>{{ $item->total_price }}</b>
                        </div>
                    </td>
                </tr>
            </table>

            <span class="d-block">Expected delivery date</span><span
                                class="font-weight-bold">
                                {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                            </span><span class="d-block mt-3 text-black-50 fs-15">
                                {{ $item->customer_notes }}</span>
            <div class="d-flex justify-content-between align-items-center footer">
                <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                <hr>

                <span class="" style="font-size: 16px;">
                    {{-- <p>Payment is to be made on delivery via MPESA PAYBILL NO. <b>4032407</b>. Account No: <b>{{ $item->order_no }}</b></p> --}}

                    @php
                        // Replace placeholders with actual order number and paybill number
                        // $waybill_terms = str_replace('{% paybill %}', 4032407, $waybill_terms);
                        $text = str_replace('{% order_no %}', $item->order_no, $waybill_terms);
                    @endphp

                    <span class="d-block font-weight-bold">{{ $text }}</span>
                    {{-- <strong> {{ $text }}</strong></span><br> --}}
                    {{-- <span>{{ $company->notes }}</span> --}}
            </div>
        </div>
        </div>
    @endforeach


    <div id="packing">
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left">
                        {{--  <strong style="line-height: 20px; font-size: 15px;">{{ env('APP_NAME') }}</strong><br>
                        {{ env('APP_URL') }} <br>
                        020-900-939-333 <br>
                        support@logixsaas.com
                        <br> --}}
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                    </td>
                    <td align="right" style="width: 40%;">
                        <h3>Packing List</h3> <br>
                        {{-- <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address>
                        </pre> --}}



                        @if ($ou->ou_phone)
                            <address class="fs-12 text-black-50">{{ $ou->ou_phone }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                        @endif
                        @if ($ou->email)
                            <address class="fs-12 text-black-50">{{ $ou->email }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->email }}</address>
                        @endif
                        @if ($ou->address)
                            <address class="fs-12 text-black-50">{{ $ou->address }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->address }}</address>
                        @endif
                        <br>
                        #Date <b>{{ date('Y-m-d') }}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="invoice">
            <hr style="color: rgba(0,0,0,.12)" />
            <table width="100%" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $order)
                        {{-- {{ dd($order) }} --}}
                        @foreach ($order->products as $product)
                            {{-- {{ $key += 1 }} --}}
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $order->order_no }}</td>
                            </tr>
                            {{-- {{ $key_ += 1 }} --}}
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="information" style="position: absolute; bottom: 0;">
            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved.
                    </td>
                    <td align="right" style="width: 50%;">
                        {{-- <a href="https://logixsaas.com" style="color: #212529" target="_blank">Logixsaas.com</a> --}}
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>
