{{-- resources/views/partials/footer.blade.php --}}
@php
    $siteName = \App\Support\Settings::get('site_name', 'Talibon Polytechnic College');
    $address  = \App\Support\Settings::get('address', 'Talibon, Bohol, Philippines');
    $email    = \App\Support\Settings::get('email', 'info@tpc.edu.ph');
    $phone    = \App\Support\Settings::get('phone', '+63 000 000 0000');
@endphp

<footer id="tpc-footer" class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid gap-10 lg:grid-cols-3">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="grid gap-6 md:grid-cols-2 md:items-start">
                    {{-- Left: Logo + Description --}}
                    <div class="flex items-start gap-3">
                        <img
                            src="{{ asset('images/TPC-Logo.png') }}"
                            alt="TPC Logo"
                            class="h-11 w-11 bg-white p-1"
                        />

                        <div>
                            <p class="text-lg font-semibold text-tpc-ink">{{ $siteName }}</p>
                            <p class="mt-1 text-sm text-tpc-ink/70">
                                Quality educational opportunities that empower students to become competitive
                                and responsive to community needs.
                            </p>
                        </div>
                    </div>

                    {{-- Right: Contact card --}}
                    <div class="rounded-2xl border border-tpc-primary/15 bg-white/80 p-5 shadow-sm">
                        <p class="text-sm font-semibold text-tpc-ink">Contact</p>

                        <div class="mt-4 space-y-3 text-sm text-tpc-ink/80">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-tpc-primary/10 text-tpc-primary leading-none">
                                    📍
                                </span>
                                <span class="leading-relaxed">{{ $address }}</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-tpc-primary/10 text-tpc-primary leading-none">
                                    ☎️
                                </span>
                                <span>{{ $phone }}</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-tpc-primary/10 text-tpc-primary leading-none">
                                    ✉️
                                </span>
                                <a class="font-medium text-tpc-primary hover:text-tpc-secondary"
                                href="mailto:{{ $email }}">
                                    {{ $email }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="lg:justify-self-end">
                <p class="text-sm font-semibold text-tpc-ink">Quick Links</p>

                <ul class="mt-4 grid grid-cols-2 gap-x-10 gap-y-3 text-sm">
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('home') }}">Home</a></li>
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('home') }}#about">About</a></li>
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('academics') }}">Academics</a></li>
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('admission') }}">Admission</a></li>
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('news.index') }}">News</a></li>
                    <li><a class="text-tpc-ink/70 hover:text-tpc-primary" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-3 border-t pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-tpc-ink/60">
                © {{ now()->year }} {{ $siteName }}. All rights reserved.
            </p>
            <p class="text-xs text-tpc-ink/60">
                SecrIT Solutions
            </p>
        </div>
    </div>
</footer>
