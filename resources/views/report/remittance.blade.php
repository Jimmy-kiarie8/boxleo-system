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
                    @endif
                    <br>
                    <b>EMAIL: INFO@BOXLEOCOURIER.COM WEBSITE:BOXLEOCOURIER.COM</b><br>
                    <b>{{ $client_name }} REMITTANCE REPORT </b>
                </th>
            </tr>
            <tr></tr>
            <tr style="background-color: #ffb53a; color: #FFFFFF;">
                <th>No</th>
                <th>Order No</th>
                <th>Total Amount</th>
                <th>Quantity</th>
                <th>Item Description</th>
                <th>Client Name</th>
                <th>Location</th>
                <th>Town</th>
                <th>Phone</th>
                <th>Upsell(Yes or No)</th>
                <th>Order Status</th>
                <th>Status Date</th>
                <th>Delivery Fee</th>
                <th>Fulfillment Fee</th>
                <th>COD</th>
                <th>Order Total</th>
                <th>Custom Reason</th>
                <th>Weight</th>
                {{-- <th>Cod charge</th>
                <th>Outbound delivery</th>
                <th>Outbound return</th>
                <th>Inbound return</th>
                <th>Inbound delivery</th>
                <th>Call charge</th>
                <th>Packaging</th>
                <th>Label printing</th> --}}

                <th>Total charges</th>
                <th>Shipping charges</th>
            </tr>

            @foreach ($orders as $order)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ $order['order_no'] }}</td>
                    <td>
                        @if ($ou->id == 4)
                            {{ $order['invoice_value'] }}
                        @else
                            {{ $order['total_price'] }}
                        @endif
                    </td>
                    <td> {{ $order['quantity'] }}</td>
                    <td> {{ $order['product_name'] }}</td>
                    <td> {{ $order['client']['name'] }}</td>
                    <td> {{ $order['client']['address'] }}</td>
                    <td> {{ $order['client']['city'] }}</td>
                    <td> {{ $order['client']['phone'] }}</td>
                    <td> </td>
                    <td> {{ $order['delivery_status'] }} </td>
                    <td> {{ $order['updated_at'] }}</td>

                    <td>{{ $order['outbound_delivery'] + $order['outbound_return'] + $order['inbound_return'] + $order['inbound_delivery'] }}</td>
                    <td>{{ $order['call_charge'] + $order['packaging'] + $order['label_printing'] }}</td>
                    <td>{{ $order['cod_charge'] }}</td>
                    <td>{{ $order['shipcharges_sum_amount'] }}</td>
                    <td>
                        @if ($order['delivery_status'] == 'Returned')
                            {{ $order['return_notes'] ?? $order['customer_notes'] }}
                        @else
                            {{ $order['customer_notes'] ?? $order['return_notes'] }}
                        @endif
                    </td>
                    <td>{{ $order['weight'] }}</td>
                    <td></td>
                    <td>{{ $order['shipping_charges'] }}</td>

                    {{-- <td>{{ $order['cod_charge'] }}</td>
                    <td>{{ $order['outbound_delivery'] }}</td>
                    <td>{{ $order['outbound_return'] }}</td>
                    <td>{{ $order['inbound_return'] }}</td>
                    <td>{{ $order['inbound_delivery'] }}</td>
                    <td>{{ $order['call_charge'] }}</td>
                    <td>{{ $order['packaging'] }}</td>
                    <td>{{ $order['label_printing'] }}</td> --}}





                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
