<x-guest-layout>
    <div class="lg:flex">


        <div class="hidden lg:flex items-center justify-center bg-indigo-100 flex-1 h-screen">
            <div class="max-w-xs transform duration-200 hover:scale-110 cursor-pointer">
                <img src="{{ asset('images/login.png') }}"
                    style="max-width: 650px;margin-left: -100px;border-radius:10px;" />
            </div>
        </div>
        <div class="lg:w-1/2 xl:max-w-screen-sm">
            <div class="bg-indigo-100 lg:bg-white flex justify-center lg:justify-start lg:px-12">
                <div class="cursor-pointer flex items-center">
                    <a href="/">
                        <img src="{{ asset('home/logo.png') }}" style="width: 30%;" />
                    </a>
                    {{-- <div class="text-2xl text-indigo-800 tracking-wide ml-2 font-semibold">blockify</div> --}}
                </div>
            </div>

            @if (Session::has('message'))
                <div class="form-result alert alert-success" style="display: block;margin-bottom: 20px;color: #0f5132;background-color: #d1e7dd;border-color: #badbcc;background-color: #d1e7dd;border-color: #badbcc;padding: 0.75rem 1.25rem;">
                    <div>{{ Session::get('message');  }}</div>
                </div>
            @endif
            <div class="mt-3 px-12 sm:px-24 md:px-48 lg:px-12 lg:mt-3 xl:px-24 xl:max-w-2xl">
                <h6 style="color: #196ae3; font-weight: bold; font-size: 23px">Start your full-featured Free Trial for
                    10 days</h6>
                <div class="mt-12">
                    <form method="POST" action="{{ route('signup') }}">
                        @csrf
                        <div>
                            <div class="text-sm font-bold text-gray-700 tracking-wide">Company name</div>
                            <input
                                class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500"
                                id="company" type="text" placeholder="ABC" autofocus required name="company">
                            @error('company')
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: #196ae3;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-700 tracking-wide">Company Website</div>
                            <input
                                class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500"
                                id="website" type="text" placeholder="logixsaas.com" autofocus required
                                name="website">
                            @error('website')
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: #196ae3;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-700 tracking-wide">Country</div>
                            <input
                                class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500"
                                id="country" type="text" placeholder="UK" autofocus required name="country">
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: #196ae3;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-700 tracking-wide">phone</div>
                            <input
                                class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500"
                                id="phone" type="text" placeholder="+44..." autofocus required name="phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: #196ae3;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-700 tracking-wide">Email Address</div>
                            <input
                                class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500"
                                id="email" type="email" placeholder="mike@gmail.com" autocomplete="email"
                                autofocus required name="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong style="color: #196ae3;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-10">
                            <button
                                class="bg-indigo-500 text-gray-100 p-4 w-full rounded-full tracking-wide
                                font-semibold font-display focus:outline-none focus:shadow-outline hover:bg-indigo-600
                                shadow-lg"
                                style="background: #196ae3;" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
