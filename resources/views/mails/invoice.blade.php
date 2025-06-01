<!doctype html>
<html lang="en">
{{--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">  --}}

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        @page {
            margin: 20px;
        }

        body {
            margin: 0px;
            background: #f8fafc;
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

        .page-break {
            page-break-after: always;
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
    </style>

</head>

<body>
    {{-- {{ dd($item) }} --}}
    <div class="page-break">
        {{-- <div class="information">
            <table width="100%">
                <tr>
                    <td align="center">
                        <img src="{{ $item->barcode }}" alt="" style="width: 200px; height: 80px">
                    </td>
                </tr>

            </table>
        </div> --}}
        <div>
            <div class="information">
                <table width="100%">
                    <tr>
                        <td align="left">
                            <img src="{{ env('APP_URL') . '/' . $company->logo }}" alt="Logo" style="width:200px;">
                            <p>{{ $company->name }}</p>
                            <address>{{ $company->address }}</address>
                            <p>{{ $company->phone }}</p>
                        </td>
                        {{-- <td align="right">
                            <img src="{{ $item->barcode }}" alt="" style="width: 200px; height: 80px">
                            <br>
                            <b>WayBill Number:: {{ $item['order_no'] }}</b>
                        </td> --}}
                        <td align="right" style="width: 40%;">
                            {{-- <img src="{{ $item->barcode }}" alt="" style="width: 200px; height: 80px">
                            <br>
                            <b style="text-align: center;">{{ $item['order_no'] }}</b>
                            <br> --}}

                            <h4>Invoice No#: {{ $sale->invoice->invoice_no }}</h4><br>
                            Invoiced: {{ $sale->invoice->created_at }} <br>
                            Due Date: {{ $sale->invoice->due_date }} <br>
                            </pre>
                        </td>
                    </tr>
                </table>
            </div>
            <hr style="color: rgba(0,0,0,.12)" />
            <div class="information">
                <table width="100%">
                    <tr>
                        <td align="left" style="width: 40%;">
                            <h3>Bill To</h3>
                            <b>Name</b>: {{ $sale->client->name }} <br>
                            <b>Address</b>: {{ $sale->client->address }}<br>
                            <b>Phone</b>: {{ $sale->client->phone }}
                        </td>
                        <td align="right" style="width: 40%;">
                            <h3>Ship To</h3>
                            <b>Name</b>: {{ $sale->client->name }} <br>
                            <b>Address</b>: {{ $sale->client->address }}<br>
                            <b>Email</b>: {{ $sale->client->email }}<br>
                            <b>Phone</b>: {{ $sale->client->phone }}
                        </td>
                    </tr>
                </table>
            </div>
            <br />
            <div class="invoice">
                <table width="100%" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->products as $key => $product)
                        <tr>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->pivot->price }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td align="left">{{ $product->pivot->quantity *  $product->pivot->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td align="left">Total</td>
                            <td align="left" class="gray">{{ $currency }} {{ $sale->sub_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if ($sale->comment || $company->notes)
            <div>
                <h4>Note</h4>
                <p>{{ $sale->comment }}</p>

                <small>{{ $company->notes }}</small>


            </div>
            @endif

            @if ($company->terms)
            <div>
                <h4>TERMS & CONDITIONS</h4>
                <small>{{ $company->terms }}</small>


            </div>
            @endif
            <div class="information" style="position: absolute; bottom: 0;">
                <table width="100%">
                    <tr>
                        <td align="left" style="width: 50%;">
                            &copy; {{ date('Y') }} <a href="{{ $company->website }}" style="color: rgb(9, 9, 61)">
                                {{ $company->name }}</a> - All rights reserved.
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</body>

</html>
