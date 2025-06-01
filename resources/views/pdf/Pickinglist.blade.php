<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Packing List</title>
    <style type="text/css">
        @page {
            margin: 40px;
        }

        body {
            margin: 0px;
            background: #fff;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }


        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .page-break {
            page-break-after: always;
        }

        .table thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        .table tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        th {
            text-align: inherit;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
    </style>

</head>

<body>

    {{-- {{ ($data) }} --}}
    <div class="page-break">
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
                        <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address> <br>
                        {{--  Package# {{ $data->package_no }} --}}
                        </pre>
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
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Address</th>
                        <th>Delivery status</th>
                        <th>Delivery date</th>
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
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ $order->client->address }}</td>
                        <td>{{ $order->delivery_status }}</td>
                        <td>{{ $order->delivery_date }}</td>
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
                       <a href="https://logixsaas.com" style="color: #212529" target="_blank">Logixsaas.com</a>
                       {{-- <a href="{{ $company->website }}" style="color: #212529" target="_blank">{{ $company->website }}</a> --}}
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>
