{{-- resources/views/public/program.blade.php --}}
@extends('layouts.site')

@section('title', $program->name)

@section('content')

    {{-- HEADER --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <a href="{{ route('academics') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/60 hover:text-white transition mb-4">
                ← Back to Programs
            </a>
            <div class="flex items-start gap-5">
                @if ($program->logo_path)
                    <img src="{{ asset('storage/' . $program->logo_path) }}"
                         alt="{{ $program->code }}"
                         class="h-16 w-16 object-contain shrink-0 bg-white/10 p-1 rounded-xl">
                @endif
                <div>
                    <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">
                        {{ $program->code }}
                        @if ($program->department) &middot; {{ $program->department }} @endif
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">{{ $program->name }}</h1>
                    @if ($program->description)
                        <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">{{ $program->description }}</p>
                    @endif
                </div>
            </div>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('admission') }}"
                   class="inline-flex items-center border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    How to Enroll
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center border-2 border-white/60 px-5 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Ask a Question
                </a>
            </div>
        </div>
    </section>

    {{-- MAIN --}}
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid gap-10 lg:grid-cols-3">

                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-14">

                    {{-- PROGRAM HEAD + COORDINATOR --}}
                    @if ($head->isNotEmpty() || $coordinators->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Program Leadership</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="grid gap-5 sm:grid-cols-2">
                                @foreach ($head->merge($coordinators) as $person)
                                    <div class="flex flex-col items-center text-center border border-gray-200 p-6 bg-tpc-primary/[0.02] hover:bg-tpc-primary/5 transition">
                                        {{-- Large square photo --}}
                                        @if ($person->photo_path)
                                            <img src="{{ asset('storage/' . $person->photo_path) }}"
                                                 class="h-36 w-36 rounded-full object-cover border-4 border-tpc-primary/20 shadow-sm mb-4"
                                                 alt="{{ $person->name }}">
                                        @else
                                            <span class="h-36 w-36 rounded-full bg-tpc-primary/10 flex items-center justify-center text-4xl font-bold text-tpc-primary mb-4">
                                                {{ strtoupper(substr($person->name, 0, 1)) }}
                                            </span>
                                        @endif
                                        <p class="text-[10px] font-bold tracking-widest text-tpc-primary uppercase mb-1">
                                            {{ $person->role_label }}
                                        </p>
                                        <p class="font-bold text-tpc-ink text-base leading-snug">{{ $person->name }}</p>
                                        @if ($person->position)
                                            <p class="text-sm text-gray-500 mt-0.5">{{ $person->position }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- INSTRUCTORS --}}
                    @if ($instructors->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Instructors</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="grid gap-px bg-gray-200 border border-gray-200 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($instructors as $person)
                                    <div class="bg-white p-5 flex flex-col items-center text-center hover:bg-tpc-primary/5 transition">
                                        @if ($person->photo_path)
                                            <img src="{{ asset('storage/' . $person->photo_path) }}"
                                                 class="h-36 w-36 rounded-full object-cover border-2 border-tpc-primary/20 shadow-sm mb-3"
                                                 alt="{{ $person->name }}">
                                        @else
                                            <span class="h-36 w-36 rounded-full bg-tpc-primary/10 flex items-center justify-center text-2xl font-bold text-tpc-primary mb-3">
                                                {{ strtoupper(substr($person->name, 0, 1)) }}
                                            </span>
                                        @endif
                                        <p class="text-sm font-bold text-tpc-ink leading-snug">{{ $person->name }}</p>
                                        @if ($person->position)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $person->position }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ACHIEVEMENTS --}}
                    @if ($achievements->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Achievements</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            <div class="space-y-6">
                                @foreach ($achievements as $achievement)
                                    <div class="border border-gray-200 hover:bg-gray-50 transition overflow-hidden">
                                        {{-- Full-width image on top --}}
                                        @if ($achievement->photo_path)
                                            <img src="{{ asset('storage/' . $achievement->photo_path) }}"
                                                 class="w-full object-contain"
                                                 alt="{{ $achievement->title }}" loading="lazy">
                                        @endif
                                        <div class="p-5">
                                            @if ($achievement->year)
                                                <p class="text-[10px] font-bold tracking-widest text-tpc-primary uppercase mb-1">{{ $achievement->year }}</p>
                                            @endif
                                            <p class="font-bold text-tpc-ink text-base leading-snug">{{ $achievement->title }}</p>
                                            @if ($achievement->description)
                                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">{{ $achievement->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- EMPTY STATE --}}
                    @if ($head->isEmpty() && $coordinators->isEmpty() && $instructors->isEmpty() && $achievements->isEmpty())
                        <div class="py-20 text-center text-gray-400 text-sm border border-dashed border-gray-200 rounded-xl">
                            More details about this program will be available soon.
                        </div>
                    @endif

                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-6">
                    <div class="border border-gray-200">
                        <div class="bg-tpc-primary px-4 py-3">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">Interested?</p>
                        </div>
                        <div class="p-5 space-y-3">
                            <p class="text-sm text-gray-600">Ready to enroll in <span class="font-semibold text-tpc-ink">{{ $program->code }}</span>?</p>
                            <a href="{{ route('admission') }}"
                               class="flex items-center justify-center border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Admission Guide
                            </a>
                            <a href="{{ route('contact') }}"
                               class="flex items-center justify-center border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Contact Us
                            </a>
                        </div>
                    </div>

                    @if ($otherPrograms->isNotEmpty())
                        <div class="border border-gray-200">
                            <div class="bg-tpc-primary px-4 py-3">
                                <p class="text-xs font-bold text-white uppercase tracking-widest">Other Programs</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach ($otherPrograms as $other)
                                    <a href="{{ route('academics.show', $other) }}"
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-tpc-primary/5 transition group">
                                        @if ($other->logo_path)
                                            <img src="{{ asset('storage/' . $other->logo_path) }}"
                                                 class="h-8 w-8 object-contain shrink-0" alt="{{ $other->code }}">
                                        @else
                                            <span class="h-8 w-8 flex items-center justify-center bg-tpc-primary/10 text-base shrink-0">🎓</span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-tpc-primary">{{ $other->code }}</p>
                                            <p class="text-xs text-gray-500 truncate group-hover:text-tpc-ink transition">{{ $other->name }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>

            </div>
        </div>
    </section>

@endsection
