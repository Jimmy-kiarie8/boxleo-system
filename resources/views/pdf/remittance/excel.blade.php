<table width="100%" class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Order No.</th>
            <th>Total Amount</th>
            <th>Item Quantity</th>
            <th>Item Description</th>
            <th>Client Name</th>
            <th>Location</th>
            <th>City</th>
            <th>Phone</th>
            <th>Upsell(Yes or No)</th>
            <th>Status</th>
            <th>Status Date</th>
            <th>Delivery Fee</th>
            <th>Order Total</th>
            <th>Special Remark</th>
            <th>Custom Reason</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $order)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $order->order_no }}</td>
                <td>{{ format_currency($order->total_price) }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->product_name }}</td>
                <td>{{ $order->client->name }}</td>
                <td>{{ $order->client->address }}</td>
                <td>{{ $order->client->city }}</td>
                <td>{{ $order->client->phone }}</td>
                <td>{{ $order->upsell }}</td>
                <td>{{ $order->delivery_status }}</td>
                <td>{{ format_currency($order->shipping_charges) }}</td>
                <td>{{ format_currency($order->total_price) }}</td>
                <td>{{ $order->customer_notes }}</td>
                <td>{{ $order->customer_notes }}</td>
            </tr>
        @endforeach
    </tbody>
    <tr></tr>
    <tr></tr>
    <tfoot>
        <tr>
            <th colspan="11"></th>
            <th>Grand Total:</th>
            <th>
                {{ format_currency($remmit->amount) }}
            </th>
        </tr>
        <tr>
            <th colspan="11"></th>
            <th>Charges:</th>
            <th>
                {{ format_currency($remmit->charge) }}
            </th>
        </tr>
        <tr>
            <th colspan="11"></th>
            <th>Balance:</th>
            <th style="color: red">
                {{ format_currency($remmit->amount - $remmit->charge) }}
            </th>
        </tr>
    </tfoot>
</table>