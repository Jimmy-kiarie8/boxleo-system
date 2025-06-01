<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Picking List</title>
    <style type="text/css">
        @page {
            margin: 0px;
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
            padding: .45rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
        }

        th {
            text-align: inherit;
        }


        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .order-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .product-item {
            border-bottom: 1px dashed #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
    </style>

</head>

<body>
    <div class="page-break">
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left">
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                    </td>
                    
                    <td align="right" style="width: 40%;">
                        <h2>PICKING LIST</h2>
                        <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address> <br>
                        <strong>Date: {{ date('D d M Y') }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice">
            <table width="100%" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($data as $order)
                        @php 
                            $rowspan = count($order->products);
                            $first = true;
                        @endphp
                        
                        @foreach ($order->products as $product)
                            <tr>
                                @if ($first)
                                    <td rowspan="{{ $rowspan }}">{{ $counter }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $order->order_no }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $order->client->name ?? 'N/A' }}</td>
                                    @php $first = false; @endphp
                                @endif
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ $order->delivery_status }}</td>
                            </tr>
                        @endforeach
                        @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="information" style="position: absolute; bottom: 0; width:100%">
            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 37%;">
                        Prepared By _________________________
                    </td>
                    <td align="center" style="width: 37%;">
                        Signature ___________________________
                    </td>
                    <td align="right" style="width: 26%;">
                        Date <b><u>{{ date('D d M Y') }}</u></b>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 37%;">
                        Picked By _________________________
                    </td>
                    <td align="center" style="width: 37%;">
                        Signature ___________________________
                    </td>
                    <td align="right" style="width: 26%;">
                        Date _________________________
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
