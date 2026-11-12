@props([
    'name'         => 'password',
    'placeholder'  => '',
    'autocomplete' => 'new-password',
])
@php $id = $attributes->get('id', $name); @endphp
{{-- Password field with a show/hide toggle. Use inside <x-admin.field :for="…">. --}}
<div class="relative" x-data="{ show: false }">
    <input id="{{ $id }}" :type="show ? 'text' : 'password'" name="{{ $name }}" placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}"
           {{ $attributes->except('id')->class(['ui-input !pr-11']) }}>
    <button type="button" @click="show = !show" :aria-pressed="show" :aria-label="show ? 'Hide password' : 'Show password'"
            class="absolute right-1.5 top-1/2 grid h-7 w-7 -translate-y-1/2 place-items-center rounded-md text-gray-400 transition-colors hover:text-ui-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand">
        <x-admin.icon name="eye" class="h-4 w-4" x-show="!show" />
        <x-admin.icon name="eye" class="h-4 w-4 text-brand" x-show="show" x-cloak />
    </button>
</div>
