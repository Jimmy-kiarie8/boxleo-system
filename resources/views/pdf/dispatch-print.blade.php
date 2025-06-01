<!-- Start Generation Here -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dispatch Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .dispatch-info {
            margin-bottom: 20px;
        }
        .dispatch-info div {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table, .table th, .table td {
            border: 1px solid black;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Dispatch Details</h1>

<div class="dispatch-info">
    <div><strong>Waybill No:</strong> {{ $dispatch->waybill_no }}</div>
    <div><strong>Vendor:</strong> {{ $dispatch->vendor_name }}</div>
    <div><strong>Date:</strong> {{ $dispatch->dispatch_date }}</div>
    <div><strong>Status:</strong> {{ $dispatch->status }}</div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
<!-- End Generation Here -->
