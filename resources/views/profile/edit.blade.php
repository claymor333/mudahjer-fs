<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- User Overview Card (Full width) -->
            <div class="flex items-center space-x-4">
    <div class="avatar">
        <div class="w-20 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
            @if($user->player && $user->player->avatar)
                <img src="{{ asset('storage/' . $user->player->avatar) }}" alt="{{ $user->name }}" />
            @else
                <div class="bg-neutral text-neutral-content rounded-full w-20 h-20 flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="flex-1">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
            @if($user->player)
                <span class="badge badge-accent badge-lg text-base">
                    LVL {{ $user->player->level }}
                </span>
            @endif
        </div>

        <p class="text-base-content/70">{{ $user->email }}</p>

        @php
            $level = $user->player->level ?? 1;
            $exp = $user->player->exp ?? 0;
            $expToNext = $level * 100;
            $progressPercent = min(100, round(($exp / $expToNext) * 100, 1));
        @endphp

        @if($user->player)
            <div class="mt-2">
                <div class="text-sm font-medium text-base-content/60 mb-1">
                    EXP: {{ $user->player->exp }} / {{ $expToNext }}
                </div>
                <progress 
                    class="progress progress-primary w-full h-3" 
                    value="{{ $exp }}" 
                    max="{{ $expToNext }}">
                </progress>
            </div>

            <div class="badge badge-primary badge-outline mt-2">
                {{ '@' . $user->player->username }}
            </div>
        @endif
    </div>
</div>


            <!-- 2-Column Grid for Form Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="card card-border border-base-300 dark:bg-base-200 bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">{{ __('Personal Information') }}</h3>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Player Information -->
                <div class="card card-border border-base-300 dark:bg-base-200 bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">{{ __('Player Information') }}</h3>
                        @include('profile.partials.update-player-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card card-border border-base-300 dark:bg-base-200 bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4">{{ __('Update Password') }}</h3>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div>
                    {{-- <h3 class="card-title text-lg mb-4 text-error">{{ __('Delete Account') }}</h3> --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
