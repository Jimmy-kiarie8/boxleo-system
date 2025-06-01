<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Set Up Two-Factor Authentication</h2>
        <p class="mb-4">Scan the QR code below with your 2FA app and enter the generated code to enable 2FA.</p>

        <!-- QR Code -->
        <div class="flex justify-center mb-4">
            {!! $qrCodeSvg !!}
        </div>

        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="2fa_code" class="block text-gray-700">2FA Code</label>
                <input type="text" name="2fa_code" id="2fa_code" class="mt-1 p-2 border rounded w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Enable 2FA</button>
        </form>
    </div>
</body>
</html>
