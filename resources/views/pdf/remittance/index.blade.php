<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Remittance</title>
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
            /* page-break-after: always; */
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
    <div class="page-break">
            <h3>Remittance report from {{ $start_date->format('D d M Y') }} to {{ $end_date->format('D d M Y') }}</h3>
            <br>
            <br>
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left">
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                        <br>
                        <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address> <br>
                    </td>
                    <td align="right" style="width: 40%;">
                        <small>{{ $vendor->name }}</small> <br>
                        <small>{{ $vendor->phone }}</small> <br>
                        <address>{{ $vendor->email }}</address> <br>
                        <address>{{ $vendor->address }}</address> <br>
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
                        <th>Order No.</th>
                        <th>Client Name</th>
                        <th>Client Phone</th>
                        <th>Client Address</th>
                        <th>Price</th>
                        <th>Charges</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->client->name }}</td>
                            <td>{{ $order->client->phone }}</td>
                            <td>{{ $order->client->address }}</td>
                            <td>{{ format_currency($order->total_price) }}</td>
                            <td>{{ format_currency($order->shipping_charges) }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4"></th>
                        <th>Grand Total:</th>
                        <th>
                            {{ format_currency($remmit->amount) }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4"></th>
                        <th>Charges:</th>
                        <th>
                            {{ format_currency($remmit->charge) }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4"></th>
                        <th>Balance:</th>
                        <th style="color: red">
                            {{ format_currency($remmit->amount - $remmit->charge) }}
                        </th>
                    </tr>
                </tfoot>
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
                       {{-- <a href="{{ $company->website }}" style="color: #212529" target="_blank">{{ $company->website }}</a> --}}
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>
