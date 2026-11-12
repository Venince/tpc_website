{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<x-admin.card title="Password" description="Use a long, random password to stay secure.">
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <x-admin.field label="Current password" name="current_password" for="update_password_current_password" bag="updatePassword" required>
            <x-admin.password-input name="current_password" id="update_password_current_password" autocomplete="current-password"
                class="{{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}" />
        </x-admin.field>

        <x-admin.field label="New password" name="password" for="update_password_password" bag="updatePassword" required hint="Minimum 8 characters.">
            <x-admin.password-input name="password" id="update_password_password" autocomplete="new-password"
                class="{{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}" />
        </x-admin.field>

        <x-admin.field label="Confirm new password" name="password_confirmation" for="update_password_password_confirmation" bag="updatePassword" required>
            <x-admin.password-input name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password" />
        </x-admin.field>

        <div class="flex justify-end border-t border-ui-line pt-4">
            <x-admin.button type="submit" icon="check">Update password</x-admin.button>
        </div>
    </form>
</x-admin.card>
