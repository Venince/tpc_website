{{--
    Downloadable / printable attachments builder (admin).
    Usage:
        Create: @include('admin.services._attachments')
        Edit:   @include('admin.services._attachments', ['attachments' => $service->attachments])
    The parent <form> must use enctype="multipart/form-data".
--}}
@php
    $attachments = $attachments ?? collect();
    $existingRows = $attachments->map(fn ($a) => [
        'id'            => $a->id,
        'label'         => old("existing_attachments.{$a->id}.label", $a->label),
        'original_name' => $a->original_name,
        'meta'          => strtoupper($a->extension) . ' · ' . $a->human_size,
        'url'           => $a->file_url,
        'remove'        => false,
        'newFileName'   => '',
    ])->values()->all();
    $accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png';
@endphp
<div x-data="{
        existing: @js($existingRows),
        rows: [],
        uid: 0,
        addRow() { this.rows.push({ uid: ++this.uid, label: '', fileName: '' }); },
        removeRow(i) { this.rows.splice(i, 1); },
        pickNew(e, row) {
            const f = e.target.files[0];
            row.fileName = f ? f.name : '';
            if (f && !row.label) { row.label = f.name.replace(/\.[^.]+$/, ''); }
        },
        pickExisting(e, a) {
            const f = e.target.files[0];
            a.newFileName = f ? f.name : '';
        }
    }">
    <div class="mb-3 flex items-start justify-between gap-3">
        <div>
            <p class="ui-label !mb-0">Downloadable files <span class="font-normal text-ui-muted">(optional)</span></p>
            <p class="ui-help !mt-0.5">
                Visitors see these in a “Forms &amp; Downloads” menu on the service page.
                PDF, Word, Excel, PowerPoint, JPG or PNG · max 10 MB each. PDFs and images can also be printed.
            </p>
        </div>
        <button type="button" @click="addRow()" class="ui-btn ui-btn-secondary ui-btn-sm shrink-0">
            <x-admin.icon name="plus" class="h-3.5 w-3.5" /> Add file
        </button>
    </div>

    <div class="space-y-2">
        {{-- Files already attached --}}
        <template x-for="a in existing" :key="a.id">
            <div class="rounded-lg border border-ui-line bg-ui-bg p-3 transition-opacity" :class="a.remove ? 'opacity-60' : ''">
                <input type="hidden" :name="`existing_attachments[${a.id}][remove]`" :value="a.remove ? 1 : 0">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-md bg-white text-gray-500 ring-1 ring-ui-line" aria-hidden="true">
                        <x-admin.icon name="paper-clip" class="h-4 w-4" />
                    </span>
                    <div class="min-w-0 flex-1 space-y-2">
                        <input type="text" :name="`existing_attachments[${a.id}][label]`" x-model="a.label" maxlength="150"
                               placeholder="Display name" aria-label="Display name" class="ui-input" :class="a.remove ? 'line-through' : ''">
                        <div class="flex flex-wrap items-center gap-x-2 text-xs text-ui-muted">
                            <a :href="a.url" target="_blank" rel="noopener" class="max-w-full truncate font-medium text-brand-dark hover:underline" x-text="a.original_name"></a>
                            <span x-text="a.meta"></span>
                        </div>
                        <div x-show="!a.remove" class="flex flex-wrap items-center gap-2">
                            <label :for="`att-existing-${a.id}`" class="ui-btn ui-btn-secondary ui-btn-sm cursor-pointer focus-within:ring-2 focus-within:ring-brand">Replace file</label>
                            <input type="file" class="sr-only" :id="`att-existing-${a.id}`" :name="`existing_attachments[${a.id}][file]`" accept="{{ $accept }}" @change="pickExisting($event, a)">
                            <span x-show="a.newFileName" x-cloak class="min-w-0 truncate text-xs text-ui-muted">New: <span class="text-ui-ink" x-text="a.newFileName"></span></span>
                        </div>
                        <p x-show="a.remove" x-cloak class="text-xs font-medium text-red-700">This file will be deleted when you save.</p>
                    </div>
                    <button type="button" @click="a.remove = !a.remove" class="ui-btn ui-btn-ghost ui-btn-sm shrink-0"
                            :class="a.remove ? '' : 'ui-btn-ghost-danger'"
                            :aria-label="a.remove ? 'Keep this file' : 'Remove this file'">
                        <span x-show="a.remove" x-cloak>Undo</span>
                        <x-admin.icon name="trash" class="h-4 w-4" x-show="!a.remove" />
                    </button>
                </div>
            </div>
        </template>

        {{-- New files to upload --}}
        <template x-for="(row, i) in rows" :key="row.uid">
            <div class="flex items-start gap-3 rounded-lg border border-ui-line bg-ui-bg p-3">
                <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-md bg-white text-brand ring-1 ring-ui-line" aria-hidden="true">
                    <x-admin.icon name="upload" class="h-4 w-4" />
                </span>
                <div class="min-w-0 flex-1 space-y-2">
                    <input type="text" :name="`new_attachments[${i}][label]`" x-model="row.label" maxlength="150"
                           placeholder="Display name (e.g. Enrollment Form)" aria-label="Display name" class="ui-input">
                    <div class="flex flex-wrap items-center gap-2">
                        <label :for="`att-new-${row.uid}`" class="ui-btn ui-btn-secondary ui-btn-sm cursor-pointer focus-within:ring-2 focus-within:ring-brand">
                            <span x-text="row.fileName ? 'Change file' : 'Choose file'"></span>
                        </label>
                        <input type="file" class="sr-only" :id="`att-new-${row.uid}`" :name="`new_attachments[${i}][file]`" accept="{{ $accept }}" @change="pickNew($event, row)">
                        <span class="min-w-0 truncate text-xs text-ui-muted" x-text="row.fileName || 'No file chosen'"></span>
                    </div>
                </div>
                <button type="button" @click="removeRow(i)" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm shrink-0" aria-label="Remove this row" title="Remove this row">
                    <x-admin.icon name="x" class="h-4 w-4" />
                </button>
            </div>
        </template>

        <p x-show="existing.length === 0 && rows.length === 0" x-cloak
           class="rounded-lg border border-dashed border-ui-line px-4 py-5 text-center text-sm text-ui-muted">
            No files attached yet. Use <span class="font-medium text-ui-ink">Add file</span> to upload a form or document.
        </p>
    </div>

    @error('new_attachments')<p class="ui-error">{{ $message }}</p>@enderror
    @foreach ($errors->get('new_attachments.*') as $messages)
        @foreach ($messages as $m)<p class="ui-error">{{ $m }}</p>@endforeach
    @endforeach
    @foreach ($errors->get('existing_attachments.*') as $messages)
        @foreach ($messages as $m)<p class="ui-error">{{ $m }}</p>@endforeach
    @endforeach
</div>
