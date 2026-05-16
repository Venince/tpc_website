{{-- resources/views/public/admission.blade.php --}}
@extends('layouts.site')

@section('title', 'Admission')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-10">
            <p class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">Enrollment & Requirements</h1>
            <p class="mt-2 max-w-2xl text-xs sm:text-sm text-white/75 leading-relaxed hidden sm:block">
                Learn the enrollment steps and prepare the needed documents to become a student of Talibon Polytechnic College.
            </p>
            <div class="mt-4 sm:mt-5 flex flex-wrap gap-2 sm:gap-3">
                <a href="#requirements"
                   class="inline-flex items-center justify-center rounded-full border-2 border-white bg-white px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-secondary hover:border-tpc-secondary hover:text-white transition">
                    View Requirements
                </a>
                <a href="#process"
                   class="inline-flex items-center justify-center rounded-full border-2 border-white px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Enrollment Process
                </a>
                <a href="{{ route('contact') }}"
                   class="hidden sm:inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white/75 hover:text-white transition">
                    Need help? Contact us →
                </a>
            </div>
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

                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-6 sm:space-y-8">

                    {{-- Requirements --}}
                    @if (($sections->has('freshmen') && $sections['freshmen']->is_visible) ||
                         ($sections->has('transferee') && $sections['transferee']->is_visible))
                        <div id="requirements" class="scroll-mt-20 sm:scroll-mt-24">

                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">Admission Requirements</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5">Bring original copies and photocopies as applicable.</p>

                            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2">
                                @foreach (['freshmen', 'transferee'] as $key)
                                    @if ($sections->has($key) && $sections[$key]->is_visible)
                                        @php $sec = $sections[$key]; @endphp
                                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                            <div class="h-1.5 bg-tpc-primary"></div>
                                            <div class="p-4 sm:p-6">
                                                <p class="text-[10px] sm:text-[11px] font-bold tracking-widest text-tpc-primary uppercase mb-3 sm:mb-4">
                                                    {{ $sec->label }}
                                                </p>
                                                <ul class="space-y-2.5 sm:space-y-3">
                                                    @foreach ($sec->items as $item)
                                                        <li class="flex items-start gap-3 text-xs sm:text-sm text-gray-700">
                                                            <span class="mt-1.5 h-2 w-2 rounded-full bg-tpc-primary shrink-0"></span>
                                                            {{ $item->title }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Requirements Note --}}
                            @if ($sections->has('requirements_note') && $sections['requirements_note']->is_visible && $sections['requirements_note']->note)
                                <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4 bg-tpc-primary/5 border border-tpc-primary/20 rounded-xl px-4 sm:px-5 py-3 sm:py-4">
                                    <span class="shrink-0 text-tpc-primary mt-0.5">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">
                                            {{ $sections['requirements_note']->label }}
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $sections['requirements_note']->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Enrollment Process --}}
                    @if ($sections->has('process') && $sections['process']->is_visible)
                        @php $process = $sections['process']; @endphp
                        <div id="process" class="scroll-mt-20 sm:scroll-mt-24">

                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-[10px] sm:text-xs font-bold tracking-widest text-tpc-primary uppercase">
                                    {{ $process->label }}
                                </h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="h-1.5 bg-tpc-primary"></div>
                                <div class="divide-y divide-gray-100">
                                    @foreach ($process->items as $i => $step)
                                        <div class="flex gap-3 sm:gap-5 p-4 sm:p-5 hover:bg-gray-50 transition">
                                            <span class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-tpc-primary text-white text-xs sm:text-sm font-bold shadow-sm">
                                                {{ $i + 1 }}
                                            </span>
                                            <div class="pt-1 min-w-0">
                                                <p class="font-bold text-xs sm:text-sm text-gray-800">{{ $step->title }}</p>
                                                @if ($step->body)
                                                    <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $step->body }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($process->note)
                                <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4 bg-tpc-primary/5 border border-tpc-primary/20 rounded-xl px-4 sm:px-5 py-3 sm:py-4">
                                    <span class="shrink-0 text-tpc-primary mt-0.5">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                                        </svg>
                                    </span>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ $process->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Explore Programs CTA --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-accent"></div>
                        <div class="p-4 sm:p-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-bold text-sm sm:text-base text-gray-800">Explore programs before enrolling</p>
                                <p class="mt-1 text-xs sm:text-sm text-gray-500">View all academic programs offered by Talibon Polytechnic College.</p>
                            </div>
                            <div class="flex gap-2 sm:gap-3 shrink-0">
                                <a href="{{ route('academics') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    View Programs
                                </a>
                                <a href="{{ route('news.index') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                    Updates →
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT SIDEBAR --}}
                {{-- On mobile, sidebar stacks below main content naturally --}}
                <aside class="space-y-4 sm:space-y-6">

                    {{-- Office Hours --}}
                    @if ($sections->has('office_hours') && $sections['office_hours']->is_visible)
                        @php $oh = $sections['office_hours']; @endphp
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                                <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">{{ $oh->label }}</p>
                            </div>
                            <div class="px-4 sm:px-5 divide-y divide-gray-100">
                                @foreach ($oh->items as $row)
                                    <div class="flex items-center justify-between py-2.5 sm:py-3 text-xs sm:text-sm">
                                        <span class="text-gray-500">{{ $row->title }}</span>
                                        <span class="font-bold text-gray-800">{{ $row->body }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($oh->note)
                                <div class="border-t border-gray-100 bg-tpc-primary/5 px-4 sm:px-5 py-3 sm:py-4 rounded-b-2xl">
                                    <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">Tip</p>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ $oh->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Admissions --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-[10px] sm:text-xs font-bold text-tpc-primary uppercase tracking-widest">Contact Admissions</p>
                        </div>
                        <div class="p-4 sm:p-5">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                                Reach out for enrollment concerns, schedule, and guidance.
                            </p>
                            <a href="{{ route('contact') }}"
                               class="mt-3 sm:mt-4 flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                Go to Contact Page
                            </a>
                        </div>
                    </div>

                    {{-- Mobile-only: Need help link (visible since header link is hidden on mobile) --}}
                    <div class="block sm:hidden bg-tpc-primary/5 border border-tpc-primary/20 rounded-xl px-4 py-3 text-center">
                        <a href="{{ route('contact') }}"
                           class="text-xs font-semibold text-tpc-primary">
                            Need help? Contact us →
                        </a>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
