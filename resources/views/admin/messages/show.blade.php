@extends('admin.layout', ['title' => 'Message'])

@section('content')
    @php
        $to      = $message->email;
        $subject = 'Re: ' . $message->subject;
        $sentAt  = $message->created_at?->format('M d, Y h:i A') ?? '';
        $original = trim((string) $message->message);
        $body =
            "Hi {$message->name},\n\n" .
            "(Write your reply above.)\n\n" .
            "----- Original Message -----\n" .
            "From: {$message->name} <{$message->email}>\n" .
            ($sentAt ? "Date: {$sentAt}\n" : "") .
            "Subject: {$message->subject}\n\n" .
            "> " . str_replace("\n", "\n> ", $original) . "\n";
        $gmail = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . urlencode($to) . '&su=' . urlencode($subject) . '&body=' . urlencode($body);
    @endphp

    <x-admin.page-header :title="$message->subject"
        :subtitle="$message->name . ' · ' . $message->email . ' · ' . $message->created_at->timezone('Asia/Manila')->format('M d, Y h:i A')"
        :breadcrumbs="['Messages' => route('admin.messages.index'), 'Message' => null]">
        {{-- Forms are toggled by app.js (data-ajax). Keep ids and the `hidden` class convention. --}}
        <form id="mark-read-form" data-ajax="true" method="POST" action="{{ route('admin.messages.read', $message) }}" class="{{ $message->is_read ? 'hidden' : '' }}">
            @csrf @method('PATCH')
            <x-admin.button type="submit" icon="check">Mark read</x-admin.button>
        </form>
        <form id="mark-unread-form" data-ajax="true" method="POST" action="{{ route('admin.messages.unread', $message) }}" class="{{ $message->is_read ? '' : 'hidden' }}">
            @csrf @method('PATCH')
            <x-admin.button type="submit" variant="secondary">Mark unread</x-admin.button>
        </form>
    </x-admin.page-header>

    <x-admin.card :padding="false" class="">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-line px-5 py-4">
            <div class="flex min-w-0 items-center gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-semibold text-brand-dark">{{ strtoupper(mb_substr($message->name, 0, 1)) }}</span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-ui-ink">{{ $message->name }}</p>
                    <a href="mailto:{{ $message->email }}" class="block truncate text-xs text-ui-muted hover:text-brand-dark hover:underline">{{ $message->email }}</a>
                </div>
            </div>
            <div class="text-right text-xs text-ui-muted">
                <p>{{ $message->created_at->format('M d, Y') }}</p>
                <p>{{ $message->created_at->format('h:i A') }}</p>
            </div>
        </div>

        <div class="px-5 py-5">
            <div class="whitespace-pre-line break-words text-sm leading-relaxed text-ui-ink">{{ $message->message }}</div>
        </div>

        <div class="flex flex-wrap items-center gap-2 border-t border-ui-line bg-ui-bg px-5 py-4 rounded-b-xl">
            <x-admin.button :href="$gmail" target="_blank" rel="noopener" icon="external">Reply via Gmail</x-admin.button>
            <x-admin.button :href="route('admin.messages.index')" variant="secondary" icon="arrow-left">Back to inbox</x-admin.button>
        </div>
    </x-admin.card>
@endsection
