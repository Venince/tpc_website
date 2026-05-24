{{-- resources/views/public/academics.blade.php --}}
@extends('layouts.site')

@section('title', 'Academics')

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
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Academic Programs
                </h1>
                <p class="mt-3 max-w-lg text-sm text-white/60 leading-relaxed">
                    Explore our academic offerings designed to build competence, confidence, and career readiness.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('admission') }}"
                       class="inline-flex items-center justify-center rounded-full border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary
                              hover:bg-tpc-primary hover:border-white hover:text-white transition">
                        Admission Guide
                    </a>
                    <a href="{{ route('org-chart') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-white/90 bg-black/20 backdrop-blur-sm px-5 py-2.5 text-sm font-bold text-white
                              hover:bg-white hover:text-tpc-primary transition">
                        Organizational Chart
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- PROGRAMS --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-14">

            <div class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">All Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium shrink-0">{{ $programs->count() }} {{ Str::plural('Program', $programs->count()) }}</span>
            </div>

            @if ($programs->isEmpty())
                <div class="py-16 sm:py-20 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-xl bg-white">
                    No programs found.
                </div>
            @else
                @php
                    $count = $programs->count();
                    $smRem = $count % 2;
                    $lgRem = $count % 3;
                @endphp

                <div class="grid gap-4 sm:gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($programs as $program)
                        @php
                            $isLast   = $loop->last;
                            $lgCenter = ($isLast && $lgRem === 1) ? 'lg:col-start-2' : '';
                            $smCenter = ($isLast && $smRem === 1 && $lgRem !== 1) ? 'sm:col-start-1 sm:col-span-2 sm:max-w-sm sm:mx-auto sm:w-full' : '';
                        @endphp

                        <a href="{{ route('academics.show', $program) }}"
                           class="program-card group relative bg-white rounded-2xl border border-gray-200 shadow-sm
                                  overflow-hidden flex flex-col {{ $lgCenter }} {{ $smCenter }}">

                            {{-- Top accent bar --}}
                            <div class="program-card-bar h-1.5 w-full bg-tpc-primary transition-colors duration-300"></div>

                            <div class="p-4 sm:p-6 flex flex-col flex-1">

                                <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4">

                                    {{-- Logo / icon --}}
                                    <div class="program-logo-wrap shrink-0 h-12 w-12 sm:h-16 sm:w-16
                                                rounded-xl flex items-center justify-center
                                                {{ $program->logo_path ? 'p-1.5 sm:p-2' : 'bg-tpc-primary/5 border border-tpc-primary/10' }}">
                                        @if ($program->logo_path)
                                            <img src="{{ asset('storage/' . $program->logo_path) }}"
                                                 alt="{{ $program->code }} logo"
                                                 class="h-full w-full object-contain"
                                                 loading="lazy">
                                        @else
                                            <span class="text-xl sm:text-2xl">🎓</span>
                                        @endif
                                    </div>

                                    <div class="min-w-0 pt-0.5 sm:pt-1">
                                        <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full mb-1">
                                            {{ $program->code ?? 'Program' }}
                                        </span>
                                        <h3 class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition-colors leading-snug">
                                            {{ $program->name }}
                                        </h3>
                                        @if ($program->department)
                                            <p class="mt-0.5 text-[11px] sm:text-xs text-gray-400">{{ $program->department }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if ($program->description)
                                    <p class="text-[11px] sm:text-xs text-gray-500 leading-relaxed line-clamp-3 flex-1">
                                        {{ $program->description }}
                                    </p>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                <div class="mt-4 sm:mt-5 pt-3 sm:pt-4 border-t border-gray-100 flex items-center justify-end">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-tpc-primary group-hover:gap-2 transition-all duration-200">
                                        View Program
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- CTA Card --}}
                <div class="mt-6 sm:mt-8 max-w-2xl mx-auto">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-accent"></div>
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm sm:text-base font-bold text-gray-800">Not sure which program to choose?</p>
                                <p class="mt-1 text-xs sm:text-sm text-gray-500">Contact us for guidance on requirements, enrollment, and academic support.</p>
                            </div>
                            <div class="flex gap-2 sm:gap-3 shrink-0">
                                <a href="{{ route('contact') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                          hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    Contact Us
                                </a>
                                <a href="{{ route('news.index') }}"
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-full border-2 border-tpc-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary
                                          hover:bg-tpc-primary hover:text-white transition">
                                    Latest Updates →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
