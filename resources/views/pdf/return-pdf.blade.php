<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>

    <link href="{{ asset('css/pdf3.css') }}" rel="stylesheet">
    <style>
        #packing td {
            padding: 0
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem
        }

        .table-bordered {
            border: 1px solid #dee2e6
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6
        }

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-borderless tbody+tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, .075)
        }

        .table-primary,
        .table-primary>td,
        .table-primary>th {
            background-color: #b8daff
        }

        .table-primary tbody+tbody,
        .table-primary td,
        .table-primary th,
        .table-primary thead th {
            border-color: #7abaff
        }

        .table-hover .table-primary:hover {
            background-color: #9fcdff
        }

        .table-hover .table-primary:hover>td,
        .table-hover .table-primary:hover>th {
            background-color: #9fcdff
        }

        .table-secondary,
        .table-secondary>td,
        .table-secondary>th {
            background-color: #d6d8db
        }

        .table-secondary tbody+tbody,
        .table-secondary td,
        .table-secondary th,
        .table-secondary thead th {
            border-color: #b3b7bb
        }

        .table-hover .table-secondary:hover {
            background-color: #c8cbcf
        }

        .table-hover .table-secondary:hover>td,
        .table-hover .table-secondary:hover>th {
            background-color: #c8cbcf
        }

        .table-success,
        .table-success>td,
        .table-success>th {
            background-color: #c3e6cb
        }

        .table-success tbody+tbody,
        .table-success td,
        .table-success th,
        .table-success thead th {
            border-color: #8fd19e
        }

        .table-hover .table-success:hover {
            background-color: #b1dfbb
        }

        .table-hover .table-success:hover>td,
        .table-hover .table-success:hover>th {
            background-color: #b1dfbb
        }

        .table-info,
        .table-info>td,
        .table-info>th {
            background-color: #bee5eb
        }

        .table-info tbody+tbody,
        .table-info td,
        .table-info th,
        .table-info thead th {
            border-color: #86cfda
        }

        .table-hover .table-info:hover {
            background-color: #abdde5
        }

        .table-hover .table-info:hover>td,
        .table-hover .table-info:hover>th {
            background-color: #abdde5
        }

        .table-warning,
        .table-warning>td,
        .table-warning>th {
            background-color: #ffeeba
        }

        .table-warning tbody+tbody,
        .table-warning td,
        .table-warning th,
        .table-warning thead th {
            border-color: #ffdf7e
        }

        .table-hover .table-warning:hover {
            background-color: #ffe8a1
        }

        .table-hover .table-warning:hover>td,
        .table-hover .table-warning:hover>th {
            background-color: #ffe8a1
        }

        .table-danger,
        .table-danger>td,
        .table-danger>th {
            background-color: #f5c6cb
        }

        .table-danger tbody+tbody,
        .table-danger td,
        .table-danger th,
        .table-danger thead th {
            border-color: #ed969e
        }

        .table-hover .table-danger:hover {
            background-color: #f1b0b7
        }

        .table-hover .table-danger:hover>td,
        .table-hover .table-danger:hover>th {
            background-color: #f1b0b7
        }

        .table-light,
        .table-light>td,
        .table-light>th {
            background-color: #fdfdfe
        }

        .table-light tbody+tbody,
        .table-light td,
        .table-light th,
        .table-light thead th {
            border-color: #fbfcfc
        }

        .table-hover .table-light:hover {
            background-color: #ececf6
        }

        .table-hover .table-light:hover>td,
        .table-hover .table-light:hover>th {
            background-color: #ececf6
        }

        .table-dark,
        .table-dark>td,
        .table-dark>th {
            background-color: #c6c8ca
        }

        .table-dark tbody+tbody,
        .table-dark td,
        .table-dark th,
        .table-dark thead th {
            border-color: #95999c
        }

        .table-hover .table-dark:hover {
            background-color: #b9bbbe
        }

        .table-hover .table-dark:hover>td,
        .table-hover .table-dark:hover>th {
            background-color: #b9bbbe
        }

        .table-active,
        .table-active>td,
        .table-active>th {
            background-color: rgba(0, 0, 0, .075)
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, .075)
        }

        .table-hover .table-active:hover>td,
        .table-hover .table-active:hover>th {
            background-color: rgba(0, 0, 0, .075)
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #343a40;
            border-color: #454d55
        }

        .table .thead-light th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6
        }

        .table-dark {
            color: #fff;
            background-color: #343a40
        }

        .table-dark td,
        .table-dark th,
        .table-dark thead th {
            border-color: #454d55
        }

        .table-dark.table-bordered {
            border: 0
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, .05)
        }

        .table-dark.table-hover tbody tr:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, .075)
        }

        @media (max-width:575.98px) {
            .table-responsive-sm {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-sm>.table-bordered {
                border: 0
            }
        }

        @media (max-width:767.98px) {
            .table-responsive-md {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-md>.table-bordered {
                border: 0
            }
        }

        @media (max-width:991.98px) {
            .table-responsive-lg {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-lg>.table-bordered {
                border: 0
            }
        }

        @media (max-width:1199.98px) {
            .table-responsive-xl {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-xl>.table-bordered {
                border: 0
            }
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .table-responsive>.table-bordered {
            border: 0
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: .5rem
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        abbr[data-original-title],
        abbr[title] {
            text-decoration: underline;
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted;
            cursor: help;
            border-bottom: 0;
            -webkit-text-decoration-skip-ink: none;
            text-decoration-skip-ink: none
        }

        address {
            margin-bottom: 1rem;
            font-style: normal;
            line-height: inherit
        }

        .d-block {
            display: block !important
        }

        .text-success {
            color: #28a745 !important
        }

        .font-weight-bold {
            font-weight: 700 !important
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div id="packing">
        <div class="invoice">
           
            <div style="text-align: center;">
                <img style="margin-top: 10px" src="https://boxleocourier.com/wp-content/uploads/2021/07/Boxleo_logo_color_web.png" alt="" srcset="">
                <br>
                <br>
                <h3>Return Received</h3>
                <br>
                #Date <b>{{ date('Y-m-d') }}</b>
            </div>  
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Product Name</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($order as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item['order_no'] }}</td>
                                <td>
                                    @foreach ($item['products'] as $product)
                                        {{ $product['product_name'] }} <br>
                                       Qty: {{ $product['pivot']['quantity'] }}
                                    @endforeach
                                </td>
                            </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3">
                            <strong>Zones: {{ implode(', ', $zones) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>


        <div class="information" style="position: absolute; bottom: 0;">
            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        {{-- &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved. --}}
                    </td>
                    <td align="right" style="width: 50%;">
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>
