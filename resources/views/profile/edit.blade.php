<x-app-layout>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <section class="rounded-3xl border border-tpc-primary/10 bg-white/70 shadow-sm backdrop-blur p-4 sm:p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <section id="update-password" class="scroll-mt-24 rounded-3xl border border-tpc-primary/10 bg-white/70 shadow-sm backdrop-blur p-4 sm:p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section class="rounded-3xl border border-tpc-primary/10 bg-white/70 shadow-sm backdrop-blur p-4 sm:p-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
