<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
    <link href="{{ asset('css/pdf1.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/pdf.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/http/bootstrap.css') }}" rel="stylesheet"> --}}


</head>

<body>
        <div class="mt-5 mb-5 container-fluid" style="margin-top: -40px">
            <div class="d-flex justify-content-center row">
                <div class="col-md-10">
                    <div class="receipt bg-white p-3 rounded">

                        <table class="table" style="margin-top: -35px;">
                            <tr>
                                <td align="left" style="border: none">
                                    <img src="{{ $company->logo }}" width="100" style="margin-top: 10px">
                                </td>
                                <td align="center" style="border: none">
                                    <div style="text-align: left">
                                        <h4 class="mt-2 mb-3">Shipped From</h4>
                                        <p class="fs-12 text-black-50">{{ $company->name }}</p>
                                        <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                        <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                        <address class="fs-12 text-black-50">{{ $company->address }}</address>
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

                                </td>
                            </tr>
                        </table>
                        <table class="table bg-white table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Order date</th>
                                    <th scope="col">Order number</th>
                                    <th scope="col">Payment method</th>
                                    <th scope="col">Shipping Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->order_no }}</td>
                                    <td>COD</td>
                                    <td>{{ $item->client->address }}</td>
                                </tr>
                            </tbody>
                        </table>


                        <table class="table bg-white table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Product name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Mode of service</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->products as $product)
                                    <tr>
                                        <td> {{ $product->product_name }} </td>
                                        <td> {{ $product->pivot->quantity }} </td>
                                        <td> Delivery service </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <table class="table">
                            <tr>
                                <td align="left">

                                    <img src="{{ $item->barcode }}" alt="" style="width: 180px;height: 50px;"><br>
                                </td>
                                <td align="right">
                                    <div style="d-block font-weight-bold; color:black">Total: <b>{{ $currency }}
                                            <b>{{ $item->total_price }}</b></div>
                                </td>
                            </tr>
                        </table>

                        <span class="d-block">Expected delivery date</span><span
                            class="font-weight-bold text-success">
                            {{-- {{($item->delivery_date)->format('y')}} --}}
                            {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                        </span><span class="d-block mt-3 text-black-50 fs-15">
                            {{ $item->customer_notes }}</span>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center footer">
                            <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                                <span class="" style="    font-size: 12px;">
                                    {{ $company->terms }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="receipt bg-white p-3 rounded" style="margin-top: 10px;border-top: 1px solid #000">
                        <table class="table" style="">
                            <tr>
                                <td align="left" style="border: none">
                                    <img src="{{ $company->logo }}" width="100" style="margin-top: 10px">
                                </td>
                                <td align="center" style="border: none">
                                    <div style="text-align: left">
                                        <h4 class="mt-2 mb-3">Shipped From</h4>
                                        <p class="fs-12 text-black-50">{{ $company->name }}</p>
                                        <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                        <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                        <address class="fs-12 text-black-50">{{ $company->address }}</address>
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

                                </td>
                            </tr>
                        </table>
                        <table class="table bg-white table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Order date</th>
                                    <th scope="col">Order number</th>
                                    <th scope="col">Payment method</th>
                                    <th scope="col">Shipping Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->order_no }}</td>
                                    <td>COD</td>
                                    <td>{{ $item->client->address }}</td>
                                </tr>
                            </tbody>
                        </table>


                        <table class="table bg-white table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Product name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Mode of service</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->products as $product)
                                    <tr>
                                        <td> {{ $product->product_name }} </td>
                                        <td> {{ $product->pivot->quantity }} </td>
                                        <td> Delivery service </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <table class="table">
                            <tr>
                                <td align="left">

                                    <img src="{{ $item->barcode }}" alt="" style="width: 180px;height: 50px;"><br>
                                </td>
                                <td align="right">
                                    <div style="d-block font-weight-bold; color:black">Total: <b>{{ $currency }}
                                            <b>{{ $item->total_price }}</b></div>
                                </td>
                            </tr>
                        </table>

                        <span class="d-block">Expected delivery date</span><span
                            class="font-weight-bold text-success">
                            {{-- {{($item->delivery_date)->format('y')}} --}}
                            {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                        </span><span class="d-block mt-3 text-black-50 fs-15">
                            {{ $item->customer_notes }}</span>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center footer">
                            <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                                <span class="" style="    font-size: 12px;">
                                    {{ $company->terms }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</body>

</html>
