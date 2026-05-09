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
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Get in Touch</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Have questions about programs, enrollment, or announcements? Send us a message and we'll get back to you.
            </p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid gap-8 lg:grid-cols-3">

                {{-- FORM --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                        <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Send a Message</h2>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    {{-- Success --}}
                    @if(session('success'))
                        <div class="mb-6 border-l-4 border-tpc-primary bg-tpc-primary/5 px-5 py-4 text-sm text-gray-700">
                            <span class="font-bold text-tpc-primary">Success:</span> {{ session('success') }}
                        </div>
                    @endif

                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="mb-6 border-l-4 border-red-500 bg-red-50 px-5 py-4 text-sm text-red-700">
                            <p class="font-bold mb-2">Please fix the following:</p>
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="border border-gray-200" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">

                        <div class="grid gap-px bg-gray-200 sm:grid-cols-2">
                            <div class="bg-white p-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                                <input name="name" value="{{ old('name') }}" placeholder="Your full name"
                                       class="w-full border border-gray-200 px-3 py-2 text-sm text-tpc-ink focus:border-tpc-primary focus:ring-0 focus:outline-none transition" />
                            </div>
                            <div class="bg-white p-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                                <input name="email" value="{{ old('email') }}" placeholder="you@example.com"
                                       class="w-full border border-gray-200 px-3 py-2 text-sm text-tpc-ink focus:border-tpc-primary focus:ring-0 focus:outline-none transition" />
                            </div>
                        </div>

                        <div class="bg-white border-t border-gray-200 p-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Subject</label>
                            <input name="subject" value="{{ old('subject') }}" placeholder="Admission inquiry / Program info / etc."
                                   class="w-full border border-gray-200 px-3 py-2 text-sm text-tpc-ink focus:border-tpc-primary focus:ring-0 focus:outline-none transition" />
                        </div>

                        <div class="bg-white border-t border-gray-200 p-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Message</label>
                            <textarea name="message" rows="7" placeholder="Write your message here..."
                                      class="w-full border border-gray-200 px-3 py-2 text-sm text-tpc-ink focus:border-tpc-primary focus:ring-0 focus:outline-none transition resize-none">{{ old('message') }}</textarea>
                        </div>

                        <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-gray-400">We respond on weekdays only (Monday – Friday).</p>
                            <div class="flex gap-3">
                                <button class="inline-flex items-center border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    Send Message
                                </button>
                                <a href="{{ route('home') }}"
                                   class="inline-flex items-center border-2 border-gray-300 px-5 py-2.5 text-sm font-bold text-gray-600 hover:border-tpc-primary hover:text-tpc-primary transition">
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- INFO / MAP --}}
                <aside class="space-y-6">
                    {{-- Contact Info --}}
                    <div class="border border-gray-200">
                        <div class="bg-tpc-primary px-4 py-3">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">Contact Information</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div class="flex items-start gap-3 p-5">
                                <span class="shrink-0 mt-0.5 flex h-8 w-8 items-center justify-center bg-tpc-primary/10 text-tpc-primary font-bold text-sm">📍</span>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Address</p>
                                    <p class="text-sm text-gray-700">{{ $address ?: 'Set your address in Admin → Settings.' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-5">
                                <span class="shrink-0 mt-0.5 flex h-8 w-8 items-center justify-center bg-tpc-primary/10 text-tpc-primary font-bold text-sm">☎</span>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Phone</p>
                                    <p class="text-sm text-gray-700">{{ $phone ?: 'Set your phone in Admin → Settings.' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-5">
                                <span class="shrink-0 mt-0.5 flex h-8 w-8 items-center justify-center bg-tpc-primary/10 text-tpc-primary font-bold text-sm">✉</span>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">Email</p>
                                    @if($email)
                                        <a href="mailto:{{ $email }}" class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">{{ $email }}</a>
                                    @else
                                        <p class="text-sm text-gray-500">Set your email in Admin → Settings.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="border border-gray-200">
                        <div class="bg-tpc-primary px-4 py-3">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">Campus Map</p>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-gray-400 mb-4">
                                Searching: <span class="font-semibold text-gray-600">{{ $siteName }}@if($address), {{ $address }}@endif</span>
                            </p>

                            @if($mapSrc)
                                <div class="relative w-full border border-gray-200" style="padding-top: 56.25%;">
                                    <iframe class="absolute inset-0 h-full w-full"
                                            src="{{ $mapSrc }}" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="h-48 border border-dashed border-gray-300 flex items-center justify-center text-sm text-gray-400">
                                    Set address in Admin → Settings.
                                </div>
                            @endif

                            <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                @if($mapLink)
                                    <a href="{{ $mapLink }}" target="_blank" rel="noopener"
                                       class="flex items-center justify-center border-2 border-tpc-primary px-4 py-2 text-xs font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                        Open in Maps
                                    </a>
                                @endif
                                <a href="{{ route('admission') }}"
                                   class="flex items-center justify-center border-2 border-gray-300 px-4 py-2 text-xs font-bold text-gray-600 hover:border-tpc-primary hover:text-tpc-primary transition">
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
