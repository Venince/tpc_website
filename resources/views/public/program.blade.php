{{-- resources/views/public/program.blade.php --}}
@extends('layouts.site')

@section('title', $program->name)

@section('content')

    {{-- HEADER --}}
    <section class="relative overflow-hidden bg-tpc-secondary">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0"
             style="background: radial-gradient(ellipse at 70% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
                                radial-gradient(ellipse at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%)"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image: linear-gradient(rgba(255,255,255,0.8) 1px, transparent 1px),
                                      linear-gradient(90deg, rgba(255,255,255,0.8) 1px, transparent 1px);
                    background-size: 40px 40px;"></div>

        <div class="relative mx-auto max-w-4xl px-4 pt-10 pb-16 sm:pt-14 sm:pb-20">

            {{-- Back link --}}
            <a href="{{ route('academics') }}"
               class="inline-flex items-center gap-1.5 text-[11px] font-bold text-white/60 hover:text-white uppercase tracking-widest transition mb-8">
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Programs
            </a>

            <div class="flex flex-col items-center text-center sm:flex-row sm:items-center sm:text-left gap-5 sm:gap-8">
                @if ($program->logo_path)
                    <div class="shrink-0 h-24 w-24">
                        <img src="{{ asset('storage/' . $program->logo_path) }}"
                             alt="{{ $program->code }}"
                             class="h-full w-full object-contain drop-shadow">
                    </div>
                @endif

                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $program->name }}
                    </h1>
                    @if ($program->description)
                        <p class="mt-2 sm:mt-3 text-sm text-white/60 leading-relaxed max-w-xl text-justify">
                            {{ $program->description }}
                        </p>
                    @endif

                    <div class="mt-4 sm:mt-5 flex flex-wrap justify-center sm:justify-start gap-2 sm:gap-3">
                        <a href="{{ route('admission') }}"
                           class="inline-flex items-center gap-2 rounded-full border-2 border-white bg-white px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary
                                  hover:bg-tpc-primary hover:border-white hover:text-white transition">
                            How to Enroll
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}"
                           class="inline-flex items-center rounded-full border-2 border-white/50 bg-white/10 backdrop-blur-sm px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                  hover:bg-white/20 hover:border-white/80 transition">
                            Ask a Question
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-12">
                <path d="M0 48 C480 0 960 0 1440 48 L1440 48 L0 48 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- MAIN --}}
    <section class="bg-gray-50 overflow-x-hidden">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:py-14">
            <div class="grid gap-6 sm:gap-8 lg:grid-cols-3 w-full min-w-0">

                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-8 sm:space-y-10 min-w-0 w-full overflow-hidden">

                    {{-- PROGRAM LEADERSHIP --}}
                    @if ($head->isNotEmpty() || $coordinators->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Program Leadership</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="grid gap-4 sm:gap-5 sm:grid-cols-2">
                                @foreach ($head->merge($coordinators) as $person)
                                    <div class="person-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                        <div class="h-1.5 bg-tpc-primary"></div>
                                        <div class="p-4 sm:p-6 flex flex-col items-center text-center">
                                            @if ($person->photo_path)
                                                <img src="{{ asset('storage/' . $person->photo_path) }}"
                                                     class="person-photo h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border-4 border-tpc-primary/20 shadow-sm mb-3 sm:mb-4"
                                                     alt="{{ $person->name }}">
                                            @else
                                                <span class="person-photo h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-tpc-primary/10 flex items-center justify-center text-2xl sm:text-3xl font-bold text-tpc-primary mb-3 sm:mb-4">
                                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                                </span>
                                            @endif
                                            <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full mb-2">
                                                {{ $person->role_label }}
                                            </span>
                                            <p class="font-bold text-gray-800 text-sm sm:text-base leading-snug">{{ $person->name }}</p>
                                            @if ($person->position)
                                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ $person->position }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- INSTRUCTORS --}}
                    @if ($instructors->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Instructors</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                                <span class="text-xs text-gray-400 font-medium shrink-0">{{ $instructors->count() }} {{ Str::plural('Instructor', $instructors->count()) }}</span>
                            </div>
                            <div class="grid gap-3 sm:gap-5 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($instructors as $person)
                                    <div class="person-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                        <div class="h-1 bg-tpc-primary/30"></div>
                                        <div class="p-3 sm:p-5 flex flex-col items-center text-center">
                                            @if ($person->photo_path)
                                                <img src="{{ asset('storage/' . $person->photo_path) }}"
                                                     class="person-photo h-16 w-16 sm:h-24 sm:w-24 rounded-full object-cover border-2 border-tpc-primary/20 shadow-sm mb-2 sm:mb-3"
                                                     alt="{{ $person->name }}">
                                            @else
                                                <span class="person-photo h-16 w-16 sm:h-24 sm:w-24 rounded-full bg-tpc-primary/10 flex items-center justify-center text-xl sm:text-2xl font-bold text-tpc-primary mb-2 sm:mb-3">
                                                    {{ strtoupper(substr($person->name, 0, 1)) }}
                                                </span>
                                            @endif
                                            <p class="text-xs sm:text-sm font-bold text-gray-800 leading-snug">{{ $person->name }}</p>
                                            @if ($person->position)
                                                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5 leading-snug">{{ $person->position }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ACHIEVEMENTS --}}
                    @if ($achievements->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Achievements</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="space-y-4 sm:space-y-5">
                                @foreach ($achievements as $achievement)
                                    <div class="achievement-card bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                        <div class="h-1.5 bg-tpc-accent"></div>
                                        @if ($achievement->photo_path)
                                            <div class="achievement-img bg-gray-50 border-b border-gray-100">
                                                <img src="{{ asset('storage/' . $achievement->photo_path) }}"
                                                    class="w-full max-w-full object-cover"
                                                     alt="{{ $achievement->title }}" loading="lazy">
                                            </div>
                                        @endif
                                        <div class="p-4 sm:p-6">
                                            @if ($achievement->year)
                                                <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full mb-2">
                                                    {{ $achievement->year }}
                                                </span>
                                            @endif
                                            <p class="font-bold text-gray-800 text-sm sm:text-base leading-snug">{{ $achievement->title }}</p>
                                            @if ($achievement->description)
                                                <div class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-gray-500 leading-relaxed space-y-2">
                                                    @foreach (explode("\n", $achievement->description) as $paragraph)
                                                        @if (trim($paragraph) !== '')
                                                            <p>{{ trim($paragraph) }}</p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- EMPTY STATE --}}
                    @if ($head->isEmpty() && $coordinators->isEmpty() && $instructors->isEmpty() && $achievements->isEmpty())
                        <div class="py-16 sm:py-24 text-center border border-dashed border-gray-300 rounded-2xl bg-white">
                            <p class="text-base sm:text-lg font-semibold text-gray-300 mb-1">No details yet</p>
                            <p class="text-xs sm:text-sm text-gray-400">More details about this program will be available soon.</p>
                        </div>
                    @endif

                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-4 sm:space-y-6 min-w-0 w-full overflow-hidden">

                    {{-- Interested CTA --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Interested?</p>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2 sm:space-y-3">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                                Ready to enroll in <span class="font-semibold text-gray-800">{{ $program->code }}</span>? Check out the admission guide or reach out to us.
                            </p>
                            <a href="{{ route('admission') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                      hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Admission Guide
                            </a>
                            <a href="{{ route('contact') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary
                                      hover:bg-tpc-primary hover:text-white transition">
                                Contact Us
                            </a>
                        </div>
                    </div>

                    {{-- Other Programs --}}
                    @if ($otherPrograms->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Other Programs</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach ($otherPrograms as $other)
                                    <a href="{{ route('academics.show', $other) }}"
                                    class="group flex items-center gap-3 px-4 sm:px-5 py-3 sm:py-3.5 hover:bg-tpc-primary/5 transition">
                                        @if ($other->logo_path)
                                            <div class="shrink-0 h-8 w-8 sm:h-9 sm:w-9 rounded-lg flex items-center justify-center p-1">
                                                <img src="{{ asset('storage/' . $other->logo_path) }}"
                                                    class="h-full w-full object-cover" alt="{{ $other->code }}">
                                            </div>
                                        @else
                                            <div class="shrink-0 h-8 w-8 sm:h-9 sm:w-9 rounded-lg flex items-center justify-center text-sm sm:text-base">
                                                🎓
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold text-tpc-primary uppercase tracking-wider">{{ $other->code }}</p>
                                            <p class="text-xs text-gray-500 truncate group-hover:text-gray-800 transition leading-snug">{{ $other->name }}</p>
                                        </div>
                                        <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary transition ml-auto shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                            <div class="p-4 sm:p-5 border-t border-gray-100">
                                <a href="{{ route('org-chart') }}"
                                class="flex items-center justify-center gap-1.5 rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                        hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                    Organizational Chart
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Org Chart button always visible --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
                            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Organizational Chart</p>
                        </div>
                        <div class="p-4 sm:p-5 space-y-2 sm:space-y-3">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                                See the full administration and academic leadership structure of Talibon Polytechnic College.
                            </p>
                            <a href="{{ route('org-chart') }}"
                            class="flex items-center justify-center gap-1.5 rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white
                                    hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                Organizational Chart
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
