@extends('admin.layout', ['title' => 'Edit Admin/Staff'])

@section('content')

{{-- Back link + header --}}
<div class="mb-7">
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400 hover:text-tpc-primary transition mb-3">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Back to Admin/Staff
    </a>
    <div class="flex items-center gap-2">
        <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Edit Admin/Staff</h1>
    </div>
</div>

<div class="max-w-2xl space-y-5"
     x-data="{ showPass: false, showConfirm: false, changePassword: false }">

    {{-- ── User identity card ── --}}
    <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="h-12 w-12 rounded-xl bg-tpc-primary/10 flex items-center justify-center shrink-0">
            <span class="text-lg font-extrabold text-tpc-primary">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
        </div>
        <div class="min-w-0">
            <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
        </div>
        <span class="ml-auto shrink-0 inline-block bg-tpc-primary/10 text-tpc-primary text-xs font-bold px-2.5 py-1 rounded-full">
            Admin/Staff
        </span>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
        @csrf @method('PUT')

        {{-- ── Account Info ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-800">Account Information</h2>
            </div>
            <div class="p-5 space-y-4">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Password reset ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Reset Password</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Optional — leave unchanged to keep current password</p>
                </div>
                <button type="button" @click="changePassword = !changePassword"
                        :class="changePassword ? 'bg-tpc-primary/10 text-tpc-primary' : 'bg-gray-100 text-gray-500'"
                        class="text-xs font-bold px-3 py-1.5 rounded-lg transition">
                    <span x-text="changePassword ? 'Cancel' : 'Change'"></span>
                </button>
            </div>

            <div x-show="changePassword"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="p-5 space-y-4">

                {{-- New password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">New Password</label>
                    <div class="relative">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password"
                               placeholder="Min. 8 characters"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <svg x-show="!showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm new password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Confirm New Password</label>
                    <div class="relative">
                        <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                               placeholder="Re-enter new password"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <svg x-show="!showConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="showConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            {{-- Collapsed state hint --}}
            <div x-show="!changePassword" class="px-5 py-3.5">
                <p class="text-xs text-gray-400 flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    Password is hidden — click "Change" to update it
                </p>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-3 pt-1">
            <a href="{{ route('admin.users.index') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-xl bg-tpc-primary text-white text-sm font-bold hover:bg-tpc-secondary active:scale-95 transition-all shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Save Changes
            </button>
        </div>

    </form>

    {{-- ── Danger Zone ── --}}
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden"
         x-data="{ confirm: false }">
        <div class="px-5 py-4 border-b border-red-100">
            <h2 class="text-sm font-bold text-red-600">Danger Zone</h2>
        </div>
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Delete this account</p>
                    <p class="text-xs text-gray-400 mt-0.5">Permanently removes <strong>{{ $user->name }}</strong>'s access. This cannot be undone.</p>
                </div>
                <button type="button" @click="confirm = true"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 text-sm font-bold text-red-600 hover:bg-red-50 active:scale-95 transition-all shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Account
                </button>
            </div>

            {{-- Inline confirm --}}
            <div x-show="confirm" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="text-sm font-semibold text-red-700 mb-3">
                    Delete <strong>{{ $user->name }}</strong>? This will permanently remove their account and access.
                </p>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 active:scale-95 transition-all">
                            Yes, delete account
                        </button>
                    </form>
                    <button type="button" @click="confirm = false"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-white active:scale-95 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
