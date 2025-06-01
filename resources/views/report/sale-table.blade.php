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
                </th>
                <th colspan="5"><b>BOXLEO COURIER AND FULFILLMENT SERVICES</b><br>
                    @if ($ou)
                        <b>
                            {{ strtoupper($ou->address) }}
                        </b>
                    @else
                        <b>AKSHRAP GODOWNS GATE A-2, JKIA JUNCTION, NAIROBI</b>
                    @endif                    <br><b>
                        EMAIL: INFO@BOXLEOCOURIER.COM WEBSITE:BOXLEOCOURIER.COM</b><br>
                    <b>REPORT ORDERS REPORT </b>
                </th>
            </tr>
            <tr></tr>
            <tr style="background-color: #ffb53a; color: #FFFFFF;">
                <th>#</th>
                <th>Order No</th>
                <th>Product Item</th>
                <th>Receiver Name</th>
                <th>Receiver Address</th>
                <th>Receiver Email</th>
                <th>Receiver Phone</th>
                <th>Receiver Town</th>
                <th>Special Instruction</th>
                <th>Payment Type</th>
                <th>Cash On Delivery</th>
                <th>Order Status</th>
                <th>Status Date</th>
                <th>Delivery Date</th>
            </tr>

            @foreach ($orders as $order)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ $order->order_no }}</td>
                    <td> {{ $order->product_name }}</td>
                    <td> {{ $order->client['name'] }}</td>
                    <td> {{ $order->client['address'] }}</td>
                    <td> {{ $order->client['email'] }}</td>
                    <td> {{ $order->client['phone'] }}</td>
                    <td> {{ $order->client['city'] }}</td>
                    <td> {{ $order->special_instruction }}</td>
                    <td> {{ $order->payment_type }}</td>
                    <td> {{ $order->total_price }}</td>
                    <td> {{ $order->delivery_status }}</td>
                    <td> {{ $order->updated_at }}</td>
                    <td> {{ $order->delivery_date }}</td>
                    <td> {{ $order->agent_name }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
