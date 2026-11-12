{{-- Vars: $program, $person (ProgramPerson|null) --}}
@php
    $pp = $person ?? null;
    $roles = ['dean' => 'Dean', 'head' => 'Program Head', 'coordinator' => 'Coordinator', 'instructor' => 'Instructor'];
    $currentRole = old('role', $pp?->role ?? 'instructor');
@endphp
<x-admin.card title="Person details" class="">
    <div class="space-y-5">
        <fieldset>
            <legend class="ui-label ui-required">Role</legend>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                @foreach ($roles as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="{{ $val }}" class="peer sr-only" @checked($currentRole === $val)>
                        <span class="flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-2 text-center text-sm font-medium text-ui-muted transition-colors
                                     hover:bg-ui-bg-2 peer-checked:border-brand peer-checked:bg-brand-50 peer-checked:text-brand-dark
                                     peer-focus-visible:ring-2 peer-focus-visible:ring-brand peer-focus-visible:ring-offset-2">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('role') <p class="ui-error">{{ $message }}</p> @enderror
        </fieldset>

        <x-admin.field label="Full name" name="name" required>
            <input id="name" type="text" name="name" value="{{ old('name', $pp?->name) }}" required @unless($pp) autofocus @endunless
                   class="ui-input @error('name') is-invalid @enderror" placeholder="e.g. Juan Dela Cruz">
        </x-admin.field>

        <x-admin.field label="Title / position" name="position" hint="Optional — e.g. Associate Professor.">
            <input id="position" type="text" name="position" value="{{ old('position', $pp?->position) }}" class="ui-input" placeholder="e.g. Associate Professor">
        </x-admin.field>

        <x-admin.photo-cropper label="Photo" :current="$pp?->photo_path ? asset('storage/' . $pp->photo_path) : null" remove-name="remove_photo" />
    </div>
</x-admin.card>
