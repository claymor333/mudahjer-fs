@props(['user', 'size' => 'w-10', 'showUsername' => false])

<div class="flex items-center space-x-2">
    <div class="avatar">
        <div class="{{ $size }} rounded-full ring ring-primary ring-offset-base-100 ring-offset-1">
            @if($user->player && $user->player->avatar)
                <img src="{{ asset('storage/' . $user->player->avatar) }}" alt="{{ $user->name }}" />
            @else
                <div class="bg-neutral text-neutral-content rounded-full {{ $size }} flex items-center justify-center">
                    <span class="font-bold {{ $size === 'w-10' ? 'text-sm' : ($size === 'w-16' ? 'text-lg' : 'text-xl') }}">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
            @endif
        </div>
    </div>
    
    @if($showUsername)
        <div class="flex flex-col">
            <span class="font-medium text-sm">{{ $user->name }}</span>
            @if($user->player)
                <span class="text-xs opacity-70">@{{ $user->player->username }}</span>
            @endif
        </div>
    @endif
</div>