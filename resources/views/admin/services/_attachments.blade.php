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

<div
    x-data="{
        existing: @js($existingRows),
        rows: [],
        uid: 0,
        addRow() {
            this.rows.push({ uid: ++this.uid, label: '', fileName: '' });
        },
        removeRow(i) {
            this.rows.splice(i, 1);
        },
        pickNew(e, row) {
            const f = e.target.files[0];
            row.fileName = f ? f.name : '';
            if (f && !row.label) {
                row.label = f.name.replace(/\.[^.]+$/, '');
            }
        },
        pickExisting(e, a) {
            const f = e.target.files[0];
            a.newFileName = f ? f.name : '';
        }
    }">

    <div class="flex items-center justify-between mb-3">
        <div class="text-xs font-bold text-neo-ink/60">
            Downloadable Files
            <span class="ml-1 font-normal text-neo-ink/35">(optional)</span>
        </div>
        <button type="button" @click="addRow()"
                class="inline-flex items-center gap-1 rounded-full bg-neo-bg shadow-neo-inset-sm px-3 py-1 text-[11px] font-bold text-tpc-primary transition hover:shadow-neo-inset">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add File
        </button>
    </div>

    <p class="mb-3 text-[11px] text-neo-ink/40 leading-relaxed">
        Visitors see these in a “Forms &amp; Downloads” dropdown on the service page.
        PDF, Word, Excel, PowerPoint, JPG or PNG · max 10 MB each. PDFs and images can also be printed.
    </p>

    <div class="space-y-3">

        {{-- Files already attached --}}
        <template x-for="a in existing" :key="a.id">
            <div class="rounded-xl bg-neo-bg shadow-neo-inset-sm p-3 transition" :class="a.remove ? 'opacity-60' : ''">
                <input type="hidden" :name="`existing_attachments[${a.id}][remove]`" :value="a.remove ? 1 : 0">

                <div class="flex items-start gap-2">
                    <div class="mt-0.5 shrink-0 h-8 w-8 rounded-lg bg-neo-surface shadow-neo-sm flex items-center justify-center">
                        <svg class="h-4 w-4 text-neo-ink/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0 space-y-2">
                        <input type="text"
                               :name="`existing_attachments[${a.id}][label]`"
                               x-model="a.label"
                               maxlength="150"
                               placeholder="Display name"
                               :class="a.remove ? 'line-through' : ''"
                               class="w-full rounded-lg bg-neo-surface shadow-neo-sm border-0 px-3 py-1.5 text-xs font-semibold text-neo-ink placeholder-neo-ink/35 focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition">

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-neo-ink/45">
                            <a :href="a.url" target="_blank" rel="noopener"
                               class="max-w-full truncate font-semibold text-tpc-primary/80 hover:text-tpc-primary transition"
                               x-text="a.original_name"></a>
                            <span x-text="a.meta"></span>
                        </div>

                        <div x-show="!a.remove" class="flex flex-wrap items-center gap-2">
                            <label :for="`att-existing-${a.id}`"
                                   class="inline-flex cursor-pointer items-center gap-1.5 rounded-full bg-neo-surface shadow-neo-sm px-3 py-1 text-[11px] font-semibold text-neo-ink/60 transition hover:shadow-neo-hover hover:text-tpc-primary">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                Replace file
                            </label>
                            <input type="file"
                                   :id="`att-existing-${a.id}`"
                                   :name="`existing_attachments[${a.id}][file]`"
                                   accept="{{ $accept }}"
                                   class="sr-only"
                                   @change="pickExisting($event, a)">
                            <span x-show="a.newFileName" x-cloak class="min-w-0 max-w-full truncate text-[11px] text-neo-ink/50">
                                New: <span class="font-semibold" x-text="a.newFileName"></span>
                            </span>
                        </div>

                        <p x-show="a.remove" x-cloak class="text-[11px] font-semibold text-red-500">
                            This file will be deleted when you save.
                        </p>
                    </div>

                    <button type="button" @click="a.remove = !a.remove"
                            :title="a.remove ? 'Keep this file' : 'Remove this file'"
                            :class="a.remove ? 'text-tpc-primary' : 'text-red-400 hover:text-red-600'"
                            class="mt-0.5 shrink-0 inline-flex h-8 items-center justify-center rounded-lg px-2 text-[11px] font-bold transition hover:bg-neo-surface hover:shadow-neo-sm">
                        <span x-show="a.remove" x-cloak>Undo</span>
                        <svg x-show="!a.remove" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        </template>

        {{-- New files to upload --}}
        <template x-for="(row, i) in rows" :key="row.uid">
            <div class="flex items-start gap-2 rounded-xl bg-neo-bg shadow-neo-inset-sm p-3">
                <div class="mt-0.5 shrink-0 h-8 w-8 rounded-lg bg-neo-surface shadow-neo-sm flex items-center justify-center">
                    <svg class="h-4 w-4 text-tpc-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0 space-y-2">
                    <input type="text"
                           :name="`new_attachments[${i}][label]`"
                           x-model="row.label"
                           maxlength="150"
                           placeholder="Display name (e.g. Enrollment Form)"
                           class="w-full rounded-lg bg-neo-surface shadow-neo-sm border-0 px-3 py-1.5 text-xs font-semibold text-neo-ink placeholder-neo-ink/35 focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition">

                    <div class="flex flex-wrap items-center gap-2">
                        <label :for="`att-new-${row.uid}`"
                               class="inline-flex cursor-pointer items-center gap-1.5 rounded-full bg-neo-surface shadow-neo-sm px-3 py-1 text-[11px] font-semibold text-neo-ink/60 transition hover:shadow-neo-hover hover:text-tpc-primary">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/>
                            </svg>
                            <span x-text="row.fileName ? 'Change file' : 'Choose file'"></span>
                        </label>
                        <input type="file"
                               :id="`att-new-${row.uid}`"
                               :name="`new_attachments[${i}][file]`"
                               accept="{{ $accept }}"
                               class="sr-only"
                               @change="pickNew($event, row)">
                        <span class="min-w-0 max-w-full truncate text-[11px] text-neo-ink/50"
                              x-text="row.fileName || 'No file chosen'"></span>
                    </div>
                </div>

                <button type="button" @click="removeRow(i)" title="Remove this row"
                        class="mt-0.5 shrink-0 inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-400 transition hover:bg-neo-surface hover:shadow-neo-sm hover:text-red-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>

        <div x-show="existing.length === 0 && rows.length === 0"
             class="rounded-xl bg-neo-bg shadow-neo-inset-sm py-5 text-center text-xs text-neo-ink/40">
            No files attached yet. Click <span class="font-semibold text-tpc-primary">Add File</span> to upload a form or document.
        </div>
    </div>
</div>
