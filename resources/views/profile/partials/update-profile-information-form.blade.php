<section>
    <header class="pb-2">
        <h2 class="text-lg font-semibold text-tpc-ink">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-tpc-ink/70">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-tpc-ink/80 font-medium" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                       focus:border-tpc-primary focus:ring-tpc-primary/25"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-tpc-ink/80 font-medium" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                       focus:border-tpc-primary focus:ring-tpc-primary/25"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 rounded-2xl border border-tpc-primary/15 bg-white/70 p-4">
                    <p class="text-sm text-tpc-ink/80">
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <button
                        form="send-verification"
                        class="mt-2 inline-flex items-center text-sm font-semibold text-tpc-primary
                               hover:text-tpc-secondary focus:outline-none focus:ring-2 focus:ring-tpc-primary/25 rounded-lg"
                    >
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 text-sm font-medium text-tpc-primary">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <x-primary-button
                class="w-full sm:w-auto justify-center rounded-xl bg-tpc-primary px-5 py-3 text-sm font-semibold text-white
                       shadow-sm hover:bg-tpc-secondary focus:ring-2 focus:ring-tpc-primary/25"
            >
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-tpc-ink/60"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
