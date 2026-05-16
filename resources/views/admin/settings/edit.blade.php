@extends('admin.layout', ['title' => 'Settings'])

@section('content')

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-2 mb-1">
        <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Site Settings</h1>
    </div>
    <p class="text-sm text-gray-500 pl-3">General website information shown across public pages.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-5">
    @csrf

    {{-- ── Contact & Identity ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center shrink-0">
                <svg class="h-4 w-4 text-tpc-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253M3 12c0 .778.099 1.533.284 2.253"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-800">Site Identity</h2>
                <p class="text-xs text-gray-400 mt-0.5">Displayed in the browser tab, header, and footer</p>
            </div>
        </div>
        <div class="p-5">
            <div>
                <label for="site_name" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                    Site Name
                </label>
                <input id="site_name" type="text" name="site_name"
                       value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                       placeholder="e.g. Technological Pioneers College"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                @error('site_name')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ── Location & Contact ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center shrink-0">
                <svg class="h-4 w-4 text-tpc-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-800">Location & Contact</h2>
                <p class="text-xs text-gray-400 mt-0.5">Shown in the footer and contact page</p>
            </div>
        </div>
        <div class="p-5 space-y-4">

            {{-- Address --}}
            <div>
                <label for="address" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                    Address
                </label>
                <input id="address" type="text" name="address"
                       value="{{ old('address', $settings['address'] ?? '') }}"
                       placeholder="e.g. 123 University Ave, Cebu City"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                @error('address')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Email + Phone side-by-side on sm+ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Email
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email"
                               value="{{ old('email', $settings['email'] ?? '') }}"
                               placeholder="info@school.edu.ph"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Phone
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </span>
                        <input id="phone" type="text" name="phone"
                               value="{{ old('phone', $settings['phone'] ?? '') }}"
                               placeholder="+63 32 000 0000"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                    </div>
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>
    </div>

    {{-- ── Actions ── --}}
    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-3 pt-1">
        <button type="reset"
                class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all">
            Reset
        </button>
        <button type="submit"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-xl bg-tpc-primary text-white text-sm font-bold hover:bg-tpc-secondary active:scale-95 transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            Save Settings
        </button>
    </div>

</form>

@endsection
