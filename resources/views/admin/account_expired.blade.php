<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <div class="max-w-sm rounded overflow-hidden shadow-lg" style="margin: 75px auto;">
        <img class="w-full" src="{{ asset('home/payment.png') }}" alt="Logixsaas">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">Account suspended</div>
            <p class="text-gray-700 text-base">
                Your account has been suspended. Please make payment or contact support for help.
            </p>
        </div>
        <div class="px-6 pt-4 pb-2">
            <span
                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"><a
                    href="{{ env('APP_URL') }}/upgrade?domain={{ $tenant }}" rel="noopener noreferrer">Go To
                    Make Payment</a></span>
        </div>
    </div>
</body>

</html>
