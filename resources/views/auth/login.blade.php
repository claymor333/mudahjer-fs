<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold gradient-text">Welcome Back!</h2>
        <p class="text-[var(--text-secondary)] mt-2">Sign in to continue your learning journey</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" class="rounded border-[var(--border-color)] text-[var(--accent)]" name="remember">
                <span class="ml-2 text-sm text-[var(--text-secondary)]">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm auth-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-4">
            <button type="submit" class="auth-button w-full">
                Log in
            </button>
            
            <p class="text-center text-sm text-[var(--text-secondary)]">
                Don't have an account? 
                <a href="{{ route('register') }}" class="auth-link">Sign up</a>
            </p>
        </div>
    </form>
</x-guest-layout>
