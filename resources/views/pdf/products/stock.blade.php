<!DOCTYPE html>
<html>

<head>
    <title>Stock Report</title>
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
            padding: .35rem;
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
    <div class="information">
        <table width="100%" class="table table-striped">
            <tr>
                <td align="left">
                    <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                </td>
                <td align="right" style="width: 40%;">
                    <h3>Stock Report for {{ $product->product_name }}</h3>
                    <small>{{ $company->name }}</small> <br>
                    <small>{{ $company->phone }}</small> <br>
                    <address>{{ $company->email }}</address> <br>
                    <address>{{ $company->address }}</address> <br>
                    </pre>
                </td>
            </tr>
        </table>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>In Transit</th>
                <th>Delivered</th>
                <th>Returned</th>
                <th>Received</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Balance</b></td>
                <td><b>{{ $running_balance }}</b></td>
            </tr>
            @php
                $run_bal = $running_balance;
            @endphp
            @foreach ($data as $date => $values)
                @php
                    $run_bal = $run_bal - $values['in_transit'] + $values['returned'] + $values['received'];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $date }}</td>
                    <td>{{ $values['in_transit'] }}</td>
                    <td>{{ $values['delivered'] }}</td>
                    <td>{{ $values['returned'] }}</td>
                    <td>{{ $values['received'] }}</td>
                    <td>{{ $run_bal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
