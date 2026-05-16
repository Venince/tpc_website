{{-- resources/views/public/contact.blade.php --}}
@extends('layouts.site')

@section('title', 'Contact')

@section('content')
@php
    $siteName = \App\Support\Settings::get('site_name', 'Talibon Polytechnic College');
    $address  = \App\Support\Settings::get('address', '');
    $email    = \App\Support\Settings::get('email', '');
    $phone    = \App\Support\Settings::get('phone', '');
    $mapQuery = trim($siteName . ($address ? ', ' . $address : ''));
    $mapSrc   = $mapQuery ? 'https://www.google.com/maps?q=' . rawurlencode($mapQuery) . '&output=embed' : null;
    $mapLink  = $mapQuery ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery) : null;
@endphp

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-10">
            <p class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">Get in Touch</h1>
            <p class="mt-2 max-w-2xl text-xs sm:text-sm text-white/75 leading-relaxed hidden sm:block">
                Have questions about programs, enrollment, or announcements? Send us a message and we'll get back to you.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-6 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:py-14">
            <div class="grid gap-6 sm:gap-8 lg:grid-cols-3">

                {{-- FORM --}}
                <div class="lg:col-span-2">

                    <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                        <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                        <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">Send a Message</h2>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    {{-- Success --}}
                    @if(session('success'))
                        <div class="mb-5 sm:mb-6 flex gap-3 bg-green-50 border border-green-200 rounded-xl px-4 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm text-green-700">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><span class="font-bold">Success:</span> {{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="mb-5 sm:mb-6 flex gap-3 bg-red-50 border border-red-200 rounded-xl px-4 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm text-red-700">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 shrink-0 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                            </svg>
                            <div>
                                <p class="font-bold mb-1">Please fix the following:</p>
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <form class="divide-y divide-gray-100" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">

                            <div class="grid sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                                <div class="p-4 sm:p-5">
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                                        Full Name <span class="text-red-400">*</span>
                                    </label>
                                    <input name="name" value="{{ old('name') }}" placeholder="Your full name"
                                           class="w-full rounded-lg border border-gray-200 px-3 py-2 sm:py-2.5 text-xs sm:text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300" />
                                </div>
                                <div class="p-4 sm:p-5">
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                                        Email Address <span class="text-red-400">*</span>
                                    </label>
                                    <input name="email" value="{{ old('email') }}" placeholder="you@example.com"
                                           class="w-full rounded-lg border border-gray-200 px-3 py-2 sm:py-2.5 text-xs sm:text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300" />
                                </div>
                            </div>

                            <div class="p-4 sm:p-5">
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    Subject <span class="text-red-400">*</span>
                                </label>
                                <input name="subject" value="{{ old('subject') }}" placeholder="Admission inquiry / Program info / etc."
                                       class="w-full rounded-lg border border-gray-200 px-3 py-2 sm:py-2.5 text-xs sm:text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300" />
                            </div>

                            <div class="p-4 sm:p-5">
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    Message <span class="text-red-400">*</span>
                                </label>
                                <textarea name="message" rows="6" placeholder="Write your message here..."
                                          class="w-full rounded-lg border border-gray-200 px-3 py-2 sm:py-2.5 text-xs sm:text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition resize-none placeholder-gray-300">{{ old('message') }}</textarea>
                            </div>

                            <div class="bg-gray-50 px-4 sm:px-5 py-3 sm:py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-b-2xl">
                                <p class="text-[10px] sm:text-xs text-gray-400">We respond on weekdays only (Monday – Friday).</p>
                                <div class="flex gap-2 sm:gap-3">
                                    <button class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition touch-manipulation">
                                        <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Send Message
                                    </button>
                                    <a href="{{ route('home') }}"
                                       class="inline-flex items-center rounded-full border-2 border-gray-200 px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-gray-500 hover:border-tpc-primary hover:text-tpc-primary transition touch-manipulation">
                                        Back to Home
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- INFO / MAP --}}
                <aside class="space-y-5 sm:space-y-6">

                    {{-- Contact Info --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">Contact Information</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5">
                                <span class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-tpc-primary/10 text-tpc-primary">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Address</p>
                                    <p class="text-xs sm:text-sm text-gray-700">{{ $address ?: 'Set your address in Admin → Settings.' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5">
                                <span class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-tpc-primary/10 text-tpc-primary">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Phone</p>
                                    <p class="text-xs sm:text-sm text-gray-700">{{ $phone ?: 'Set your phone in Admin → Settings.' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 sm:gap-4 p-4 sm:p-5">
                                <span class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-tpc-primary/10 text-tpc-primary">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Email</p>
                                    @if($email)
                                        <a href="mailto:{{ $email }}" class="text-xs sm:text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">{{ $email }}</a>
                                    @else
                                        <p class="text-xs sm:text-sm text-gray-500">Set your email in Admin → Settings.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">Campus Map</p>
                        </div>
                        <div class="p-4 sm:p-5">
                            <p class="text-[10px] sm:text-xs text-gray-400 mb-3 sm:mb-4">
                                Searching: <span class="font-semibold text-gray-600">{{ $siteName }}@if($address), {{ $address }}@endif</span>
                            </p>

                            @if($mapSrc)
                                <div class="relative w-full rounded-xl overflow-hidden border border-gray-200" style="padding-top: 56.25%;">
                                    <iframe class="absolute inset-0 h-full w-full"
                                            src="{{ $mapSrc }}" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="h-40 sm:h-48 rounded-xl border border-dashed border-gray-300 flex items-center justify-center text-xs sm:text-sm text-gray-400">
                                    Set address in Admin → Settings.
                                </div>
                            @endif

                            <div class="mt-3 sm:mt-4 grid gap-2 sm:grid-cols-2">
                                @if($mapLink)
                                    <a href="{{ $mapLink }}" target="_blank" rel="noopener"
                                       class="flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 py-2 text-[10px] sm:text-xs font-bold text-white hover:bg-tpc-secondary hover:text-white transition touch-manipulation">
                                        Open in Maps
                                    </a>
                                @endif
                                <a href="{{ route('admission') }}"
                                   class="flex items-center justify-center rounded-full border-2 border-gray-200 px-4 py-2 text-[10px] sm:text-xs font-bold text-gray-500 hover:border-tpc-primary hover:text-tpc-primary transition touch-manipulation">
                                    View Admission
                                </a>
                            </div>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
