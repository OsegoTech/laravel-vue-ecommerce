<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
            Login to your account
        </h2>

        <p class="text-center text-gray-500 mb-6">
            or
            <a
                href="{{route('register')}}"
                class="text-sm text-purple-700 hover:text-purple-600"
            >
                create new account
            </a>
        </p>

        <!-- Email Address -->
        <div >
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex justify-between items-center mb-5">
            <div class="block mt-4">
                <label for="remember_me" class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500">
                    <input class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500" id="remember_me" type="checkbox" >
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>



        <div>

            <button
                class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full"
            >
                Login
            </button>
        </div>
    </form>

</x-app-layout>
