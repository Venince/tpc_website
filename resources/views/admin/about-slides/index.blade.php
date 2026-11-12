@extends('admin.layout', ['title' => 'About Slides'])

@section('content')
    <x-admin.page-header title="About slides" subtitle="Manage the image carousel shown in the About section on the homepage."
        :breadcrumbs="['About Slides' => null]">
        <x-admin.button :href="route('admin.about-slides.create')" icon="plus">Add slide</x-admin.button>
    </x-admin.page-header>

    @if ($slides->isEmpty())
        <x-admin.card :padding="false">
            <x-admin.empty-state icon="photo" title="No slides yet" description="Add your first slide to populate the homepage carousel.">
                <x-admin.button :href="route('admin.about-slides.create')" icon="plus">Add first slide</x-admin.button>
            </x-admin.empty-state>
        </x-admin.card>
    @else
        @php
            $activeCount = $slides->where('is_active', true)->count();
            $totalCount  = $slides->count();
        @endphp
        <p class="mb-4 text-sm text-ui-muted">
            <span class="font-semibold text-ui-ink tabular-nums">{{ $totalCount }}</span> total ·
            <span class="font-semibold text-brand-dark tabular-nums">{{ $activeCount }}</span> active ·
            <span class="font-semibold text-ui-ink tabular-nums">{{ $totalCount - $activeCount }}</span> hidden
        </p>

        <ul class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($slides as $slide)
                <li class="overflow-hidden rounded-xl border border-ui-line bg-white shadow-ui-xs">
                    <div class="relative aspect-[2/1] bg-ui-bg-2">
                        <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title ?: 'Slide ' . $slide->sort_order }}" class="h-full w-full object-cover" loading="lazy">
                        <span class="absolute left-2 top-2 rounded-md bg-gray-900/70 px-2 py-0.5 text-xs font-medium text-white">#{{ $slide->sort_order }}</span>
                        <span class="absolute right-2 top-2">
                            @if ($slide->is_active)
                                <x-admin.badge type="success" dot class="bg-white">Active</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral" class="bg-white">Hidden</x-admin.badge>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-2 px-4 py-3">
                        <p class="min-w-0 truncate text-sm font-medium text-ui-ink">{{ $slide->title ?: 'Untitled slide' }}</p>
                        <div class="flex shrink-0 items-center gap-1">
                            <x-admin.button :href="route('admin.about-slides.edit', $slide)" variant="secondary" size="sm" icon="pencil">Edit</x-admin.button>
                            <form action="{{ route('admin.about-slides.destroy', $slide) }}" method="POST"
                                  data-confirm="This slide and its image will be permanently removed." data-confirm-title="Delete this slide?" data-confirm-label="Delete slide">
                                @csrf @method('DELETE')
                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete slide {{ $slide->title ?: '#' . $slide->sort_order }}" title="Delete">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach

            <li>
                <a href="{{ route('admin.about-slides.create') }}"
                   class="ui-dropzone h-full min-h-[11rem] hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
                    <x-admin.icon name="plus" class="h-6 w-6" />
                    <span class="font-medium">Add slide</span>
                </a>
            </li>
        </ul>
    @endif
@endsection
