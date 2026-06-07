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
                    <ul class="w-full grid grid-cols-1 gap-x-6 gap-y-3 lg:grid-cols-2 text-sm text-white/75">

                        <li class="flex items-start gap-3">
                            <svg class="shrink-0 h-4 w-4 text-white mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                            <a href="https://www.facebook.com/TalibonPolytechnicCollege"
                            target="_blank" rel="noopener noreferrer"
                            class="text-white/75 hover:text-white transition font-medium touch-manipulation">
                                Facebook Page
                            </a>
                        </li>

                        <li class="flex items-start gap-3">
                            <svg class="shrink-0 h-4 w-4 text-white mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $email }}"
                            class="text-white/75 hover:text-white transition font-medium touch-manipulation">
                                {{ $email }}
                            </a>
                        </li>

                        <li class="flex items-start gap-3">
                            <svg class="shrink-0 h-4 w-4 text-white mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $phone }}</span>
                        </li>

                        <li class="flex items-start gap-3">
                            <svg class="shrink-0 h-4 w-4 text-white mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
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
