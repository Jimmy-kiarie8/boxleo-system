<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

</head>

<body>
    {{-- {{ dd($user) }} --}}
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-gray-50 shadow" style="text-align: center;width: 50%;margin: auto;">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between"
            style="padding-bottom: 0;">
            <div class="mt-12 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md">
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        <span class="block">Another user is loged in with this credentials.</span>
                        <span class="block text-indigo-600">Delete existing user session.</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">

            <div class="mt-12 flex lg:mt-0 lg:flex-shrink-0" style="width: 20%;margin: auto;">
                <div class="inline-flex rounded-md">

                    <div>
                        <form action="{{ route('delete_session') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $user->email }}" name="email">
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Delete session
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
