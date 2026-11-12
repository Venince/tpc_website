{{-- resources/views/profile/partials/delete-user-form.blade.php --}}
<x-admin.card title="Delete account" description="Permanently remove your account and all of its data." class="border-red-200"
              x-data="{ open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-ui-muted">Once your account is deleted, all of its data is permanently removed and cannot be recovered.</p>
        <button type="button" class="ui-btn ui-btn-danger shrink-0" @click="open = true" x-show="!open" :aria-expanded="open" aria-controls="delete-confirm">
            <x-admin.icon name="trash" class="h-4 w-4" /> Delete account
        </button>
    </div>

    <div id="delete-confirm" x-show="open" x-cloak x-transition class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">
        <p class="text-sm font-semibold text-red-800">Are you sure you want to delete your account?</p>
        <p class="mt-1 text-sm text-red-700">Enter your password to confirm. This cannot be undone.</p>

        <form method="post" action="{{ route('profile.destroy') }}" class="mt-4 space-y-4">
            @csrf
            @method('delete')

            <x-admin.field label="Password" name="password" for="del-password" bag="userDeletion" required>
                <x-admin.password-input name="password" id="del-password" placeholder="Enter your password" autocomplete="current-password"
                    class="{{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}" />
            </x-admin.field>

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" class="ui-btn ui-btn-secondary" @click="open = false">Cancel</button>
                <button type="submit" class="ui-btn ui-btn-danger-solid">Yes, delete my account</button>
            </div>
        </form>
    </div>
</x-admin.card>
