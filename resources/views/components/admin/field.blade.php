@props([
    'label'    => null,
    'name'     => null,     // input name — used for the @error lookup
    'for'      => null,     // id of the control (defaults to name)
    'required' => false,
    'hint'     => null,
    'bag'      => 'default',  // named error bag (e.g. updatePassword)
])
@php $id = $for ?? $name; @endphp
{{-- Label + control (slot) + hint + validation message. Give the control id="{{ $id }}". --}}
<div {{ $attributes }}>
    @if ($label)
        <label @if ($id) for="{{ $id }}" @endif @class(['ui-label', 'ui-required' => $required])>{{ $label }}</label>
    @endif
    {{ $slot }}
    @if ($hint)
        <p class="ui-help" @if ($id) id="{{ $id }}-hint" @endif>{{ $hint }}</p>
    @endif
    @if ($name)
        @error($name, $bag)
            <p class="ui-error" @if ($id) id="{{ $id }}-error" @endif role="alert">{{ $message }}</p>
        @enderror
    @endif
</div>
