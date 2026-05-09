{{-- resources/views/public/admission.blade.php --}}
@extends('layouts.site')

@section('title', 'Admission')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Talibon Polytechnic College</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Enrollment & Requirements</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75 leading-relaxed">
                Learn the enrollment steps and prepare the needed documents to become a student of Talibon Polytechnic College.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="#requirements"
                   class="inline-flex items-center border-2 border-white bg-white px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                    View Requirements
                </a>
                <a href="#process"
                   class="inline-flex items-center border-2 border-white/60 px-5 py-2.5 text-sm font-bold text-white hover:bg-white hover:text-tpc-primary transition">
                    Enrollment Process
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white/75 hover:text-white transition">
                    Need help? Contact us →
                </a>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid gap-8 lg:grid-cols-3">

                {{-- LEFT MAIN --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Requirements (freshmen + transferee side by side) --}}
                    @if (($sections->has('freshmen') && $sections['freshmen']->is_visible) ||
                         ($sections->has('transferee') && $sections['transferee']->is_visible))
                        <div id="requirements" class="scroll-mt-24">
                            <div class="flex items-center gap-4 mb-5">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Admission Requirements</h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <p class="text-sm text-gray-500 mb-5">Bring original copies and photocopies as applicable.</p>

                            <div class="grid gap-px bg-gray-200 border border-gray-200 sm:grid-cols-2">
                                @foreach (['freshmen', 'transferee'] as $key)
                                    @if ($sections->has($key) && $sections[$key]->is_visible)
                                        @php $sec = $sections[$key]; @endphp
                                        <div class="bg-white p-6">
                                            <p class="text-[11px] font-bold tracking-widest text-tpc-primary uppercase mb-4">
                                                {{ $sec->label }}
                                            </p>
                                            <ul class="space-y-2 text-sm text-gray-700">
                                                @foreach ($sec->items as $item)
                                                    <li class="flex items-start gap-2">
                                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-tpc-primary shrink-0"></span>
                                                        {{ $item->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Requirements Note callout --}}
                            @if ($sections->has('requirements_note') && $sections['requirements_note']->is_visible && $sections['requirements_note']->note)
                                <div class="mt-4 border-l-4 border-tpc-primary bg-tpc-primary/5 px-5 py-4">
                                    <p class="text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">
                                        {{ $sections['requirements_note']->label }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $sections['requirements_note']->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Enrollment Process --}}
                    @if ($sections->has('process') && $sections['process']->is_visible)
                        @php $process = $sections['process']; @endphp
                        <div id="process" class="scroll-mt-24">
                            <div class="flex items-center gap-4 mb-5">
                                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">
                                    {{ $process->label }}
                                </h2>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <div class="border border-gray-200 divide-y divide-gray-200">
                                @foreach ($process->items as $i => $step)
                                    <div class="flex gap-5 p-5 hover:bg-gray-50 transition">
                                        <span class="shrink-0 flex h-8 w-8 items-center justify-center bg-tpc-primary text-white text-sm font-bold">
                                            {{ $i + 1 }}
                                        </span>
                                        <div>
                                            <p class="font-bold text-sm text-tpc-ink">{{ $step->title }}</p>
                                            @if ($step->body)
                                                <p class="mt-0.5 text-sm text-gray-500">{{ $step->body }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($process->note)
                                <div class="mt-4 border-l-4 border-tpc-primary bg-tpc-primary/5 px-5 py-4">
                                    <p class="text-sm text-gray-600">{{ $process->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Explore Programs CTA --}}
                    <div class="border-l-4 border-tpc-primary bg-gray-50 border border-gray-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="font-bold text-tpc-ink">Explore programs before enrolling</p>
                            <p class="mt-1 text-sm text-gray-500">View all academic programs offered by Talibon Polytechnic College.</p>
                        </div>
                        <div class="flex flex-wrap gap-3 shrink-0">
                            <a href="{{ route('academics') }}"
                               class="inline-flex items-center border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Programs
                            </a>
                            <a href="{{ route('news.index') }}"
                               class="inline-flex items-center border-2 border-tpc-primary px-5 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Latest Updates →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-6">

                    {{-- Office Hours --}}
                    @if ($sections->has('office_hours') && $sections['office_hours']->is_visible)
                        @php $oh = $sections['office_hours']; @endphp
                        <div class="border border-gray-200">
                            <div class="bg-tpc-primary px-4 py-3">
                                <p class="text-xs font-bold text-white uppercase tracking-widest">{{ $oh->label }}</p>
                            </div>
                            <div class="p-5 divide-y divide-gray-100">
                                @foreach ($oh->items as $row)
                                    <div class="flex items-center justify-between py-3 text-sm">
                                        <span class="text-gray-600">{{ $row->title }}</span>
                                        <span class="font-bold text-tpc-ink">{{ $row->body }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($oh->note)
                                <div class="border-t border-gray-200 bg-tpc-primary/5 px-5 py-4">
                                    <p class="text-xs font-bold text-tpc-primary uppercase tracking-wide mb-1">Tip</p>
                                    <p class="text-sm text-gray-600">{{ $oh->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Admissions --}}
                    <div class="border border-gray-200">
                        <div class="bg-tpc-primary px-4 py-3">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">Contact Admissions</p>
                        </div>
                        <div class="p-5">
                            <p class="text-sm text-gray-600">
                                Reach out for enrollment concerns, schedule, and guidance.
                            </p>
                            <a href="{{ route('contact') }}"
                               class="mt-4 flex items-center justify-center border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                Go to Contact Page
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

@endsection
