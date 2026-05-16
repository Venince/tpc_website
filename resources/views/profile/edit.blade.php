{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Page header --}}
            <div class="mb-2">
                <div class="flex items-center gap-2 mb-1">
                    <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
                    <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">My Profile</h1>
                </div>
                <p class="text-sm text-gray-500 pl-3">Manage your account information and security settings.</p>
            </div>

            {{-- Profile info --}}
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Password --}}
            <div id="update-password" class="scroll-mt-24 rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete account --}}
            <div class="rounded-2xl border border-red-100 bg-white shadow-sm overflow-hidden">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
