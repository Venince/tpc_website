{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
@php $user = $user ?? auth()->user(); @endphp

{{-- Separate form (forms cannot be nested): the "re-send" button below targets it via form="send-verification" --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

<x-admin.card title="Profile information" description="Update your name and email address.">
    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <x-admin.field label="Name" name="name" required>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="ui-input @error('name') is-invalid @enderror">
        </x-admin.field>

        <x-admin.field label="Email" name="email" required>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="ui-input @error('email') is-invalid @enderror">
        </x-admin.field>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <x-admin.alert type="warning" title="Your email address is unverified">
                <button form="send-verification" type="submit"
                        class="mt-0.5 rounded font-medium text-amber-900 underline underline-offset-2 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-600">
                    Re-send verification email
                </button>
            </x-admin.alert>
        @endif

        <div class="flex justify-end border-t border-ui-line pt-4">
            <x-admin.button type="submit" icon="check">Save changes</x-admin.button>
        </div>
    </form>
</x-admin.card>
