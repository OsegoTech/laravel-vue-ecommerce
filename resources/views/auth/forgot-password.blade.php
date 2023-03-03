<x-app-layout>
    <div class="mb-2 mt-4 text-sm text-gray-600">
        <h2 class="text-2xl font-semibold text-center mb-5">
            Enter your Email to reset password
        </h2>

        <p class="text-center text-gray-500 mb-2">
            or
            <a
                href="{{ route('login') }}"
                class="text-purple-600 hover:text-purple-500"
            >
                login with existing account
            </a>
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="w-[400px] mx-auto p-6 mt-3">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-app-layout>
