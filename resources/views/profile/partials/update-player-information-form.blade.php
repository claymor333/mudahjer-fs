<section>
	<p class="text-sm opacity-70 mb-6">
		{{ __("Update your gaming profile and avatar.") }}
	</p>

	<form method="post" action="{{ route('player.update') }}" enctype="multipart/form-data" class="space-y-6">
		@csrf
		@method('patch')

		<!-- Avatar Display -->
		<fieldset class="fieldset w-full">
			<legend class="fieldset-legend font-medium">{{ __('Current Avatar') }}</legend>
			<div class="flex items-center space-x-4 mt-2">
				<div class="avatar">
					<div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
						@if($user->player && $user->player->avatar)
						<img src="{{ asset('storage/' . $user->player->avatar) }}" alt="{{ __('Current Avatar') }}" />
						@else
						<div
							class="bg-neutral text-neutral-content rounded-full w-24 h-24 flex items-center justify-center">
							<span class="text-xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
						</div>
						@endif
					</div>
				</div>
				<div class="text-sm">
					@if($user->player && $user->player->avatar)
					<p class="text-success">{{ __('You have an avatar set') }}</p>
					<p class="text-base-content/70">{{ __('Upload a new image to replace it') }}</p>
					@else
					<p class="text-warning">{{ __('No avatar set') }}</p>
					<p class="text-base-content/70">{{ __('Upload an image to set your avatar') }}</p>
					@endif
				</div>
			</div>
		</fieldset>

		<!-- Username -->
		<fieldset class="fieldset w-full">
			<legend class="fieldset-legend font-medium">{{ __('Username') }}</legend>
			<input type="text" id="username" name="username"
				class="input input-bordered w-full @error('username') input-error @enderror"
				value="{{ old('username', $user->player->username ?? '') }}" required autocomplete="username"
				placeholder="{{ __('Choose a unique username') }}" />
			@error('username')
			<p class="label text-error mt-1">{{ $message }}</p>
			@enderror
		</fieldset>

		<!-- Avatar Upload -->
		<fieldset class="fieldset w-full">
			<legend class="fieldset-legend font-medium flex items-center justify-between">
				{{ __('Avatar') }}
				<span class="label-text-alt text-xs">{{ __('JPG, PNG, GIF (max 2MB)') }}</span>
			</legend>
			<input type="file" id="avatar" name="avatar"
				class="file-input file-input-bordered w-full @error('avatar') file-input-error @enderror"
				accept="image/jpeg,image/png,image/gif" />
			@error('avatar')
			<p class="label text-error mt-1">{{ $message }}</p>
			@enderror
			<p class="label text-base-content/60 mt-1">{{ __('Leave empty to keep current avatar') }}</p>
		</fieldset>

		<!-- Avatar Preview -->
		<fieldset class="fieldset w-full" id="avatar-preview-container" style="display: none;">
			<legend class="fieldset-legend font-medium">{{ __('New Avatar Preview') }}</legend>
			<div class="flex items-center space-x-4 mt-2">
				<div class="avatar">
					<div class="w-24 rounded-full ring ring-secondary ring-offset-base-100 ring-offset-2">
						<img id="avatar-preview" src="#" alt="{{ __('Avatar Preview') }}" />
					</div>
				</div>
				<div class="text-sm">
					<p class="text-info">{{ __('This will be your new avatar') }}</p>
					<button type="button" class="btn btn-sm btn-ghost" onclick="clearPreview()">
						{{ __('Clear preview') }}
					</button>
				</div>
			</div>
		</fieldset>

		<!-- Submit -->
		<div class="flex items-center gap-4">
			<button type="submit" class="btn btn-primary">
				{{ __('Update Player Info') }}
			</button>

			@if (session('status') === 'player-updated')
			<div class="alert alert-success p-3" x-data="{ show: true }" x-show="show" x-transition
				x-init="setTimeout(() => show = false, 3000)">
				<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
					viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
				<span class="text-sm">{{ __('Player information updated successfully!') }}</span>
			</div>
			@endif
		</div>
	</form>

	<script>
		// Avatar preview functionality
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('avatar-preview-container');
            const previewImg = document.getElementById('avatar-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });

        // Clear preview function
        function clearPreview() {
            const fileInput = document.getElementById('avatar');
            const previewContainer = document.getElementById('avatar-preview-container');
            
            fileInput.value = '';
            previewContainer.style.display = 'none';
        }
	</script>
</section>