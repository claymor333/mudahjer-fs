<section>
    <p class="text-sm opacity-70 mb-6">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium">{{ __('Current Password') }}</legend>
            <input 
                type="password" 
                id="update_password_current_password" 
                name="current_password" 
                class="input input-bordered w-full @error('current_password', 'updatePassword') input-error @enderror" 
                autocomplete="current-password"
                required
            />
            @error('current_password', 'updatePassword')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <!-- New Password -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium">{{ __('New Password') }}</legend>
            <input 
                type="password" 
                id="update_password_password" 
                name="password" 
                class="input input-bordered w-full @error('password', 'updatePassword') input-error @enderror" 
                autocomplete="new-password"
                required
            />
            @error('password', 'updatePassword')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <!-- Confirm Password -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium">{{ __('Confirm Password') }}</legend>
            <input 
                type="password" 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                class="input input-bordered w-full @error('password_confirmation', 'updatePassword') input-error @enderror" 
                autocomplete="new-password"
                required
            />
            @error('password_confirmation', 'updatePassword')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <!-- Password Strength Indicator -->
        <fieldset class="fieldset w-full">
            <label class="label">
                <span class="label-text font-medium">{{ __('Password Strength') }}</span>
            </label>
            <div class="w-full bg-base-300 rounded-full h-2 overflow-hidden">
                <div id="password-strength-bar" class="h-full transition-all duration-300 bg-gray-300 w-0"></div>
            </div>
            <p id="password-strength-text" class="text-xs mt-2 text-gray-400">
                {{ __('Enter password') }}
            </p>
        </fieldset>

        <!-- Submit Button -->
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div 
                    class="alert alert-success p-3"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">{{ __('Password updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>

    <!-- Password Strength Script -->
    <script>
    const passwordInput = document.getElementById('update_password_password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    passwordInput.addEventListener('input', function(e) {
        const password = e.target.value;
        const strength = calculatePasswordStrength(password);
        updateStrengthUI(strength);
    });

    function calculatePasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        return Math.min(score, 4); // max score is 4 for the UI
    }

    function updateStrengthUI(score) {
        const widths = ['0%', '25%', '50%', '75%', '100%'];
        const textColors = ['text-gray-400', 'text-error', 'text-warning', 'text-info', 'text-success'];
        const bgColors = ['bg-gray-300', 'bg-error', 'bg-warning', 'bg-info', 'bg-success'];
        const messages = [
            '{{ __("Enter password") }}',
            '{{ __("Very Weak") }}',
            '{{ __("Weak") }}',
            '{{ __("Good") }}',
            '{{ __("Strong") }}'
        ];

        strengthBar.className = `h-full transition-all duration-300 rounded-full ${bgColors[score]}`;
        strengthBar.style.width = widths[score];
        strengthText.textContent = messages[score];
        strengthText.className = `text-xs mt-2 ${textColors[score]}`;
    }
</script>

</section>
