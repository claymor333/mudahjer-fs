<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold gradient-text">Create Account</h2>
        <p class="text-[var(--text-secondary)] mt-2">Start your sign language learning journey today</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Name</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-[var(--text-primary)] mb-1">Confirm Password</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-4">
            <button type="submit" class="auth-button w-full">
                Register
            </button>
            
            <p class="text-center text-sm text-[var(--text-secondary)]">
                Already have an account? 
                <a href="{{ route('login') }}" class="auth-link">Sign in</a>
            </p>
        </div>
    </form>
</x-guest-layout>
