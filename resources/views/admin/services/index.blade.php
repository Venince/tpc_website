@extends('admin.layout')

@section('title', 'Services')

@section('page_actions')
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-tpc-ink">Services</h1>
            <p class="text-xs text-tpc-ink/50 mt-0.5">Manage all public-facing services</p>
        </div>
        <a href="{{ route('admin.services.create') }}"
           class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-tpc-secondary transition-all hover:shadow-md active:scale-95">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            New Service
        </a>
    </div>
@endsection

@section('content')

    @if ($services->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 px-4 text-center border-2 border-dashed border-gray-100 rounded-3xl bg-gray-50/50">
            <div class="h-16 w-16 rounded-2xl bg-tpc-primary/8 flex items-center justify-center mb-4">
                <svg class="h-7 w-7 text-tpc-primary/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-gray-400">No services yet</p>
            <p class="text-xs text-gray-400 mt-1 mb-5">Create your first service to get started.</p>
            <a href="{{ route('admin.services.create') }}"
               class="inline-flex items-center gap-1.5 rounded-full bg-tpc-primary px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-tpc-secondary transition active:scale-95">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Create Service
            </a>
        </div>
    @else
        {{-- ── MOBILE CARDS (hidden on sm+) ───────────────────────── --}}
        <div class="space-y-3 sm:hidden">
            @foreach ($services as $service)
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    @if ($service->featured_image_path)
                        <div class="h-28 w-full overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                                 class="h-full w-full object-cover" alt="">
                        </div>
                    @else
                        <div class="h-1.5 w-full bg-gradient-to-r from-tpc-primary/60 to-tpc-primary/20"></div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 truncate text-sm">{{ $service->title }}</p>
                                <p class="text-[11px] text-gray-400 font-mono truncate">/services/{{ $service->slug }}</p>
                            </div>
                            @if ($service->is_active)
                                <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-[11px] font-semibold text-green-700 ring-1 ring-green-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-500 ring-1 ring-gray-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex items-center gap-1 rounded-full bg-tpc-primary/8 px-2.5 py-0.5 text-[11px] font-semibold text-tpc-primary">
                                {{ $service->contents_count ?? $service->contents()->count() }}
                                <span class="font-normal text-tpc-primary/70">sections</span>
                            </span>
                        </div>

                        <div class="flex items-center gap-2 border-t border-gray-50 pt-3">
                            <a href="{{ route('admin.services.show', $service) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 py-2 text-xs font-semibold text-gray-600 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                View
                            </a>
                            <a href="{{ route('admin.services.edit', $service) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-tpc-primary/25 py-2 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                  onsubmit="return confirm('Delete this service and all its content?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-100 text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── DESKTOP TABLE (hidden below sm) ───────────────────────── --}}
        <div class="hidden sm:block overflow-x-auto rounded-2xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Service</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Sections</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @foreach ($services as $service)
                        <tr class="group hover:bg-tpc-primary/[0.02] transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($service->featured_image_path)
                                        <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                                             class="h-11 w-11 rounded-xl object-cover border border-gray-100 shrink-0 shadow-sm"
                                             alt="">
                                    @else
                                        <span class="h-11 w-11 rounded-xl bg-tpc-primary/8 flex items-center justify-center shrink-0 border border-tpc-primary/10">
                                            <svg class="h-4 w-4 text-tpc-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 truncate">{{ $service->title }}</p>
                                        <p class="text-xs text-gray-400 truncate font-mono mt-0.5">/services/{{ $service->slug }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-full bg-tpc-primary/8 px-2.5 py-0.5 text-xs font-semibold text-tpc-primary">
                                    {{ $service->contents_count ?? $service->contents()->count() }}
                                    <span class="font-normal text-tpc-primary/70">sections</span>
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                @if ($service->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-[11px] font-semibold text-green-700 ring-1 ring-green-200/60">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-500 ring-1 ring-gray-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.services.show', $service) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-tpc-primary/50 hover:bg-tpc-primary/8 hover:text-tpc-primary transition"
                                       title="View">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-tpc-primary/50 hover:bg-tpc-primary/8 hover:text-tpc-primary transition"
                                       title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                          onsubmit="return confirm('Delete this service and all its content?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-red-400/60 hover:bg-red-50 hover:text-red-600 transition"
                                                title="Delete">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($services->hasPages())
            <div class="mt-5">{{ $services->links() }}</div>
        @endif
    @endif

@endsection
