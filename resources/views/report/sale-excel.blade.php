<table>
    <thead>
    <tr>
        <th>Order Number</th>
        <th>Created On</th>
        <th>Quantity</th>
        <th>Product Name</th>
        <th>Client Name</th>
        <th>Client Phone</th>
        <th>Client Address</th>
        <th>Vendor Name</th>
        <th>Delivery status</th>
        <th>status</th>
        <th>Delivery Date</th>
        <th>Delivered On</th>
        <th>Dispatched On</th>
        <th>Returned On</th>
        <th>Mpesa Code</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->order_no }}</td>
            <td>{{ $sale->created_at }}</td>
            <td>{{ $sale->quantity }}</td>
            <td>{{ $sale->product_name }}</td>
            <td>{{ $sale->client_name }}</td>
            <td>{{ $sale->client_phone }}</td>
            <td>{{ $sale->client_address }}</td>
            <td>{{ $sale->seller->name }}</td>
            <td>{{ $sale->delivery_status }}</td>
            <td>{{ $sale->status }}</td>
            <td>{{ $sale->delivery_date }}</td>
            <td>{{ $sale->delivered_on }}</td>
            <td>{{ $sale->dispatched_on }}</td>
            <td>{{ $sale->returned_on }}</td>
            <td>{{ $sale->mpesa_code }}</td>
            <td>{{ $sale->customer_notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>