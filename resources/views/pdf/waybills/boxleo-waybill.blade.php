<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
    {{-- <link href="{{ asset('css/pdf2.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">

</head>

<body>
    <div class="mt-5 mb-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10">
                <div class="receipt bg-white p-3 rounded">

                    <table class="table" style="margin-top: -35px;width: 100%">
                        <tr>
                            <td align="left" style="border: none">
                                <img src="{{ $company->logo }}" width="160" style="margin-top: 10px">
                            </td>
                            <td align="center" style="border: none">
                                <div style="text-align: left">
                                    <h4 class="mt-2 mb-3">Shipped From</h4>
                                    <p class="fs-12 text-black-50">{{ $company->name }}</p>
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
                                <th scope="col">City</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->order_no }}</td>
                                <td>COD</td>
                                <td>{{ $item->client->address }}</td>
                                <td>{{ $item->client->city }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <table class="table bg-white table-bordered" style="width: 100%">
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

                                <img src="{{ $item->barcode }}" alt="" style="width: 400px;height: 80px;"><br>
                                <b>{{ $item->order_no }}</b>
                            </td>
                            <td align="right">
                                <div style="d-block font-weight-bold; color:black">Total: <b>
                                        <b>{{ $item->total_price }}</b></div>
                            </td>
                        </tr>
                    </table>

                    <span class="d-block">Expected delivery date</span><span class="font-weight-bold">
                        {{-- {{($item->delivery_date)->format('y')}} --}}
                        {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                        <br>
                                {{-- <small>Served by: {{ $sale->served_by }}</small> --}}
                    </span><span class="d-block mt-3 text-black-50 fs-15">
                        {{ $item->customer_notes }}</span>
                    <hr>



                    @if (Auth::user()->ou_id == 3)
                        <div class="d-flex justify-content-between align-items-center footer">
                            <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                                <small>Customers are requested to pay through MomoPay or Airtel. However, cash payments
                                    will be acceptable in case to case basis. Please
                                    follow the following instructions to pay</small>
                                <table class="table">
                                    <tr>
                                        <td align="left">
                                            <h2>MomoPay</h2>
                                            <small>1. Press *165*3#</small><br>
                                            <small>2. Input merchant code 659130</small><br>
                                            <small>3. Enter Amount UGX {{ $item->total_price }}</small><br>
                                            <small>4. Choose mobile money</small><br>
                                            <small>5. Input your PIN number to complete payment.</small><br>

                                        </td>
                                        <td align="right">
                                            <h2>Airtel Money</h2>
                                            <small>1. Press *185*9#</small><br>
                                            <small>2. Input Merchant ID 4342744</small><br>
                                            <small>3. Enter Amount UGX {{ $item->total_price }}</small><br>
                                        </td>
                                    </tr>
                                </table>


                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-center footer">
                            <div class="thanks">
                                <span class="d-block font-weight-bold">Terms&Conditions</span>
                                <hr>
                                <span class="d-block font-weight-bold">{{ $waybill_terms }}</span>
                              {{--   <span class="" style="font-size: 16px;">
                                    <strong> {{ $waybill_terms }}</strong></span> --}}
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>


</body>

</html>
