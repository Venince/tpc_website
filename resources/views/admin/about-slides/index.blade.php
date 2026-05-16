{{-- resources/views/admin/about-slides/index.blade.php --}}
@extends('admin.layout')

@section('title', 'About Slides')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-8">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">About Slides</h1>
        </div>
        <p class="text-sm text-gray-500 pl-3">Manage the image carousel shown in the About section on the homepage.</p>
    </div>
    <a href="{{ route('admin.about-slides.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-tpc-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-tpc-secondary active:scale-95 transition-all duration-150 shadow-sm shrink-0">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Add Slide
    </a>
</div>

@if($slides->isEmpty())
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="h-16 w-16 rounded-2xl bg-tpc-primary/8 flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M13.5 12h.008v.008H13.5V12zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/>
            </svg>
        </div>
        <p class="text-sm font-semibold text-gray-700 mb-1">No slides yet</p>
        <p class="text-xs text-gray-400 mb-5">Add your first slide to populate the homepage carousel.</p>
        <a href="{{ route('admin.about-slides.create') }}"
           class="inline-flex items-center gap-2 bg-tpc-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-tpc-secondary transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Slide
        </a>
    </div>

@else

    {{-- Stats bar --}}
    @php
        $activeCount = $slides->where('is_active', true)->count();
        $totalCount  = $slides->count();
    @endphp
    <div class="flex items-center gap-4 mb-5 text-xs text-gray-500">
        <span><span class="font-bold text-gray-800">{{ $totalCount }}</span> total</span>
        <span class="h-3 w-px bg-gray-200"></span>
        <span><span class="font-bold text-green-600">{{ $activeCount }}</span> active</span>
        <span class="h-3 w-px bg-gray-200"></span>
        <span><span class="font-bold text-gray-400">{{ $totalCount - $activeCount }}</span> hidden</span>
    </div>

    {{-- ── MOBILE: card list (< sm) ── --}}
    <div class="flex flex-col gap-3 sm:hidden">
        @foreach($slides as $slide)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                 x-data="{ deleting: false }">

                {{-- Image strip --}}
                <div class="relative h-32 w-full bg-gray-50 overflow-hidden">
                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                         alt="{{ $slide->title ?: 'Slide ' . $slide->sort_order }}"
                         class="h-full w-full object-cover">
                    {{-- Overlay badges --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-2 left-3 right-3 flex items-end justify-between">
                        <p class="text-white font-semibold text-sm leading-tight drop-shadow truncate max-w-[70%]">
                            {{ $slide->title ?: 'Untitled' }}
                        </p>
                        @if($slide->is_active)
                            <span class="inline-flex items-center gap-1 bg-green-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white/70 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                Hidden
                            </span>
                        @endif
                    </div>
                    {{-- Order badge --}}
                    <div class="absolute top-2 left-3">
                        <span class="bg-black/40 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            #{{ $slide->sort_order }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex border-t border-gray-100">
                    <a href="{{ route('admin.about-slides.edit', $slide) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-bold text-tpc-primary hover:bg-tpc-primary/5 active:bg-tpc-primary/10 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                        </svg>
                        Edit
                    </a>
                    <div class="w-px bg-gray-100"></div>
                    <form action="{{ route('admin.about-slides.destroy', $slide) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Delete this slide? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-1.5 py-3 text-sm font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── DESKTOP: visual grid (≥ sm) ── --}}
    <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($slides as $slide)
            <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:border-tpc-primary/20 transition-all duration-200">

                {{-- Image --}}
                <div class="relative h-44 w-full bg-gray-50 overflow-hidden">
                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                         alt="{{ $slide->title ?: 'Slide ' . $slide->sort_order }}"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                    {{-- Order badge --}}
                    <div class="absolute top-3 left-3">
                        <span class="bg-black/40 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            #{{ $slide->sort_order }}
                        </span>
                    </div>

                    {{-- Status badge --}}
                    <div class="absolute top-3 right-3">
                        @if($slide->is_active)
                            <span class="inline-flex items-center gap-1.5 bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>Active
                            </span>
                        @else
                            <span class="inline-flex items-center bg-black/40 backdrop-blur-sm text-white/60 text-xs font-bold px-2.5 py-1 rounded-full">
                                Hidden
                            </span>
                        @endif
                    </div>

                    {{-- Title overlay --}}
                    <div class="absolute bottom-3 left-3 right-3">
                        <p class="text-white font-semibold text-sm leading-snug drop-shadow truncate">
                            {{ $slide->title ?: 'Untitled Slide' }}
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100">
                    <a href="{{ route('admin.about-slides.edit', $slide) }}"
                       class="inline-flex items-center gap-1.5 text-sm font-bold text-tpc-primary hover:text-tpc-secondary transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                        Edit Slide
                    </a>
                    <form action="{{ route('admin.about-slides.destroy', $slide) }}" method="POST"
                          onsubmit="return confirm('Delete this slide? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1 text-xs font-bold text-red-400 hover:text-red-600 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        {{-- Add new card --}}
        <a href="{{ route('admin.about-slides.create') }}"
           class="flex flex-col items-center justify-center gap-3 h-full min-h-[220px] rounded-2xl border-2 border-dashed border-gray-200 hover:border-tpc-primary/40 hover:bg-tpc-primary/3 text-gray-400 hover:text-tpc-primary transition-all duration-200 group">
            <div class="h-10 w-10 rounded-xl border-2 border-current flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-sm font-bold">Add Slide</span>
        </a>
    </div>

@endif

@endsection
