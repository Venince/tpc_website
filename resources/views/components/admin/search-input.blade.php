@props([
    'placeholder' => 'Search…',
    'model'       => 'q',          // Alpine property on the surrounding x-data (see tpcFilter())
    'label'       => 'Search',
])
@php $id = 'search-' . substr(md5($model . $placeholder), 0, 6); @endphp
<div {{ $attributes->class(['relative w-full sm:max-w-xs']) }}>
    <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
    <input id="{{ $id }}" type="search" x-model.debounce.150ms="{{ $model }}" placeholder="{{ $placeholder }}"
           class="ui-input !pl-9" autocomplete="off">
</div>
