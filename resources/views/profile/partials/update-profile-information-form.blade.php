<section>
    {{-- <div class="flex items-center space-x-4 mb-6 p-4 bg-base-200 dark:bg-base-100 rounded-lg">
        <div class="avatar">
            <div class="w-16 rounded-full">
                @if($user->player && $user->player->avatar)
                    <img src="{{ asset('storage/' . $user->player->avatar) }}" alt="{{ $user->name }}" />
                @else
                    <div class="bg-neutral text-neutral-content rounded-full w-16 h-16 flex items-center justify-center">
                        <span class="text-lg font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div>
            <p class="text-sm font-medium">{{ __('Editing profile for') }}</p>
            <p class="font-bold">{{ $user->name }}</p>
            @if($user->player)
                <p class="text-sm opacity-70">{!! "@" . $user->player->username !!}</p>
            @endif
        </div>
    </div> --}}

    <p class="text-sm opacity-70 mb-6">
        {{ __("Update your account's profile information and email address.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium">{{ __('Name') }}</legend>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="input input-bordered w-full @error('name') input-error @enderror" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
            />
            @error('name')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <!-- Email -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium">{{ __('Email') }}</legend>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="input input-bordered w-full @error('email') input-error @enderror" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
            />
            @error('email')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div>
                        <p class="text-sm">
                            {{ __('Your email address is unverified.') }}
                        </p>
                        <button 
                            form="send-verification" 
                            class="btn btn-link btn-sm p-0 h-auto min-h-0 text-left justify-start underline"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm">{{ __('A new verification link has been sent to your email address.') }}</span>
                    </div>
                @endif
            @endif
        </fieldset>

        <!-- Phone Number -->
        <fieldset class="fieldset w-full">
            <legend class="fieldset-legend font-medium flex justify-between">
                {{ __('Phone Number') }}
            </legend>
            <input 
                type="tel" 
                id="phone_number" 
                name="phone_number" 
                class="input input-bordered w-full @error('phone_number') input-error @enderror" 
                value="{{ old('phone_number', $user->phone_number) }}" 
                autocomplete="tel"
                placeholder="+1 (555) 123-4567"
            />
            @error('phone_number')
                <p class="label text-error mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <!-- Submit Button -->
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
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
                    <span class="text-sm">{{ __('Profile updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
