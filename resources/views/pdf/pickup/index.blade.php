<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GRN</title>
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

    {{-- {{ ($data) }} --}}
    <div class="page-break">
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left">
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                    </td>
                    <td align="right" style="width: 40%;">
                        <h3>GRN</h3> <br>
                        <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address> <br>
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
                        <th>Client</th>
                        <th>Product</th>
                        <th>Arrival date</th>
                        {{-- <th>Clearing company</th> --}}
                        <th>Clearing date</th>
                        <th>Weight</th>
                        {{-- <th>Price/KG</th> --}}
                        <th>Quantity</th>
                        <th>Faulty Qty</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{ dd($data->data) }} --}}
                    @foreach ($data->data as $product)
                    <tr>
                        <td>{{ $data->vendor_name }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $data->arrival_date }}</td>
                        {{-- <td>{{ $product['clearing_company'] }}</td> --}}
                        <td>{{ $data->created_at }}</td>
                        {{-- <td>{{ $data->created_at->format('Y-m-d') }}</td> --}}
                        <td>{{ array_key_exists('weight',$product) ? $product['weight']:0 }}</td>
                        {{-- <td>{{ $product['price'] }}</td> --}}
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ array_key_exists('faulty',$product) ? $product['faulty']:0 }}</td>
                        <td>{{ array_key_exists('notes',$product) ? $product['notes']:'' }}</td>
                        {{-- <td>{{ $product['price'] * $product['weight'] }}</td> --}}
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5"></th>
                        <th colspan="2">Grand Total:</th>
                        <th colspan="2">
                            {{ $currency }} {{ $data->total }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th colspan="2">Received By:</th>
                        <th colspan="2">
                            _____________________
                        </th>

                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th colspan="2">Received On:</th>
                        <th colspan="2">
                            _____________________
                        </th>

                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th colspan="2">Signature:</th>
                        <th colspan="2">
                            _____________________
                        </th>

                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="information" style="position: absolute; bottom: 0;">
            {{-- <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved.
                    </td>
                    <td align="right" style="width: 50%;">
                       <a href="{{ $company->website }}" style="color: #212529" target="_blank">{{ $company->website }}</a>
                    </td>
                </tr>

            </table> --}}
        </div>
    </div>

</body>

</html>
