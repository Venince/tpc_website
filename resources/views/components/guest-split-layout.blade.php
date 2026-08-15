<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Talibon Polytechnic College') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-neo-bg text-neo-ink antialiased">
    <div class="min-h-screen lg:flex">

        {{-- ── Left: campus photo panel (desktop only) ── --}}
        <div class="relative hidden lg:flex lg:w-1/2 xl:w-3/5 overflow-hidden">
            <img src="{{ asset('images/school-bg.jpg') }}"
                 alt="Talibon Polytechnic College campus"
                 class="absolute inset-0 h-full w-full object-cover">            <div class="relative z-10 flex w-full flex-col justify-between p-10 xl:p-14">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 w-fit rounded-2xl bg-black/45 backdrop-blur-sm px-4 py-2.5">
                    <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo"
                         class="h-12 w-auto object-contain drop-shadow-md">
                    <div>
                        <div class="text-lg font-bold leading-tight text-white">Talibon Polytechnic College</div>
                        <div class="text-xs font-medium uppercase tracking-widest text-white/70">Official Website</div>
                    </div>
                </a>

                <div class="max-w-md rounded-2xl bg-black/45 backdrop-blur-sm px-5 py-4 w-fit">
                    <p class="text-2xl xl:text-3xl font-semibold leading-snug text-white mb-3">
                        Committed to providing accessible, high-quality education.
                    </p>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Manage announcements, admissions, and services for the TPC community — all in one place.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Right: form panel ── --}}
        <div class="flex flex-1 flex-col items-center justify-center px-4 py-12 lg:w-1/2 xl:w-2/5">
            <div class="w-full max-w-md">

                {{-- Mobile-only header (image panel is hidden below lg) --}}
                <div class="flex flex-col items-center text-center lg:hidden mb-6">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/TPC-Logo.png') }}"
                            alt="Talibon Polytechnic College Logo"
                            class="h-16 w-auto object-contain">
                    </a>
                    <div class="mt-3">
                        <div class="text-base font-semibold text-neo-ink">Talibon Polytechnic College</div>
                        <div class="text-xs font-medium uppercase tracking-widest text-neo-ink/45">Admin Portal</div>
                    </div>
                </div>

                <p class="hidden lg:block mb-3 text-xs font-bold uppercase tracking-widest text-tpc-primary/70">
                    Admin Portal
                </p>

                <div class="rounded-[28px] bg-neo-surface shadow-neo p-6 sm:p-8">
                    {{ $slot }}
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary transition">
                        ← Back to Public Website
                    </a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
