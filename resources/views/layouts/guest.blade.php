<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Talibon Polytechnic College') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-neo-bg text-neo-ink antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="flex flex-col items-center text-center">
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

            <div class="mt-6 rounded-[28px] bg-neo-surface shadow-neo p-6 sm:p-8">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary transition">
                    ← Back to Public Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
