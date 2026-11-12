{{-- Vars: $section, $item (AdmissionItem|null) --}}
@php $it = $item ?? null; @endphp
<x-admin.card :title="$it ? 'Item details' : 'New item'" :description="'Section: ' . $section->label . ' (' . $section->type . ')'" class="">
    <div class="space-y-5">
        <x-admin.field :label="$section->type === 'steps' ? 'Step title' : ($section->type === 'schedule' ? 'Day / period' : 'Item text')" name="title" required>
            <input id="title" type="text" name="title" value="{{ old('title', $it?->title) }}" required autofocus
                   placeholder="{{ $section->type === 'schedule' ? 'e.g. Monday – Friday' : ($section->type === 'steps' ? 'e.g. Submit documents' : 'e.g. PSA Birth Certificate') }}"
                   class="ui-input @error('title') is-invalid @enderror">
        </x-admin.field>

        @if (in_array($section->type, ['steps', 'schedule']))
            <x-admin.field :label="$section->type === 'steps' ? 'Description' : 'Time / value'" name="body" hint="Optional.">
                <input id="body" type="text" name="body" value="{{ old('body', $it?->body) }}"
                       placeholder="{{ $section->type === 'schedule' ? 'e.g. 8:00 AM – 5:00 PM' : 'Short description of this step.' }}" class="ui-input">
            </x-admin.field>
        @endif
    </div>
</x-admin.card>
