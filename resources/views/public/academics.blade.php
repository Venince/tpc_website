{{-- resources/views/public/academics.blade.php --}}
@extends('layouts.site')

@section('title', 'Academics')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Academic Programs</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Explore our academic offerings designed to build competence, confidence, and career readiness.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('admission') }}"
                   class="inline-flex items-center rounded-l-full border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Admission Guide
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center rounded-r-full border-2 border-white/60 px-5 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Contact Us
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- PROGRAMS --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-14">

            <div class="flex items-center gap-4 mb-10">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">All Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">{{ $programs->count() }} {{ Str::plural('Program', $programs->count()) }}</span>
            </div>

            @if ($programs->isEmpty())
                <div class="py-20 text-center text-gray-400 text-sm border border-dashed border-gray-300 rounded-xl bg-white">
                    No programs found.
                </div>
            @else
                @php
                    $count     = $programs->count();
                    $smRem     = $count % 2;
                    $lgRem     = $count % 3;
                @endphp

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($programs as $program)
                        @php
                            $isLast = $loop->last;

                            // Center on lg (3-col) if remainder is 1
                            $lgCenter = ($isLast && $lgRem === 1) ? 'lg:col-start-2' : '';

                            // Center on sm (2-col) if remainder is 1 AND not already handled by lg
                            $smCenter = ($isLast && $smRem === 1 && $lgRem !== 1) ? 'sm:col-start-1 sm:col-span-2 sm:max-w-sm sm:mx-auto sm:w-full' : '';
                        @endphp

                        <a href="{{ route('academics.show', $program) }}"
                        class="group relative bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300 overflow-hidden flex flex-col {{ $lgCenter }} {{ $smCenter }}">

                            {{-- Top accent bar --}}
                            <div class="h-1.5 w-full bg-tpc-primary group-hover:bg-tpc-accent transition-colors duration-300"></div>

                            <div class="p-6 flex flex-col flex-1">

                                {{-- Logo + Code --}}
                                <div class="flex items-start gap-4 mb-4">
                                    @if ($program->logo_path)
                                        <div class="shrink-0 h-16 w-16 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 flex items-center justify-center p-2">
                                            <img src="{{ asset('storage/' . $program->logo_path) }}"
                                                alt="{{ $program->code }} logo"
                                                class="h-full w-full object-contain" loading="lazy">
                                        </div>
                                    @else
                                        <div class="shrink-0 h-16 w-16 rounded-xl bg-tpc-primary/5 border border-tpc-primary/10 flex items-center justify-center text-2xl">
                                            🎓
                                        </div>
                                    @endif

                                    <div class="min-w-0 pt-1">
                                        <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full mb-1">
                                            {{ $program->code ?? 'Program' }}
                                        </span>
                                        <h3 class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition-colors leading-snug">
                                            {{ $program->name }}
                                        </h3>
                                        @if ($program->department)
                                            <p class="mt-0.5 text-xs text-gray-400">{{ $program->department }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Description --}}
                                @if ($program->description)
                                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-3 flex-1">
                                        {{ $program->description }}
                                    </p>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                {{-- Footer --}}
                                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-end">
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
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10 lg:flex lg:items-center lg:justify-between gap-8">
            <div>
                <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Need Help?</p>
                <h2 class="text-2xl font-bold text-white">Not sure which program to choose?</h2>
                <p class="mt-1 text-sm text-white/75">
                    Contact us for guidance on requirements, enrollment, and academic support.
                </p>
            </div>
            <div class="mt-6 flex flex-wrap gap-3 lg:mt-0 lg:shrink-0">
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center rounded-l-full border-2 border-white bg-white px-6 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Contact Us
                </a>
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center rounded-r-full border-2 border-white/60 px-6 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Latest Updates →
                </a>
            </div>
        </div>
    </section>

@endsection
