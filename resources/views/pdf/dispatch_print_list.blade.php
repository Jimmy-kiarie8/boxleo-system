<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Product Summary Report</h1>
        <p>Date: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Merchant</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @php $totalQuantity = 0; @endphp
            @foreach($productSummary as $index => $product)
                @php $totalQuantity += $product['total']; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['seller_name'] ?? 'Unknown' }}</td>
                    <td>{{ $product['total'] }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalQuantity }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signatures" style="margin-top: 50px;">
        <div style="float: left; width: 30%;">
            <p>________________________</p>
            <p>Warehouse Manager</p>
            <p>Date: _______________</p>
        </div>
        <div style="float: left; width: 30%; margin-left: 5%;">
            <p>________________________</p>
            <p>Warehouse Officer</p>
            <p>Date: _______________</p>
        </div>
        <div style="float: right; width: 30%;">
            <p>________________________</p>
            <p>Requisition Officer</p>
            <p>Date: _______________</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <p>This report lists the total quantity of each product across all orders.</p>
        <p>&copy; {{ date('Y') }} Boxleo - All rights reserved.</p>
    </div>
</body>
</html>
