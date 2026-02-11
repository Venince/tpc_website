@extends('layouts.site')

@section('title', 'Admin - Messages')

@section('content')
<section class="bg-transparent">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium tracking-wide text-tpc-primary uppercase">Admin</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-tpc-ink">Inbox</h1>
                <p class="mt-2 text-sm text-tpc-ink/70">
                    Unread: <span class="font-semibold text-tpc-primary">{{ $unreadCount }}</span>
                </p>
            </div>

            {{-- ✅ Better mobile filter layout --}}
            <form method="GET" class="w-full sm:w-auto grid gap-2 sm:grid-cols-3 sm:items-end">
                <input
                    name="q"
                    value="{{ $q }}"
                    placeholder="Search name, email, subject..."
                    class="w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20"
                />

                <select
                    name="status"
                    class="w-full rounded-lg border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20"
                >
                    <option value="unread" @selected($status === 'unread')>Unread</option>
                    <option value="read" @selected($status === 'read')>Read</option>
                    <option value="all" @selected($status === 'all')>All</option>
                </select>

                <button class="w-full rounded-lg bg-tpc-primary px-4 py-2 text-sm font-medium text-white hover:bg-tpc-secondary">
                    Filter
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="mt-5 rounded-xl border border-tpc-primary/20 bg-tpc-primary/5 p-4 text-sm text-tpc-ink">
                <span class="font-medium text-tpc-primary">OK:</span> {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid gap-4">
            @forelse ($messages as $m)
                <a
                    href="{{ route('admin.messages.show', $m) }}"
                    class="block rounded-2xl border border-tpc-primary/15 bg-white/75 p-5 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-tpc-primary/30 hover:shadow-md"
                >
                    {{-- ✅ Stack on mobile, row on desktop --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                @if(!$m->is_read)
                                    <span class="inline-flex items-center rounded-full bg-tpc-accent/40 px-2.5 py-1 text-xs font-semibold text-tpc-secondary">
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-tpc-primary/10 px-2.5 py-1 text-xs font-semibold text-tpc-primary">
                                        Read
                                    </span>
                                @endif

                                <p class="truncate text-sm font-semibold text-tpc-ink">
                                    {{ $m->subject }}
                                </p>
                            </div>

                            <p class="mt-2 break-words text-sm text-tpc-ink/70">
                                From: <span class="font-medium text-tpc-ink">{{ $m->name }}</span>
                                <span class="text-tpc-ink/50">({{ $m->email }})</span>
                            </p>

                            <p class="mt-2 line-clamp-2 text-sm text-tpc-ink/70">
                                {{ \Illuminate\Support\Str::limit($m->message, 160) }}
                            </p>
                        </div>

                        <div class="shrink-0 sm:text-right">
                            <p class="text-xs text-tpc-ink/55">{{ $m->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-tpc-ink/55">{{ $m->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-tpc-primary/30 p-10 text-center text-tpc-ink/70">
                    No messages found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</section>
@endsection
