{{-- resources/views/public/academics.blade.php --}}
@extends('layouts.site')

@section('title', 'Academics')

@section('content')
    {{-- PAGE HEADER --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Academics</p>
                    <h1 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink sm:text-4xl">
                        Academic Programs
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-tpc-ink/70">
                        Explore our academic offerings designed to build competence, confidence, and career readiness.
                    </p>
                </div>

                <a href="{{ route('admission') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-4 py-2 text-sm font-medium text-tpc-primary transition hover:bg-tpc-primary/5">
                    Admission Guide →
                </a>
            </div>
        </div>
    </section>

    {{-- PROGRAMS GRID --}}
    <section class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 pb-20">

            <div class="flex flex-wrap justify-center gap-6">
                @forelse ($programs as $program)
                    <article
                        class="group flex-none basis-full sm:basis-[calc(50%-0.75rem)] lg:basis-[calc(33.333%-1rem)]
                            rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm transition
                            hover:-translate-y-0.5 hover:border-tpc-primary/30 hover:shadow-md"
                    >
                        <div class="flex flex-col items-center text-center">
                            {{-- Logo --}}
                            @if ($program->logo_path)
                                <img
                                    src="{{ asset('storage/' . $program->logo_path) }}"
                                    alt="{{ $program->code }} logo"
                                    class="h-20 w-20 object-contain"
                                    loading="lazy"
                                />
                            @else
                                <span class="inline-flex h-20 w-20 items-center justify-center text-tpc-secondary text-3xl">
                                    🎓
                                </span>
                            @endif

                            {{-- Code --}}
                            <p class="mt-3 inline-flex items-center rounded-full bg-tpc-primary/10 px-3 py-1 text-xs font-medium text-tpc-secondary">
                                {{ $program->code }}
                            </p>

                            {{-- Name --}}
                            <h2 class="mt-3 text-lg font-semibold text-tpc-ink group-hover:text-tpc-primary transition">
                                {{ $program->name }}
                            </h2>

                            {{-- Department (optional) --}}
                            @if ($program->department)
                                <p class="mt-2 text-xs font-medium text-tpc-ink/60">
                                    Department: <span class="text-tpc-ink/80">{{ $program->department }}</span>
                                </p>
                            @endif
                        </div>

                        {{-- Description --}}
                        <p class="mt-4 text-sm leading-relaxed text-tpc-ink/70 text-justify">
                            {{ $program->description ?: 'Short program description will appear here.' }}
                        </p>

                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xs text-tpc-ink/50">Slug: {{ $program->slug }}</span>

                            @if($program->is_active)
                                <span class="rounded-full bg-tpc-accent/30 px-2 py-1 text-xs font-medium text-tpc-secondary">Active</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">Inactive</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="w-full rounded-2xl border border-dashed border-tpc-primary/30 p-10 text-center text-tpc-ink/70">
                        No programs found.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-tpc-primary/5">
        <div class="max-w-7xl mx-auto px-4 py-14">
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-8 shadow-sm lg:flex lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-tpc-ink">Need help choosing a program?</h2>
                    <p class="mt-2 text-sm text-tpc-ink/70">
                        Contact us for guidance on requirements, enrollment, and academic support.
                    </p>
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row lg:mt-0">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-tpc-primary px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-tpc-secondary">
                        Contact Us
                    </a>
                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary shadow-sm transition hover:bg-tpc-primary/5">
                        Latest Updates →
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
