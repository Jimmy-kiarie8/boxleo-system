<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} Report</title>
    <style>
        table {
            border: 1px dashed #000;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <th>
                    <img src="{{ public_path() . '/images/logo.png' }}" width="150px" height="90px">
                    <br>
                </th>
                <th colspan="5"><b>BOXLEO COURIER AND FULFILLMENT SERVICES</b><br>
                    @if ($ou)
                        <b>
                            {{ strtoupper($ou->address) }}
                        </b>
                    @else
                        <b>AKSHRAP GODOWNS GATE A-2, JKIA JUNCTION, NAIROBI</b>
                    @endif <br><b>
                        EMAIL: INFO@BOXLEOCOURIER.COM WEBSITE:BOXLEOCOURIER.COM</b><br>
                    <b>MERCHANTS ORDERS REPORT </b>
                </th>
            </tr>
            <tr></tr>
            <tr style="background-color: #ffb53a; color: #FFFFFF;">
                <th>No</th>
                <th>Order No</th>
                <th>Product Item</th>
                <th>Receiver Name</th>
                <th>Receiver Address</th>
                <th>Receiver Email</th>
                <th>Receiver Phone</th>
                <th>Receiver Gender</th>
                <th>Receiver Country</th>
                <th>Receiver Town</th>
                <th>Receiver Latitude</th>
                <th>Receiver Longitude</th>
                <th>Special Instruction</th>
                <th>Payment Type</th>
                <th>Cash On Delivery</th>
                <th>Insurance</th>
                <th>Order Status</th>
                <th>Custom Reason</th>
                <th>Created at</th>
                <th>Status Date</th>
                <th>Scheduled Date</th>
                <th>Scheduled Time</th>
                <th>Delivery Date</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>TAT</th>
                <th>Rider</th>
                <th>Zones</th>
                <th>Agent</th>
            </tr>

            @foreach ($orders as $order)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ $order['order_no'] }}</td>
                    <td> {{ $order['product_name'] }}</td>

                    <td> {{ $order['client']['name'] }}</td>
                    <td> {{ $order['client']['address'] }}</td>
                    <td> {{ $order['client']['email'] }}</td>
                    <td> {{ $order['client']['phone'] }}</td>
                    <td> {{ $order['client']['gender'] }}</td>
                    <td> {{ $order['client']['country_name'] }}</td>
                    <td> {{ $order['client']['city'] }}</td>
                    <td> {{ $order['client']['latitude'] }}</td>
                    <td> {{ $order['client']['longitude'] }}</td>
                    <td> {{ $order['special_instruction'] }}</td>
                    <td> {{ $order['payment_type'] }}</td>

                    @if ($ou->id == 4)
                        <td> {{ $order['invoice_value'] }}</td>
                    @else
                        <td> {{ $order['total_price'] }}</td>
                    @endif
                    <td> {{ $order['insurance'] }}</td>
                    <td>{{ $order['delivery_status'] }}</td>
                    <td> {{ $order['customer_notes'] }}</td>
                    <td> {{ $order['created_at'] }}</td>
                    <td> {{ $order['updated_at'] }}</td>
                    {{-- <td> {{ $order['schedule_date'] }}</td> --}}
                    <td>{{ date('Y-m-d', strtotime($order['schedule_date'])) }}</td>
                    <td>{{ date('H:i:s', strtotime($order['schedule_date'])) }}</td>

                    <td> {{ $order['delivery_date'] }}</td>
                    <td> {{ $order['quantity'] }}</td>

                    @if ($ou->id == 4)
                        <td>{{ $order['invoice_value'] }}</td>
                    @else
                        <td>{{ $order['total_price'] }}</td>
                    @endif
                    <td> {{ $order['tat'] }}</td>
                    <td> {{ $order['rider_name'] }}</td>
                    <td> {{ $order['zone_name']}}</td>
                    <td> {{ $order['agent_name'] }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
