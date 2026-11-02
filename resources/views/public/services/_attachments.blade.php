{{--
    Public "Forms & Downloads" card (sidebar).
    Usage: @include('public.services._attachments', ['service' => $service])
    Shows each attached file's name with a Download button. PDFs and images
    also get a small Print button that opens the file in a new tab.
--}}
@php
    $attachments = $service->attachments;

    $typeStyles = [
        'pdf'     => 'bg-red-50 text-red-600 ring-red-200/70',
        'doc'     => 'bg-blue-50 text-blue-600 ring-blue-200/70',
        'docx'    => 'bg-blue-50 text-blue-600 ring-blue-200/70',
        'xls'     => 'bg-green-50 text-green-600 ring-green-200/70',
        'xlsx'    => 'bg-green-50 text-green-600 ring-green-200/70',
        'ppt'     => 'bg-orange-50 text-orange-600 ring-orange-200/70',
        'pptx'    => 'bg-orange-50 text-orange-600 ring-orange-200/70',
        'jpg'     => 'bg-purple-50 text-purple-600 ring-purple-200/70',
        'jpeg'    => 'bg-purple-50 text-purple-600 ring-purple-200/70',
        'png'     => 'bg-purple-50 text-purple-600 ring-purple-200/70',
        'default' => 'bg-gray-50 text-gray-600 ring-gray-200',
    ];
@endphp

@if ($attachments->isNotEmpty())
    <div class="bg-white rounded-3xl border border-gray-100 shadow-md overflow-hidden print:hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Forms &amp; Downloads</p>
        </div>

        <div class="p-5 space-y-2.5">
            @foreach ($attachments as $file)
                @php
                    $ext   = $file->extension;
                    $style = $typeStyles[$ext] ?? $typeStyles['default'];
                @endphp

                <div class="flex flex-col gap-3 rounded-2xl bg-gray-50 px-3 py-3 ring-1 ring-gray-100 sm:flex-row sm:items-center">

                    {{-- File type badge + name --}}
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-[10px] font-extrabold ring-1 {{ $style }}">
                            {{ strtoupper(Str::limit($ext ?: 'file', 4, '')) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="break-words text-sm font-bold leading-snug text-gray-800">{{ $file->label }}</p>
                            <p class="text-[11px] text-gray-400">{{ strtoupper($ext ?: 'file') }} &middot; {{ $file->human_size }}</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('services.attachments.download', [$service, $file]) }}"
                           download="{{ $file->download_name }}"
                           data-no-transition
                           onclick="event.stopPropagation()"
                           class="inline-flex h-11 flex-1 items-center justify-center gap-1.5 rounded-full border-2 border-tpc-primary bg-tpc-primary px-4 text-xs font-bold text-white transition hover:border-tpc-secondary hover:bg-tpc-secondary focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/40 focus-visible:ring-offset-1 sm:h-9 sm:flex-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Download
                            <span class="sr-only">{{ $file->label }}</span>
                        </a>

                        @if ($file->isPrintable())
                            <a href="{{ route('services.attachments.view', [$service, $file]) }}"
                               target="_blank" rel="noopener"
                               title="Open to print"
                               class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full border-2 border-gray-200 bg-white text-gray-500 transition hover:border-tpc-primary hover:text-tpc-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-tpc-primary/40 sm:h-9 sm:w-9">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                                </svg>
                                <span class="sr-only">Print {{ $file->label }} (opens in a new tab)</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
