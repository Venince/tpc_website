@extends('admin.layout', ['title' => $service->title])

@section('content')
    <x-admin.page-header :title="$service->title" :subtitle="'/services/' . $service->slug"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => null]">
        <x-admin.button :href="route('services.show', $service)" target="_blank" rel="noopener" variant="secondary" icon="external">Preview</x-admin.button>
        <x-admin.button :href="route('admin.services.edit', $service)" variant="secondary" icon="pencil">Edit service</x-admin.button>
        <x-admin.button :href="route('admin.services.contents.create', $service)" icon="plus">Add section</x-admin.button>
    </x-admin.page-header>

    <div class="grid items-start gap-4 lg:grid-cols-3">
        {{-- LEFT: meta --}}
        <aside class="space-y-4 lg:order-2" aria-label="Service details">
            @if ($service->featured_image_path)
                <div class="overflow-hidden rounded-xl border border-ui-line bg-white">
                    <img src="{{ asset('storage/' . $service->featured_image_path) }}" alt="" class="max-h-56 w-full object-cover">
                </div>
            @endif

            <x-admin.card title="Overview">
                <dl class="space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-ui-muted">Status</dt>
                        <dd>
                            @if ($service->is_active)
                                <x-admin.badge type="success" dot>Active</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral" dot>Inactive</x-admin.badge>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-ui-muted">Sections</dt>
                        <dd class="font-medium text-ui-ink tabular-nums">{{ $service->contents->count() }}</dd>
                    </div>
                    @if ($service->description)
                        <div>
                            <dt class="text-ui-muted">Description</dt>
                            <dd class="mt-1 text-ui-ink">{{ $service->description }}</dd>
                        </div>
                    @endif
                    @if (! empty($service->social_links))
                        <div>
                            <dt class="text-ui-muted">Social links</dt>
                            <dd class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($service->social_links as $link)
                                    @php $displayLabel = ! empty($link['label']) ? $link['label'] : ucfirst($link['platform']); @endphp
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                                       class="ui-badge ui-badge-neutral transition-colors hover:bg-brand-50 hover:text-brand-dark hover:ring-brand-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                                        {{ $displayLabel }}<x-admin.icon name="external" class="h-3 w-3" />
                                    </a>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-admin.card>

            <x-admin.card title="Downloadable files" :padding="false">
                <x-slot:actions>
                    <x-admin.button :href="route('admin.services.edit', $service)" variant="ghost" size="sm">Manage</x-admin.button>
                </x-slot:actions>
                @if ($service->attachments->isEmpty())
                    <p class="px-5 py-4 text-sm text-ui-muted">No files attached. Use <em>Edit service</em> to add forms or documents.</p>
                @else
                    <ul class="divide-y divide-ui-line">
                        @foreach ($service->attachments as $file)
                            <li>
                                <a href="{{ $file->file_url }}" target="_blank" rel="noopener"
                                   class="flex items-center gap-3 px-5 py-2.5 transition-colors hover:bg-ui-bg focus-visible:outline-none focus-visible:bg-ui-bg">
                                    <x-admin.badge type="neutral" class="uppercase">{{ $file->extension ?: 'file' }}</x-admin.badge>
                                    <span class="min-w-0 flex-1 truncate text-sm text-ui-ink">{{ $file->label }}</span>
                                    <span class="shrink-0 text-xs text-ui-muted">{{ $file->human_size }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.card>

            <x-admin.card title="Achievements" :padding="false">
                <x-slot:actions>
                    <x-admin.button :href="route('admin.services.achievements.create', $service)" size="sm" icon="plus">Add</x-admin.button>
                </x-slot:actions>
                @if ($service->achievements->isEmpty())
                    <p class="px-5 py-4 text-sm text-ui-muted">No achievements added yet.</p>
                @else
                    <ul id="sortable-achievements" class="divide-y divide-ui-line">
                        @foreach ($service->achievements as $achievement)
                            @php $photoCount = $achievement->images->count(); @endphp
                            <li data-id="{{ $achievement->id }}" class="flex items-center gap-3 bg-white px-4 py-3">
                                <span class="drag-handle cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                                    <x-admin.icon name="grip" class="h-4 w-4" />
                                </span>
                                @if ($photoCount > 0)
                                    <img src="{{ asset('storage/' . $achievement->images->first()->path) }}" alt="" class="ui-thumb" loading="lazy">
                                @else
                                    <span class="ui-thumb grid place-items-center text-gray-400"><x-admin.icon name="star" class="h-5 w-5" /></span>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-ui-ink">{{ $achievement->title }}</p>
                                    @if ($achievement->year || $photoCount > 0)
                                        <p class="text-xs text-ui-muted">
                                            {{ $achievement->year }}@if ($achievement->year && $photoCount > 0) · @endif@if ($photoCount > 0){{ $photoCount }} {{ Str::plural('photo', $photoCount) }}@endif
                                        </p>
                                    @endif
                                </div>
                                <div class="flex shrink-0 items-center gap-1">
                                    <x-admin.button :href="route('admin.services.achievements.edit', [$service, $achievement])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit {{ $achievement->title }}" title="Edit" />
                                    <form action="{{ route('admin.services.achievements.destroy', [$service, $achievement]) }}" method="POST"
                                          data-confirm="This achievement and its photos will be permanently deleted." data-confirm-title="Delete this achievement?" data-confirm-label="Delete">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete {{ $achievement->title }}" title="Delete">
                                            <x-admin.icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <p class="border-t border-ui-line px-4 py-2.5 text-xs text-ui-muted">Drag the handle to reorder.</p>
                @endif
            </x-admin.card>

            <x-admin.card title="Danger zone" class="border-red-200">
                <p class="mb-3 text-sm text-ui-muted">Permanently delete this service and everything attached to it.</p>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                      data-confirm="“{{ $service->title }}” and all of its content will be permanently deleted. This cannot be undone."
                      data-confirm-title="Delete this service?" data-confirm-label="Delete service">
                    @csrf @method('DELETE')
                    <x-admin.button type="submit" variant="danger" icon="trash">Delete service</x-admin.button>
                </form>
            </x-admin.card>
        </aside>

        {{-- RIGHT: content blocks --}}
        <div class="lg:order-1 lg:col-span-2">
            <x-admin.card title="Content sections" :description="$service->contents->isNotEmpty() ? 'Drag rows to reorder' : null" :padding="false">
                <x-slot:actions>
                    <x-admin.button :href="route('admin.services.contents.create', $service)" size="sm" icon="plus">Add section</x-admin.button>
                </x-slot:actions>

                @if ($service->contents->isEmpty())
                    <x-admin.empty-state icon="document-text" title="No content sections yet" description="Add text or image blocks to build out this service page.">
                        <x-admin.button :href="route('admin.services.contents.create', $service)" icon="plus">Add first section</x-admin.button>
                    </x-admin.empty-state>
                @else
                    <ul id="sortable-contents" class="divide-y divide-ui-line">
                        @foreach ($service->contents as $content)
                            <li data-id="{{ $content->id }}" class="content-row flex items-start gap-3 bg-white px-4 py-4">
                                <span class="drag-handle mt-0.5 cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                                    <x-admin.icon name="grip" class="h-4 w-4" />
                                </span>
                                <x-admin.badge :type="$content->isImage() ? 'neutral' : 'success'" class="mt-0.5">{{ $content->isImage() ? 'Image' : 'Text' }}</x-admin.badge>

                                <div class="min-w-0 flex-1">
                                    @if ($content->heading)
                                        <p class="truncate text-sm font-medium text-ui-ink">{{ $content->heading }}</p>
                                    @endif
                                    @if ($content->isImage() && $content->image_path)
                                        <div class="mt-1 flex items-center gap-3">
                                            <img src="{{ asset('storage/' . $content->image_path) }}" alt="" class="ui-thumb !h-14 !w-20" loading="lazy">
                                            @if ($content->image_caption)<p class="truncate text-sm text-ui-muted">{{ $content->image_caption }}</p>@endif
                                        </div>
                                    @elseif ($content->body)
                                        @php
                                            $preview = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(strip_tags($content->body), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                                        @endphp
                                        <p class="mt-0.5 line-clamp-2 text-sm text-ui-muted">{{ Str::limit($preview, 160) }}</p>
                                    @endif
                                </div>

                                <div class="flex shrink-0 items-center gap-1">
                                    <x-admin.button :href="route('admin.services.contents.edit', [$service, $content])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit section" title="Edit" />
                                    <form action="{{ route('admin.services.contents.destroy', [$service, $content]) }}" method="POST"
                                          data-confirm="This content section will be permanently deleted." data-confirm-title="Delete this section?" data-confirm-label="Delete section">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete section" title="Delete">
                                            <x-admin.icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.card>
        </div>
    </div>

    {{-- Lives inside the page content (not @push) so PJAX navigation re-runs it --}}
    <script>
        (function () {
            window.tpcSortable('sortable-achievements', '{{ route('admin.services.achievements.reorder', $service) }}');
            window.tpcSortable('sortable-contents', '{{ route('admin.services.contents.reorder', $service) }}');
        })();
    </script>
@endsection
