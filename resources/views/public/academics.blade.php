{{-- resources/views/public/academics.blade.php --}}
@extends('layouts.site')

@section('title', 'Academics')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Academic Programs</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Explore our academic offerings designed to build competence, confidence, and career readiness.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('admission') }}"
                   class="inline-flex items-center border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Admission Guide
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center border-2 border-white/60 px-5 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    {{-- PROGRAMS --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-12">

            <div class="flex items-center gap-4 mb-8">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">All Programs</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            @php
                $programCount = $programs->count();
                $remainder    = $programCount % 3;
            @endphp

            <div class="grid gap-px bg-gray-200 border border-gray-200 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    @php
                        $isLast     = $loop->last;
                        $lgColStart = ($isLast && $remainder === 1) ? 'lg:col-start-2' : '';
                    @endphp

                    <a href="{{ route('academics.show', $program) }}"
                       class="group bg-white p-6 flex items-center gap-4 hover:bg-tpc-primary/5 transition {{ $lgColStart }}">

                        @if ($program->logo_path)
                            <img src="{{ asset('storage/' . $program->logo_path) }}"
                                 alt="{{ $program->code }} logo"
                                 class="h-16 w-16 object-contain shrink-0" loading="lazy">
                        @else
                            <div class="h-16 w-16 shrink-0 flex items-center justify-center bg-tpc-primary/10 text-2xl">🎓</div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-tpc-primary uppercase tracking-wider">
                                {{ $program->code ?? 'Program' }}
                            </p>
                            <h3 class="mt-0.5 text-sm font-bold text-tpc-ink group-hover:text-tpc-primary transition leading-snug">
                                {{ $program->name }}
                            </h3>
                            @if ($program->department)
                                <p class="mt-1 text-xs text-gray-400">{{ $program->department }}</p>
                            @endif
                            @if ($program->description)
                                <p class="mt-2 text-xs text-gray-500 leading-relaxed line-clamp-2">
                                    {{ $program->description }}
                                </p>
                            @endif
                            <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-tpc-primary group-hover:gap-2 transition-all">
                                Learn more →
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white col-span-3 py-16 text-center text-gray-400 text-sm border border-dashed border-gray-300">
                        No programs found.
                    </div>
                @endforelse
            </div>
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
                   class="inline-flex items-center border-2 border-white bg-white px-6 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    Contact Us
                </a>
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center border-2 border-white/60 px-6 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Latest Updates →
                </a>
            </div>
        </div>
    </section>

@endsection
