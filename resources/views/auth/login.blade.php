<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="lg:flex">
        <div class="lg:w-1/2 xl:max-w-screen-sm">
            <div class="py-12 bg-indigo-100 lg:bg-white flex justify-center lg:justify-start lg:px-12">
                <div class="cursor-pointer flex items-center">
                    <div>
                        <img class="w-10 text-indigo-500" src="https://cdn-icons-png.flaticon.com/512/931/931949.png" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 225 225" style="enable-background:new 0 0 225 225;" xml:space="preserve" />
                    </div>
                    <div class="text-2xl tracking-wide ml-2 font-semibold">Alcohol Blog Post</div>
                </div>
            </div>
            <div class="mt-10 px-12 sm:px-24 md:px-48 lg:px-12 lg:mt-16 xl:px-24 xl:max-w-2xl">
                <h2 class="text-center text-4xl text-indigo-900 font-display font-semibold lg:text-left xl:text-5xl
                    xl:text-bold">Log in</h2>
                <div class="mt-12">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <x-label for="email" :value="__('Email Address')" class="text-sm font-bold text-gray-700 tracking-wide" />
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus>
                        </div>
                        <div class="mt-8">
                            <div class="flex justify-between items-center">
                                <x-label for="password" :value="__('Password')" class="text-sm font-bold text-gray-700 tracking-wide" />
                                <div>
                                    <a class="text-xs font-display font-semibold text-indigo-600 hover:text-indigo-800
                                        cursor-pointer" href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                </div>
                            </div>
                            <input class="w-full text-lg py-2 border-b border-gray-300 focus:outline-none focus:border-indigo-500" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">
                        </div>

                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="mt-10">
                            <button class="bg-indigo-500 text-gray-100 p-4 w-full rounded-full tracking-wide
                                font-semibold font-display focus:outline-none focus:shadow-outline hover:bg-indigo-600
                                shadow-lg" type="submit">
                                Log In
                            </button>
                        </div>
                    </form>
                    <div class="mt-12 text-sm font-display font-semibold text-gray-700 text-center">
                        Don't have an account ? <a class="cursor-pointer text-indigo-600 hover:text-indigo-800" href="{{ route('register') }}">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex items-center justify-center bg-indigo-100 flex-1 h-screen" style="background-image: url(https://images.unsplash.com/photo-1508253730651-e5ace80a7025?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80); background-size: cover;">
        </div>
    </div>
</x-guest-layout>