<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dispatch List</title>
    <style type="text/css">
        @page {
            margin: 0px;
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
            padding: .45rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
        }

        th {
            text-align: inherit;
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
            <table width="100%" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Region.</th>
                        <th>No of parcels</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item['zone_name'] }}</td>
                        <td>{{ $item['order_count'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="information" style="bottom: 0; width:100%">

            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 37%;font-size: 20px">
                    {{-- Total <b>{{ count($data) }} </b> --}}
                
                </td>
{{-- 
                    @foreach ($count as $key => $s)
                        <td align="left" style="width: 37%;font-size: 20px">
                            {{ $key }} <b>{{ $s }} </b>
                        
                        </td>
                    @endforeach --}}

                </tr>

            </table>
            

            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 37%;">
                        Dispatched By _________________________
                       
                    </td>
                    <td align="center" style="width: 37%;">
                        Signature ___________________________
                    </td>
                    <td align="right" style="width: 26%;">
                        {{-- Date _________________ --}}
                        Date <b><u>{{ date('D d M Y') }}</u></b>
                    </td>
                </tr>

            </table>


            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 37%;">
                        {{-- Agent name <b><u> {{ $rider_name }} </u></b> --}}
                       
                    </td>
                    <td align="center" style="width: 37%;">
                        {{-- County <b><u> {{ $zone_name }} </u></b> --}}
                    </td>
                    <td align="right" style="width: 26%;">
                        Signature ___________________________
                        {{-- Date <b><u>{{ date('D d M Y') }}</u></b> --}}
                    </td>
                </tr>

            </table>

            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved.
                    </td>
                    <td align="right" style="width: 50%;">
                       <a href="{{ $company->website }}" style="color: #212529" target="_blank">{{ $company->website }}</a>
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>
