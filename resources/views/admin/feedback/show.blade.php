@extends('admin.layout', ['title' => 'Feedback'])

@section('content')
    <x-admin.page-header title="Feedback" :subtitle="($feedback->name ?: 'Anonymous') . ' · ' . $feedback->created_at->timezone('Asia/Manila')->format('M d, Y h:i A')"
        :breadcrumbs="['Feedback' => route('admin.feedback.index'), 'Details' => null]">
        {{-- Toggled by app.js (data-ajax). Keep ids and the `hidden` class convention. --}}
        <form id="mark-read-form" data-ajax="true" method="POST" action="{{ route('admin.feedback.read', $feedback) }}" class="{{ $feedback->is_read ? 'hidden' : '' }}">
            @csrf @method('PATCH')
            <x-admin.button type="submit" icon="check">Mark read</x-admin.button>
        </form>
        <form id="mark-unread-form" data-ajax="true" method="POST" action="{{ route('admin.feedback.unread', $feedback) }}" class="{{ $feedback->is_read ? '' : 'hidden' }}">
            @csrf @method('PATCH')
            <x-admin.button type="submit" variant="secondary">Mark unread</x-admin.button>
        </form>
    </x-admin.page-header>

    <div class=" space-y-4">
        <x-admin.card :padding="false">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-line px-5 py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-semibold text-brand-dark">{{ $feedback->name ? strtoupper(substr($feedback->name, 0, 1)) : '?' }}</span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-ui-ink">{{ $feedback->name ?: 'Anonymous' }}</p>
                        <p class="truncate text-xs text-ui-muted">{{ $feedback->email ?: 'No email provided' }}</p>
                    </div>
                </div>
                <div class="text-right text-xs text-ui-muted">
                    <p>{{ $feedback->created_at->format('M d, Y') }}</p>
                    <p>{{ $feedback->created_at->format('h:i A') }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 border-b border-ui-line px-5 py-3">
                <x-admin.badge :type="$feedback->is_read ? 'neutral' : 'success'" dot>{{ $feedback->is_read ? 'Read' : 'Unread' }}</x-admin.badge>
                @if ($feedback->category)<x-admin.badge>{{ $feedback->categoryLabel() }}</x-admin.badge>@endif
                <span class="inline-flex items-center gap-2" role="img" aria-label="{{ $feedback->rating }} out of 5 stars">
                    <span class="inline-flex text-brand">
                        @for ($i = 1; $i <= 5; $i++)
                            <x-admin.icon name="star" :class="'h-4 w-4 ' . ($i <= $feedback->rating ? 'fill-current' : 'text-gray-300')" />
                        @endfor
                    </span>
                    <span class="text-sm font-medium text-ui-ink">{{ $feedback->rating }}/5</span>
                </span>
            </div>

            <div class="px-5 py-5">
                @if ($feedback->message)
                    <div class="whitespace-pre-line break-words text-sm leading-relaxed text-ui-ink">{{ $feedback->message }}</div>
                @else
                    <p class="text-sm italic text-ui-muted">No comment was left with this rating.</p>
                @endif
            </div>

            @if ($feedback->page_url)
                <div class="border-t border-ui-line px-5 py-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-ui-muted">Submitted from</p>
                    <p class="mt-0.5 break-all text-sm text-ui-ink">{{ $feedback->page_url }}</p>
                </div>
            @endif

            @if ($feedback->email)
                @php
                    $to = $feedback->email;
                    $subject = 'Re: Your feedback to TPC';
                    $sentAt  = $feedback->created_at?->format('M d, Y h:i A') ?? '';
                    $original = trim((string) $feedback->message);
                    $body =
                        "Hi " . ($feedback->name ?: 'there') . ",\n\n" .
                        "(Write your reply above.)\n\n" .
                        "----- Original Feedback -----\n" .
                        "Rating: {$feedback->rating}/5\n" .
                        ($sentAt ? "Date: {$sentAt}\n" : "") .
                        ($original ? "\n> " . str_replace("\n", "\n> ", $original) . "\n" : "");
                    $gmail = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . urlencode($to) . '&su=' . urlencode($subject) . '&body=' . urlencode($body);
                @endphp
                <div class="rounded-b-xl border-t border-ui-line bg-ui-bg px-5 py-4">
                    <x-admin.button :href="$gmail" target="_blank" rel="noopener" icon="external">Reply via Gmail</x-admin.button>
                </div>
            @endif
        </x-admin.card>

        <x-admin.card title="Internal response / notes" :description="$feedback->responded_at ? 'Last updated ' . $feedback->responded_at->timezone('Asia/Manila')->format('M d, Y h:i A') : 'Visible to admins only.'">
            <form method="POST" action="{{ route('admin.feedback.respond', $feedback) }}" class="space-y-3">
                @csrf
                <label for="admin_response" class="sr-only">Internal response</label>
                <textarea id="admin_response" name="admin_response" rows="4" class="ui-textarea @error('admin_response') is-invalid @enderror"
                          placeholder="Log how this was handled, or draft a response here (visible to admins only)…">{{ old('admin_response', $feedback->admin_response) }}</textarea>
                @error('admin_response') <p class="ui-error">{{ $message }}</p> @enderror
                <div class="flex justify-end"><x-admin.button type="submit">Save note</x-admin.button></div>
            </form>
        </x-admin.card>

        <x-admin.button :href="route('admin.feedback.index')" variant="secondary" icon="arrow-left">Back to feedback</x-admin.button>
    </div>
@endsection
