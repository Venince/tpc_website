@extends('layouts.site')

@section('title', 'Feedback')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>
        <div class="relative mx-auto max-w-4xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">
            <div class="flex flex-col items-center text-center">
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">Share Your Feedback</h1>
                <p class="mt-3 max-w-lg text-sm text-white/60 leading-relaxed">
                    Your experience matters to us. Tell us what we're doing well and where we can improve.
                </p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0" style="margin-bottom: -2px;">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12" style="display: block;">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 py-10 sm:py-14">

            {{-- Success --}}
            @if(session('success'))
                <div class="mb-6 flex gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-sm text-green-700">
                    <svg class="h-5 w-5 shrink-0 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><span class="font-bold">Thank you!</span> {{ session('success') }}</span>
                </div>
            @endif

            {{-- Errors --}}
            @if($errors->any())
                <div class="mb-6 flex gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-4 text-sm text-red-700">
                    <svg class="h-5 w-5 shrink-0 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ rating: {{ old('rating', 0) }}, hoverRating: 0, showContact: {{ old('name') || old('email') ? 'true' : 'false' }} }">

                <form method="POST" action="{{ route('feedback.store') }}" class="divide-y divide-gray-100">
                    @csrf
                    <input type="text" id="fb-website" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">

                    {{-- Star rating --}}
                    <div class="p-5 sm:p-6">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">
                            How would you rate your experience? <span class="text-red-400">*</span>
                        </label>
                        <div class="flex gap-2" @mouseleave="hoverRating = 0">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="rating = i" @mouseenter="hoverRating = i"
                                    class="transition-transform hover:scale-110 focus:outline-none">
                                    <svg class="h-10 w-10 sm:h-11 sm:w-11" :class="(hoverRating || rating) >= i ? 'text-amber-400' : 'text-gray-200'"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                    </div>

                    {{-- Category --}}
                    <div class="p-5 sm:p-6">
                        <label for="fb-category" class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Category</label>
                        <select id="fb-category" name="category"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition">
                            <option value="" @selected(old('category') === null)>General</option>
                            @foreach(\App\Models\Feedback::CATEGORIES as $key => $label)
                                <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Message --}}
                    <div class="p-5 sm:p-6">
                        <label for="fb-message" class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Comments</label>
                        <textarea id="fb-message" name="message" rows="5" placeholder="Tell us more (optional)..."
                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition resize-none placeholder-gray-300">{{ old('message') }}</textarea>
                    </div>

                    {{-- Optional contact info --}}
                    <div class="p-5 sm:p-6">
                        <button type="button" @click="showContact = !showContact"
                            class="flex items-center gap-1.5 text-xs font-bold text-tpc-primary hover:text-tpc-secondary transition">
                            <svg class="h-3.5 w-3.5 transition-transform" :class="showContact ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            Add contact info (optional)
                        </button>
                        <div x-show="showContact" x-transition class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="fb-name" class="sr-only">Your name</label>
                                <input id="fb-name" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" autocomplete="name"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300">
                            </div>
                            <div>
                                <label for="fb-email" class="sr-only">Your email</label>
                                <input id="fb-email" type="email" name="email" value="{{ old('email') }}" placeholder="Your email" autocomplete="email"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-800 focus:border-tpc-primary focus:ring-0 focus:outline-none transition placeholder-gray-300">
                            </div>
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-400">Leave these blank to stay anonymous. We'll only use them if you'd like a reply.</p>
                    </div>

                    <div class="bg-gray-50 px-5 sm:px-6 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-b-2xl">
                        <p class="text-[10px] sm:text-xs text-gray-400">Anonymous by default.</p>
                        <div class="flex gap-2 sm:gap-3">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition touch-manipulation">
                                Submit Feedback
                            </button>
                            <a href="{{ route('home') }}"
                               class="inline-flex items-center rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition touch-manipulation">
                                Back to Home
                            </a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>

@endsection
