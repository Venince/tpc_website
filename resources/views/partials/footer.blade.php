@php
    $siteName = \App\Support\Settings::get('site_name', 'Talibon Polytechnic College');
    $address  = \App\Support\Settings::get('address', 'Talibon, Bohol, Philippines');
    $email    = \App\Support\Settings::get('email', 'info@tpc.edu.ph');
    $phone    = \App\Support\Settings::get('phone', '+63 000 000 0000');
@endphp

<footer id="tpc-footer">

    {{-- Main footer body --}}
    <div class="bg-tpc-secondary text-white">
        <div class="max-w-7xl mx-auto px-5 py-10 sm:py-12">
            <div class="flex flex-col items-center gap-8 sm:grid sm:grid-cols-2 sm:items-start sm:gap-10 lg:grid-cols-3">

                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('images/TPC-Logo.png') }}" alt="TPC Logo"
                             class="h-20 w-20 rounded shrink-0">
                        <div>
                            <p class="text-lg font-bold text-white leading-tight">{{ $siteName }}</p>
                            <p class="mt-1.5 text-sm text-white/70 leading-relaxed max-w-sm">
                                Committed to providing accessible, high-quality education through academic
                                excellence, innovation, and service to the community.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Divider (mobile only) --}}
                <div class="w-16 h-px bg-white/20 sm:hidden"></div>

                {{-- Contact --}}
                <div class="w-full flex flex-col items-center sm:items-start">
                    <p class="text-xs font-bold text-tpc-accent uppercase tracking-widest mb-4">Contact</p>
                    <ul class="w-full space-y-3 text-sm text-white/75">
                        <li>
                            <a href="https://www.facebook.com/TalibonPolytechnicCollege"
                               target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 py-1 text-tpc-accent hover:text-white transition font-medium touch-manipulation">
                                <span class="shrink-0 text-base leading-none">📘</span>
                                <span>Facebook Page</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ $email }}"
                               class="flex items-center gap-3 py-1 text-tpc-accent hover:text-white transition font-medium touch-manipulation">
                                <span class="shrink-0 text-base leading-none">✉</span>
                                <span class="break-all">{{ $email }}</span>
                            </a>
                        </li>
                        <li class="flex items-center gap-3 py-1">
                            <span class="shrink-0 text-base leading-none">☎</span>
                            <span>{{ $phone }}</span>
                        </li>
                        <li class="flex items-start gap-3 py-1">
                            <span class="shrink-0 text-base leading-none mt-0.5">📍</span>
                            <span>{{ $address }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- Copyright strip --}}
    <div class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-5 py-3 flex flex-col items-center gap-1.5 sm:flex-row sm:justify-between">
            <p class="text-xs text-white/80 text-center sm:text-left">
                © {{ now()->year }} {{ $siteName }}. All rights reserved.
            </p>
            <div class="flex items-center gap-2">
                <a href="https://www.facebook.com/share/18XASdjQ8z/" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('images/self-logo-icon.png') }}" alt="SecrIT Solutions Logo"
                         class="h-4 w-auto opacity-60 hover:opacity-100 transition">
                </a>
                <span class="text-white/30 text-xs">|</span>
                <p class="text-xs text-white/60">SecrIT Solutions: AABJRSV</p>
            </div>
        </div>
    </div>

</footer>
