{{-- resources/views/public/contact.blade.php --}}
@extends('layouts.site')

@section('title', 'Contact')

@section('content')
@php
    $siteName = \App\Support\Settings::get('site_name', 'Talibon Polytechnic College');
    $address  = \App\Support\Settings::get('address', '');
    $email    = \App\Support\Settings::get('email', '');
    $phone    = \App\Support\Settings::get('phone', '');

    // ✅ Pin label + address (label helps even if address is generic)
    $mapQuery = trim($siteName . ($address ? ', ' . $address : ''));

    $mapSrc  = $mapQuery ? 'https://www.google.com/maps?q=' . rawurlencode($mapQuery) . '&output=embed' : null;
    $mapLink = $mapQuery ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery) : null;
@endphp

    {{-- PAGE HEADER --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Contact</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">
                Get in touch
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-tpc-ink/70">
                Have questions about programs, enrollment, or announcements? Send a message and we’ll get back to you.
            </p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 pb-20">
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- FORM --}}
                <div class="lg:col-span-2">
                    <div class="tpc-card p-6">
                        <h2 class="text-xl font-semibold text-tpc-ink">Send a message</h2>

                        {{-- Success --}}
                        @if (session('success'))
                            <div class="mt-4 rounded-xl border border-tpc-primary/20 bg-tpc-primary/5 p-4 text-sm text-tpc-ink">
                                <span class="font-medium text-tpc-primary">Success:</span> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Errors --}}
                        @if ($errors->any())
                            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                                <div class="font-semibold">Please fix the following:</div>
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="mt-6 grid gap-5" method="POST" action="{{ route('contact.store') }}">
                            @csrf

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-tpc-ink">Full Name</label>
                                    <input name="name" value="{{ old('name') }}" placeholder="Your name"
                                           class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-tpc-ink">Email</label>
                                    <input name="email" value="{{ old('email') }}" placeholder="you@example.com"
                                           class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-tpc-ink">Subject</label>
                                <input name="subject" value="{{ old('subject') }}" placeholder="Admission inquiry / Program info / etc."
                                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
                            </div>

                            <div>
                                <label class="text-sm font-medium text-tpc-ink">Message</label>
                                <textarea name="message" rows="7" placeholder="Write your message here..."
                                          class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20">{{ old('message') }}</textarea>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <button class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-tpc-secondary">
                                    Send Message
                                </button>

                                <a href="{{ route('home') }}"
                                   class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary shadow-sm transition hover:bg-tpc-primary/5">
                                    Back to Home
                                </a>
                            </div>
                        </form>

                        <div class="mt-6 rounded-xl bg-tpc-primary/5 p-4">
                            <p class="text-sm font-medium text-tpc-ink">Note</p>
                            <p class="mt-1 text-sm text-tpc-ink/70">
                                We respond to messages on weekdays only (Monday–Friday).
                            </p>
                        </div>
                    </div>
                    {{-- Honeypot (spam bots fill this, humans won't). Must stay empty --}}
                    <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">
                </div>

                {{-- INFO / MAP --}}
                <aside class="space-y-6">
                    <div class="tpc-card p-6">
                        <h3 class="text-lg font-semibold text-tpc-ink">Contact Information</h3>

                        <div class="mt-4 space-y-3 text-sm text-tpc-ink/80">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-tpc-accent/30">📍</span>
                                <div>
                                    <p class="font-medium">Address</p>
                                    <p class="text-tpc-ink/70">
                                        {{ $address ?: 'Set your address in Admin → Settings.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-tpc-accent/30">☎️</span>
                                <div>
                                    <p class="font-medium">Phone</p>
                                    <p class="text-tpc-ink/70">
                                        {{ $phone ?: 'Set your phone in Admin → Settings.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-tpc-accent/30">✉️</span>
                                <div>
                                    <p class="font-medium">Email</p>
                                    @if ($email)
                                        <a class="text-tpc-primary hover:text-tpc-secondary font-medium" href="mailto:{{ $email }}">
                                            {{ $email }}
                                        </a>
                                    @else
                                        <p class="text-tpc-ink/70">Set your email in Admin → Settings.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tpc-card p-6">
                        <h3 class="text-lg font-semibold text-tpc-ink">Campus Map</h3>
                        <p class="mt-2 text-sm text-tpc-ink/70">
                            Pin search uses: <span class="font-medium">{{ $siteName }}</span>@if($address), {{ $address }}@endif
                        </p>

                        <div class="mt-4 overflow-hidden rounded-xl border border-tpc-primary/15 bg-white">
                            @if ($mapSrc)
                                <div class="relative w-full" style="padding-top: 56.25%;">
                                    <iframe
                                        class="absolute inset-0 h-full w-full"
                                        src="{{ $mapSrc }}"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            @else
                                <div class="h-56 rounded-xl border border-dashed border-tpc-primary/30 bg-tpc-primary/5 flex items-center justify-center text-sm text-tpc-ink/60">
                                    Please set Address (and optionally Site Name) in Admin → Settings.
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @if ($mapLink)
                                <a href="{{ $mapLink }}"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                                    Open Google Maps
                                </a>
                            @endif

                            <a href="{{ route('admission') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                                View Admission
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
