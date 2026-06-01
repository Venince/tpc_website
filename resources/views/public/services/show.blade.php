{{-- resources/views/public/services/show.blade.php --}}
@extends('layouts.site')

@section('title', $service->title)

@section('content')

    {{-- ── HEADER ──────────────────────────────────────────────────────── --}}
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
                {{-- <span class="inline-block bg-white/20 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full backdrop-blur-sm border border-white/20 mb-4">
                    Services
                </span> --}}
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    {{ $service->title }}
                </h1>
                @if ($service->description)
                    <p class="mt-3 max-w-lg text-sm text-white/60 leading-relaxed text-justify">
                        {{ $service->description }}
                    </p>
                @endif

                <div class="mt-5 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('admission') }}"
                       class="inline-flex items-center gap-2 rounded-full border-2 border-white bg-white px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:border-white hover:text-white transition">
                        How to Enroll
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center rounded-full border-2 border-white/50 bg-white/10 backdrop-blur-sm px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-white/20 hover:border-white/80 transition">
                        Ask a Question
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

    {{-- ── BODY ────────────────────────────────────────────────────────── --}}
    <section class="bg-gray-50 py-8 sm:py-14">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid gap-8 lg:grid-cols-3">

                {{-- Main content column --}}
                <div class="lg:col-span-2 space-y-6">

                    @if ($service->featured_image_path)
                        <div class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                                 alt="{{ $service->title }}"
                                 class="w-full object-cover"
                                 loading="eager">
                        </div>
                    @endif

                    @if ($service->contents->isNotEmpty())
                        @foreach ($service->contents as $block)

                            @if ($block->isText())
                                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8">
                                    @if ($block->heading)
                                        <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-5">
                                            <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm shrink-0"></span>
                                            <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ $block->heading }}</h2>
                                        </div>
                                    @endif
                                    <div class="prose prose-sm sm:prose-base prose-gray max-w-none
                                                prose-p:leading-relaxed prose-p:text-gray-600
                                                prose-headings:font-bold prose-headings:text-gray-900">
                                        {!! nl2br(e($block->body)) !!}
                                    </div>
                                </div>

                            @elseif ($block->isImage() && $block->image_path)
                                <figure class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                                    @if ($block->heading)
                                        <div class="bg-white px-6 py-4 border-b border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <span class="block h-4 w-1 bg-tpc-primary rounded-sm shrink-0"></span>
                                                <h3 class="text-sm font-bold text-gray-900">{{ $block->heading }}</h3>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="bg-gray-50">
                                        <img src="{{ asset('storage/' . $block->image_path) }}"
                                             alt="{{ $block->heading ?? $block->image_caption ?? $service->title }}"
                                             class="w-full object-cover max-h-96"
                                             loading="lazy">
                                    </div>
                                    @if ($block->image_caption)
                                        <figcaption class="bg-white px-6 py-3 text-xs text-gray-400 italic border-t border-gray-100">
                                            {{ $block->image_caption }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endif

                        @endforeach
                    @else
                        <div class="py-16 text-center border border-dashed border-gray-300 rounded-3xl bg-white">
                            <p class="text-base font-semibold text-gray-300 mb-1">Content coming soon</p>
                            <p class="text-xs text-gray-400">More details about this service will be available soon.</p>
                        </div>
                    @endif
                </div>

                {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
                <aside class="space-y-5 sm:space-y-6">

                    {{-- Social Media Card (shown when links exist) --}}
                    @if (!empty($service->social_links))
                        @php
                            $platformMeta = [
                                'facebook'  => ['label' => 'Facebook',  'color' => 'text-[#1877F2]', 'bg' => 'bg-[#1877F2]/8 hover:bg-[#1877F2]/15', 'ring' => 'ring-[#1877F2]/20', 'icon' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
                                'instagram' => ['label' => 'Instagram', 'color' => 'text-[#E1306C]', 'bg' => 'bg-[#E1306C]/8 hover:bg-[#E1306C]/15', 'ring' => 'ring-[#E1306C]/20', 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z'],
                                'twitter'   => ['label' => 'X (Twitter)', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100 hover:bg-gray-200', 'ring' => 'ring-gray-200', 'icon' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.213 5.567zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                                'youtube'   => ['label' => 'YouTube',   'color' => 'text-[#FF0000]', 'bg' => 'bg-[#FF0000]/8 hover:bg-[#FF0000]/15', 'ring' => 'ring-[#FF0000]/20', 'icon' => 'M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58zM9.75 15.02V8.98L15.5 12z'],
                                'tiktok'    => ['label' => 'TikTok',    'color' => 'text-gray-900', 'bg' => 'bg-gray-100 hover:bg-gray-200', 'ring' => 'ring-gray-200', 'icon' => 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.3 6.3 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z'],
                                'linkedin'  => ['label' => 'LinkedIn',  'color' => 'text-[#0A66C2]', 'bg' => 'bg-[#0A66C2]/8 hover:bg-[#0A66C2]/15', 'ring' => 'ring-[#0A66C2]/20', 'icon' => 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2zM4 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4z'],
                                'other'     => ['label' => 'Link',      'color' => 'text-tpc-primary', 'bg' => 'bg-tpc-primary/8 hover:bg-tpc-primary/15', 'ring' => 'ring-tpc-primary/20', 'icon' => 'M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101m-.758-4.899a4 4 0 0 0 5.656 0l4-4a4 4 0 0 0-5.656-5.656l-1.1 1.1'],
                            ];
                        @endphp
                        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-5 py-4 border-b border-gray-100">
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Follow Us</p>
                            </div>
                            <div class="p-5 space-y-2.5">
                                @foreach ($service->social_links as $link)
                                    @php
                                        $meta = $platformMeta[$link['platform']] ?? $platformMeta['other'];
                                        $displayLabel = !empty($link['label']) ? $link['label'] : $meta['label'];
                                    @endphp
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="group flex items-center gap-3 rounded-2xl px-4 py-3 ring-1 transition {{ $meta['bg'] }} {{ $meta['ring'] }}">
                                        <div class="shrink-0 h-9 w-9 rounded-xl bg-white shadow-sm flex items-center justify-center ring-1 ring-gray-100">
                                            <svg class="h-4 w-4 {{ $meta['color'] }}" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="{{ $meta['icon'] }}"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $displayLabel }}</p>
                                            <p class="text-[11px] text-gray-400 truncate">{{ parse_url($link['url'], PHP_URL_HOST) }}</p>
                                        </div>
                                        <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CTA card --}}
                    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="h-1.5 bg-tpc-primary"></div>
                        <div class="px-5 py-4 border-b border-gray-100">
                            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Interested?</p>
                        </div>
                        <div class="p-5 space-y-3">
                            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                                Want to learn more about <span class="font-semibold text-gray-800">{{ $service->title }}</span>?
                                Check our admission guide or get in touch.
                            </p>
                            <a href="{{ route('admission') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary bg-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-white hover:bg-tpc-secondary hover:border-tpc-secondary transition">
                                View Admission Guide
                            </a>
                            <a href="{{ route('contact') }}"
                               class="flex items-center justify-center rounded-full border-2 border-tpc-primary px-5 py-2.5 text-xs sm:text-sm font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
                                Contact Us
                            </a>
                        </div>
                    </div>

                    {{-- Other Services --}}
                    @php
                        $otherServices = \App\Models\Service::active()
                            ->ordered()
                            ->where('id', '!=', $service->id)
                            ->get();
                    @endphp

                    @if ($otherServices->isNotEmpty())
                        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="h-1.5 bg-tpc-primary"></div>
                            <div class="px-5 py-4 border-b border-gray-100">
                                <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Other Services</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach ($otherServices as $other)
                                    <a href="{{ route('services.show', $other) }}"
                                       class="group flex items-center gap-3 px-5 py-3.5 hover:bg-tpc-primary/5 transition">
                                        <div class="shrink-0 h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center">
                                            <svg class="h-3.5 w-3.5 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate group-hover:text-gray-900 transition leading-snug flex-1">
                                            {{ $other->title }}
                                        </p>
                                        <svg class="h-3.5 w-3.5 text-gray-300 group-hover:text-tpc-primary transition ml-auto shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
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
