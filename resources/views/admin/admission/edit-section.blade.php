@extends('admin.layout', ['title' => 'Edit Section'])

@section('content')
    <x-admin.page-header title="Edit section" :subtitle="$section->label"
        :breadcrumbs="['Admission' => route('admin.admission.index'), 'Edit section' => null]" />

    <form method="POST" action="{{ route('admin.admission.sections.update', $section) }}">
        @csrf @method('PATCH')

        <x-admin.card title="Section settings" :description="'Key: ' . $section->key . ' · Type: ' . $section->type" class="">
            <div class="space-y-5">
                <x-admin.field label="Heading label" name="label" required>
                    <input id="label" type="text" name="label" value="{{ old('label', $section->label) }}" required placeholder="e.g. For Freshmen" class="ui-input @error('label') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Note / tip" name="note" hint="Optional. Shown as a callout below the section items — leave blank to hide it.">
                    <textarea id="note" name="note" rows="3" placeholder="Leave blank to hide the callout box." class="ui-textarea @error('note') is-invalid @enderror">{{ old('note', $section->note) }}</textarea>
                </x-admin.field>

                <label class="flex cursor-pointer items-start gap-3">
                    <input id="is_visible" type="checkbox" name="is_visible" value="1" class="ui-switch mt-0.5" role="switch" @checked(old('is_visible', $section->is_visible))>
                    <span>
                        <span class="block text-sm font-medium text-ui-ink">Visible on public page</span>
                        <span class="block text-xs text-ui-muted">When off, this section is hidden from visitors.</span>
                    </span>
                </label>
            </div>
        </x-admin.card>

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.admission.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
