@extends('admin.layout')

@section('title', $service->title)

@section('page_actions')
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2 min-w-0">
            <a href="{{ route('admin.services.index') }}"
               class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-base font-bold text-tpc-ink truncate">{{ $service->title }}</h1>
                <p class="text-xs text-tpc-ink/50">/services/{{ $service->slug }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('services.show', $service) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 px-3.5 py-2 text-xs font-bold text-gray-500 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                Preview
            </a>
            <a href="{{ route('admin.services.edit', $service) }}"
               class="inline-flex items-center gap-1.5 rounded-full border border-tpc-primary/30 px-3.5 py-2 text-xs font-bold text-tpc-primary hover:bg-tpc-primary/5 transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Edit Service
            </a>
            <a href="{{ route('admin.services.contents.create', $service) }}"
               class="inline-flex items-center gap-1.5 rounded-full bg-tpc-primary px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-tpc-secondary transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Section
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- LEFT: Service meta --}}
        <aside class="lg:col-span-1 space-y-4">
            {{-- Featured image --}}
            @if ($service->featured_image_path)
                <div class="rounded-2xl overflow-hidden border border-gray-100">
                    <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                         class="w-full object-cover alt="">
                </div>
            @endif

            {{-- Info card --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50 divide-y divide-gray-100">
                <div class="px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Status</p>
                    @if ($service->is_active)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-green-200/60">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-500 ring-1 ring-gray-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                        </span>
                    @endif
                </div>
                <div class="px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Display Order</p>
                    <p class="text-sm text-gray-700 font-mono">{{ $service->order }}</p>
                </div>
                @if ($service->description)
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Description</p>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $service->description }}</p>
                    </div>
                @endif
                <div class="px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Sections</p>
                    <p class="text-sm font-bold text-tpc-primary">{{ $service->contents->count() }}</p>
                </div>
            </div>

            {{-- Danger zone --}}
            <div class="rounded-2xl border border-red-100 bg-red-50/50 px-4 py-4">
                <p class="text-xs font-bold text-red-700 mb-2">Danger Zone</p>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                      onsubmit="return confirm('Permanently delete this service and all its content?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                        Delete Service
                    </button>
                </form>
            </div>
        </aside>

        {{-- RIGHT: Content blocks --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-gray-700">Content Sections</h2>
                @if ($service->contents->isNotEmpty())
                    <p class="text-xs text-gray-400">Drag rows to reorder</p>
                @endif
            </div>

            @if ($service->contents->isEmpty())
                <div class="py-16 text-center border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <svg class="h-10 w-10 mx-auto text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-300 mb-1">No content sections yet</p>
                    <p class="text-xs text-gray-400 mb-4">Add text or image blocks to build out this service page.</p>
                    <a href="{{ route('admin.services.contents.create', $service) }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-tpc-primary px-5 py-2 text-xs font-bold text-white hover:bg-tpc-secondary transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Add First Section
                    </a>
                </div>
            @else
                <div id="sortable-contents" class="space-y-3">
                    @foreach ($service->contents as $content)
                        <div class="content-row flex items-start gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:border-tpc-primary/30 hover:shadow-md transition-all duration-200"
                             data-id="{{ $content->id }}">

                            {{-- Drag handle --}}
                            <span class="drag-handle mt-0.5 shrink-0 cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                                </svg>
                            </span>

                            {{-- Badge --}}
                            <div class="mt-0.5 shrink-0">
                                @if ($content->isImage())
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-purple-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-purple-600 ring-1 ring-purple-200/60">
                                        <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                        Image
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-600 ring-1 ring-blue-200/60">
                                        <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                                        </svg>
                                        Text
                                    </span>
                                @endif
                            </div>

                            {{-- Preview --}}
                            <div class="flex-1 min-w-0">
                                @if ($content->heading)
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $content->heading }}</p>
                                @endif
                                @if ($content->isImage() && $content->image_path)
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <img src="{{ asset('storage/' . $content->image_path) }}"
                                             class="h-12 w-20 rounded-lg object-cover border border-gray-100" alt="">
                                        @if ($content->image_caption)
                                            <p class="text-xs text-gray-400 italic truncate">{{ $content->image_caption }}</p>
                                        @endif
                                    </div>
                                @elseif ($content->body)
                                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mt-0.5">{{ $content->body }}</p>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('admin.services.contents.edit', [$service, $content]) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-tpc-primary/60 hover:bg-tpc-primary/8 hover:text-tpc-primary transition" title="Edit">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.services.contents.destroy', [$service, $content]) }}" method="POST"
                                      onsubmit="return confirm('Delete this content section?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-red-400/70 hover:bg-red-50 hover:text-red-600 transition" title="Delete">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
{{--
    Drag-to-reorder using SortableJS (CDN).
    If you already have SortableJS in your project, remove the script tag.
--}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('sortable-contents');
    if (!el) return;

    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd: function () {
            const order = [...el.querySelectorAll('.content-row')]
                .map(row => parseInt(row.dataset.id));

            fetch('{{ route('admin.services.contents.reorder', $service) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ order }),
            });
        }
    });
});
</script>
@endpush
