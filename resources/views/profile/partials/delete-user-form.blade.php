<!-- Delete Account Card -->
<div class="card bg-base-100 shadow-xl card-border border-error">
    <div class="card-body">
        <h3 class="card-title text-lg mb-2 text-error">
            {{ __('Delete Account') }}
        </h3>

        <div class="alert alert-error mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
                <h3 class="font-bold">{{ __('Warning: This action cannot be undone') }}</h3>
                <div class="text-sm">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </div>
            </div>
        </div>

        <div class="card-actions justify-end">

            <button class="btn btn-error w-fit" onclick="confirm_deletion_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('Delete Account') }}
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<dialog id="confirm_deletion_modal" class="modal">
    <div class="modal-box w-11/12 max-w-lg">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h3 class="font-bold text-lg text-error mb-4">
                {{ __('Are you sure you want to delete your account?') }}
            </h3>

            <div class="alert alert-warning mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="text-sm">
                    {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                </div>
            </div>

            <div class="form-control w-full mb-6">
                <label class="label sr-only" for="password">
                    <span class="label-text">{{ __('Password') }}</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="input input-bordered w-full @error('password', 'userDeletion') input-error @enderror"
                    placeholder="{{ __('Enter your password') }}"
                    required
                />
                @error('password', 'userDeletion')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
                @enderror
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="confirm_deletion_modal.close()">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Confirm Deletion') }}
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', () => confirm_deletion_modal.showModal());
    </script>
@endif
