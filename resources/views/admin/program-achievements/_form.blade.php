{{-- Vars: $achievement (ProgramAchievement|null) --}}
@php $a = $achievement ?? null; @endphp
<x-admin.card title="Achievement details" class="">
    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.field label="Title" name="title" required class="sm:col-span-2">
            <input id="title" type="text" name="title" value="{{ old('title', $a?->title) }}" required @unless($a) autofocus @endunless
                   class="ui-input @error('title') is-invalid @enderror" placeholder="e.g. Regional Champion — ICT Olympics">
        </x-admin.field>

        <x-admin.field label="Year" name="year" hint="Optional.">
            <input id="year" type="text" name="year" value="{{ old('year', $a?->year) }}" class="ui-input" placeholder="e.g. 2024">
        </x-admin.field>

        <x-admin.field label="Description" name="description" hint="Optional." class="sm:col-span-3">
            <textarea id="description" name="description" rows="4" class="ui-textarea" placeholder="Describe the achievement…">{{ old('description', $a?->description) }}</textarea>
        </x-admin.field>
    </div>
</x-admin.card>
