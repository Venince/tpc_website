@props([
    'name',
    'title' => null,
    'description' => null,
    'maxWidth' => 'lg',   // sm | md | lg | xl | 2xl
    'show' => false,
])
@php
    $max = ['sm' => 'sm:max-w-sm', 'md' => 'sm:max-w-md', 'lg' => 'sm:max-w-lg', 'xl' => 'sm:max-w-xl', '2xl' => 'sm:max-w-2xl'][$maxWidth] ?? 'sm:max-w-lg';
@endphp
{{--
  Admin modal. Open with:  $dispatch('open-modal', '{{ $name }}')   Close with: $dispatch('close-modal', '{{ $name }}')
  Teleported to <body> because #tpc-admin-main is transformed (position:fixed would otherwise be relative to it).
--}}
<div x-data="{ show: @js($show) }"
     x-on:open-modal.window="$event.detail === '{{ $name }}' && (show = true)"
     x-on:close-modal.window="$event.detail === '{{ $name }}' && (show = false)"
     x-on:keydown.escape.window="show = false"
     x-effect="document.body.classList.toggle('overflow-hidden', show)">
    <template x-teleport="body">
        <div x-show="show" x-cloak class="fixed inset-0 z-[70] flex items-end justify-center p-0 sm:items-center sm:p-6"
             role="dialog" aria-modal="true" @if ($title) aria-labelledby="modal-{{ $name }}-title" @endif>
            <div x-show="show" x-transition.opacity.duration.150ms class="absolute inset-0 bg-gray-900/40" @click="show = false" aria-hidden="true"></div>
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                 class="relative flex max-h-[92vh] w-full flex-col overflow-hidden rounded-t-2xl border border-ui-line bg-white shadow-ui-lg sm:w-full {{ $max }} sm:rounded-xl">
                @if ($title)
                    <div class="flex items-start justify-between gap-4 border-b border-ui-line px-5 py-4">
                        <div class="min-w-0">
                            <h3 id="modal-{{ $name }}-title" class="text-base font-semibold text-ui-ink">{{ $title }}</h3>
                            @if ($description)<p class="mt-0.5 text-sm text-ui-muted">{{ $description }}</p>@endif
                        </div>
                        <button type="button" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm -mr-2 -mt-1" @click="show = false" aria-label="Close dialog">
                            <x-admin.icon name="x" class="h-4 w-4" />
                        </button>
                    </div>
                @endif
                <div class="ui-scroll flex-1 overflow-y-auto px-5 py-4">{{ $slot }}</div>
                @isset($footer)
                    <div class="flex flex-wrap items-center justify-end gap-2 border-t border-ui-line bg-ui-bg px-5 py-3">{{ $footer }}</div>
                @endisset
            </div>
        </div>
    </template>
</div>
