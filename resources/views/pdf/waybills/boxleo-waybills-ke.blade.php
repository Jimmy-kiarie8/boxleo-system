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
        <div class="page-break" style="margin-top: -35px">

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
                        <tr>
                            <td> {{ $item->client->name }} </td>
                            <td> {{ $item->client->address }} </td>
                            <td> {{ $item->client->phone }}</td>
                        </tr>
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

                        <img src="{{ $item->barcode }}" alt="" style="width: 330px;height: 80px;">

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


    <div id="packing" style="margin-top: -50px">
        <div class="invoice">
            <p>Packing List <br><span>#Date <b>{{ date('Y-m-d') }}</b></span></p>
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $order)
                        @foreach ($order->products as $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $order->order_no }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <div class="information" style="position: absolute; bottom: 0;">
            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved.
                    </td>
                    <td align="right" style="width: 50%;">
                    </td>
                </tr>

            </table>
        </div> --}}
    </div>

</body>

</html>
