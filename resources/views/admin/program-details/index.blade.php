@extends('admin.layout', ['title' => $program->code . ' — Details'])

@section('content')
    <x-admin.page-header title="Detail sections" :subtitle="'Managing sections for ' . $program->name"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Details' => null]">
        <x-admin.button :href="route('academics.show', $program)" target="_blank" rel="noopener" variant="secondary" icon="external">View live</x-admin.button>
        <x-admin.button :href="route('admin.programs.details.create', $program)" icon="plus">Add section</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padding="false">
        @if ($program->details->isEmpty())
            <x-admin.empty-state icon="document-text" title="No sections yet" description="Sections appear on the program's public page.">
                <x-admin.button :href="route('admin.programs.details.create', $program)" icon="plus">Add your first section</x-admin.button>
            </x-admin.empty-state>
        @else
            <ul id="sortable-details" class="divide-y divide-ui-line">
                @foreach ($program->details as $detail)
                    <li data-id="{{ $detail->id }}" class="flex items-start gap-3 bg-white px-4 py-4">
                        <span class="drag-handle mt-0.5 cursor-grab touch-none rounded p-1 text-gray-400 hover:bg-ui-bg-2 hover:text-gray-600 active:cursor-grabbing" role="img" aria-label="Drag to reorder" title="Drag to reorder">
                            <x-admin.icon name="grip" class="h-4 w-4" />
                        </span>
                        <x-admin.badge type="{{ $detail->type === 'text' ? 'success' : 'neutral' }}" class="mt-0.5 uppercase">{{ $detail->type }}</x-admin.badge>

                        <div class="min-w-0 flex-1">
                            @if ($detail->heading)
                                <p class="truncate text-sm font-medium text-ui-ink">{{ $detail->heading }}</p>
                            @endif
                            @if ($detail->type === 'text' && $detail->body)
                                <p class="mt-0.5 line-clamp-2 text-sm text-ui-muted">{{ $detail->body }}</p>
                            @elseif ($detail->type === 'list' && $detail->items)
                                <p class="mt-0.5 line-clamp-2 text-sm text-ui-muted">
                                    {{ count($detail->items) }} item{{ count($detail->items) !== 1 ? 's' : '' }}:
                                    {{ implode(', ', array_slice($detail->items, 0, 3)) }}{{ count($detail->items) > 3 ? '…' : '' }}
                                </p>
                            @elseif ($detail->type === 'gallery' && $detail->image_path)
                                <div class="mt-1 flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $detail->image_path) }}" alt="" class="ui-thumb !h-12 !w-16" loading="lazy">
                                    @if ($detail->caption)<p class="truncate text-sm text-ui-muted">{{ $detail->caption }}</p>@endif
                                </div>
                            @endif
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            <x-admin.button :href="route('admin.programs.details.edit', [$program, $detail])" variant="ghost" size="sm" class="ui-btn-icon" icon="pencil" aria-label="Edit section" title="Edit" />
                            <form method="POST" action="{{ route('admin.programs.details.destroy', [$program, $detail]) }}"
                                  data-confirm="This section will be permanently deleted." data-confirm-title="Delete this section?" data-confirm-label="Delete section">
                                @csrf @method('DELETE')
                                <button type="submit" class="ui-btn ui-btn-ghost-danger ui-btn-icon ui-btn-sm" aria-label="Delete section" title="Delete">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <p class="border-t border-ui-line px-4 py-2.5 text-xs text-ui-muted">Drag the handle to reorder. Changes save automatically.</p>
        @endif
    </x-admin.card>

    <script>
        window.tpcSortable('sortable-details', '/tpc_admin/programs/{{ $program->id }}/details/reorder');
    </script>
@endsection
