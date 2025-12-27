<x-guest-layout>
    <x-slot name="title">Login</x-slot>

    <header class="mb-8 text-center">
        <h2 class="mb-2 font-poppins text-4xl font-semibold text-bgray-900 dark:text-white">
            Masuk ke Sistem
        </h2>
        <p class="text-base font-medium text-bgray-600 dark:text-bgray-50">
            Kelola kendaraan dengan mudah dan efisien
        </p>
    </header>

    <!-- Google SSO Button -->
    <div class="mb-5">
        <a href="{{ route('auth.google') }}" class="flex w-full items-center justify-center gap-x-2 rounded-lg border border-bgray-300 px-6 py-4 text-base font-medium text-bgray-900 transition-all hover:bg-bgray-50 dark:border-darkblack-400 dark:text-white dark:hover:bg-darkblack-400">
            <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.8758 11.2137C20.8758 10.4224 20.8103 9.84485 20.6685 9.24597H11.4473V12.8179H16.8599C16.7508 13.7055 16.1615 15.0424 14.852 15.9406L14.8336 16.0602L17.7492 18.2737L17.9512 18.2935C19.8063 16.6144 20.8758 14.144 20.8758 11.2137Z" fill="#4285F4"/>
                <path d="M11.4467 20.625C14.0984 20.625 16.3245 19.7694 17.9506 18.2936L14.8514 15.9408C14.022 16.5076 12.9089 16.9033 11.4467 16.9033C8.84946 16.9033 6.64512 15.2243 5.85933 12.9036L5.74415 12.9131L2.7125 15.2125L2.67285 15.3205C4.28791 18.4647 7.60536 20.625 11.4467 20.625Z" fill="#34A853"/>
                <path d="M5.86006 12.9036C5.65272 12.3047 5.53273 11.663 5.53273 11C5.53273 10.3369 5.65272 9.69524 5.84915 9.09636L5.84366 8.96881L2.774 6.63257L2.67357 6.67938C2.00792 7.98412 1.62598 9.44929 1.62598 11C1.62598 12.5507 2.00792 14.0158 2.67357 15.3205L5.86006 12.9036Z" fill="#FBBC05"/>
                <path d="M11.4467 5.09664C13.2909 5.09664 14.5349 5.87733 15.2443 6.52974L18.0161 3.8775C16.3138 2.32681 14.0985 1.375 11.4467 1.375C7.60539 1.375 4.28792 3.53526 2.67285 6.6794L5.84844 9.09638C6.64514 6.77569 8.84949 5.09664 11.4467 5.09664Z" fill="#EB4335"/>
            </svg>
            <span>Masuk dengan Google</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="relative mb-5 mt-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-bgray-300 dark:border-darkblack-400"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-white px-2 text-base text-bgray-600 dark:bg-darkblack-500">Atau masuk dengan</span>
        </div>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-error-200 @enderror" placeholder="Masukkan email" required autofocus>
            @error('email')
                <p class="mt-1 text-sm text-error-200">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="form-label">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" class="form-input @error('password') border-error-200 @enderror" placeholder="Masukkan password" required>
                <button type="button" onclick="togglePassword()" class="absolute bottom-4 right-4 top-4">
                    <svg id="eye-icon" width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-error-200">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="mb-7 flex justify-between">
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="remember" name="remember" class="h-5 w-5 rounded-full border border-bgray-300 text-success-300 focus:ring-transparent dark:bg-darkblack-500" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="text-base font-semibold text-bgray-900 dark:text-white">Ingat saya</label>
            </div>
            <div>
                <a href="{{ route('password.request') }}" class="text-base font-semibold text-success-300 underline">Lupa Password?</a>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-success-300 py-3.5 font-bold text-white transition-all hover:bg-success-400">
            Masuk
        </button>
    </form>

    <!-- Rate Limit Warning -->
    @if(session('throttle'))
        <div class="mt-4 rounded-lg bg-error-50 p-4 text-center text-error-300">
            {{ session('throttle') }}
        </div>
    @endif

    <!-- Footer Links -->
    <nav class="flex flex-wrap items-center justify-center gap-x-11 pt-24">
        <a href="#" class="text-sm text-bgray-700 dark:text-white">Syarat & Ketentuan</a>
        <a href="#" class="text-sm text-bgray-700 dark:text-white">Kebijakan Privasi</a>
        <a href="#" class="text-sm text-bgray-700 dark:text-white">Bantuan</a>
    </nav>

    <p class="mt-6 text-center text-sm text-bgray-600 dark:text-white">
        &copy; {{ date('Y') }} Keuskupan Agung Semarang. All Rights Reserved.
    </p>

    @push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.opacity = '0.5';
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.opacity = '1';
            }
        }
    </script>
    @endpush
</x-guest-layout>
