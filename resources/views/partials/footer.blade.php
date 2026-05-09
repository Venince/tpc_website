@php
    $siteName = \App\Support\Settings::get('site_name', 'Talibon Polytechnic College');
    $address  = \App\Support\Settings::get('address', 'Talibon, Bohol, Philippines');
    $email    = \App\Support\Settings::get('email', 'info@tpc.edu.ph');
    $phone    = \App\Support\Settings::get('phone', '+63 000 000 0000');
@endphp

<footer id="tpc-footer">

    {{-- Main footer body --}}
    <div class="bg-tpc-secondary text-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo"
                             class="h-12 w-12 rounded bg-white p-1.5 shrink-0">
                        <div>
                            <p class="text-lg font-bold text-white leading-tight">{{ $siteName }}</p>
                            <p class="mt-1.5 text-sm text-white/70 leading-relaxed max-w-sm">
                                Committed to providing accessible, high-quality education through academic
                                excellence, innovation, and service to the community.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div>
                    <p class="text-xs font-bold text-tpc-accent uppercase tracking-widest mb-4">Contact</p>
                    <ul class="space-y-3 text-sm text-white/75">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 shrink-0 text-tpc-accent">📍</span>
                            <span>{{ $address }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="shrink-0 text-tpc-accent">☎</span>
                            <span>{{ $phone }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="shrink-0 text-tpc-accent">✉</span>
                            <a href="mailto:{{ $email }}"
                               class="text-tpc-accent hover:text-white transition font-medium">
                                {{ $email }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Quick links --}}
                <div>
                    <p class="text-xs font-bold text-tpc-accent uppercase tracking-widest mb-4">Quick Links</p>
                    <ul class="space-y-2 text-sm">
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('home') }}">Home</a></li>
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('home') }}#about">About</a></li>
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('academics') }}">Academics</a></li>
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('admission') }}">Admission</a></li>
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('news.index') }}">News</a></li>
                        <li><a class="text-white/70 hover:text-white transition" href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright strip --}}
    <div class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-white/80">
                © {{ now()->year }} {{ $siteName }}. All rights reserved.
            </p>
            <p class="text-xs text-white/60">SecrIT Solutions</p>
        </div>
    </div>
</footer>
