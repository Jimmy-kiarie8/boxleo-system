<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="{{ asset('css/sticker.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/pdf.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/http/bootstrap.css') }}" rel="stylesheet"> --}}

    <style>
        * {
            font-size: 12px;
            line-height: 18px;
            font-family: 'Ubuntu', sans-serif;
        }

        p {
            margin-bottom: 0 !important;
            font-size: 9px !important;
        }

        td {
            color: #00000080 !important;
            /* font-size: 13px !important; */
        }

        .mt-5,
        .my-5 {
            margin-top: 0 !important;
        }
    </style>
</head>

<body>
    <div class="">
        <div style="margin:0 auto;padding:0 3px;">
            <div class="mt-5 mb-5 container-fluid"
                style="box-shadow: 1px -3px 13px 10px rgba(75,168,151,0.75);background: #FFF;width: 65mm;margin-left:-56px;">
                <div class="d-flex justify-content-center row" style="margin-top: -40px;">
                    <div class="col-md-10">
                        <div class="receipt bg-white p-3 rounded">
                            <table class="table">
                                <tr>
                                    <th align="left" style="border: none">
                                        <img src="{{ $company->logo }}" style="width:70;">
                                    </th>
                                    <td align="center" style="width: 20px"></td>
                                    <td align="right">
                                        <b>{{ $company->name }}</b> <br>
                                        <b>{{ $company->email }}</b> <br>
                                        <b>{{ $company->address }}</b>
                                        </th>
                                </tr>
                            </table>
                            {{-- <h4 class="mt-2 mb-3">Ship To</h4> --}}
                            <table class="table bg-white;">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">Order date</th> --}}
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        {{-- <th><b>{{ $item->created_at }}</th> --}}
                                        <th>
                                            <p>{{ $item->client->name }}</p>
                                        </th>
                                        <th>
                                            <p>{{ $item->client->phone }}</p>
                                        </th>
                                        <td>
                                            <p>{{ $item->client->address }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <table class="table bg-white;">
                                <thead>
                                    <tr>
                                        {{-- <th scope="col">Order date</th> --}}
                                        <th scope="col">Order no.</th>
                                        <th scope="col">Payment method</th>
                                        <th scope="col">COD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>
                                            <p>{{ $item->order_no }}</p>
                                        </th>
                                        <th>
                                            <p>COD</p>
                                        </th>
                                        <th>
                                            <p>{{ $item->total_price }}</p>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table bg-white">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Qty</th>
                                        {{-- <th scope="col">Mode of service</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->products as $product)
                                        <tr>
                                            <th>
                                                <p> {{ $product->product_name }} </p>
                                            </th>
                                            <th>
                                                <p> {{ $product->pivot->quantity }} </p>
                                            </th>
                                            {{-- <th><p> Delivery service </p></th> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <table class="table">
                                <th align="left"></th>
                                <tr>
                                    <td align="center">

                                        <img src="{{ $item->barcode }}" alt=""
                                            style="width: 100%;height: 50px;"><br>
                                        </th>
                                </tr>
                                <th align="right"></th>

                            </table>

                            <span class="d-block">Delivery date
                                <span class="font-weight-bold text-success">
                                    {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                                </span>
                                <hr>
                            </span><span class="d-block mt-3 text-black-50 fs-15">
                                Notes: {{ $item->customer_notes }}</span>
                            <div class="d-flex justify-content-between align-items-center footer">
                                <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions:
                                        <b class="" style="font-size: 10px;">
                                            {{ $company->terms }}</b>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
