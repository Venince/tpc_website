@extends('admin.layout', ['title' => 'Messages'])

@section('content')

    {{-- Header row --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-neo-ink">Inbox</h2>
            <p class="mt-0.5 text-xs text-neo-ink/50">
                Unread messages:
                <span class="font-semibold text-tpc-primary">{{ $unreadCount }}</span>
            </p>
        </div>

        {{-- Filter form --}}
        <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <div class="flex gap-2">
                <input
                    name="q"
                    value="{{ $q }}"
                    placeholder="Search name, email, subject..."
                    class="w-full sm:w-64 rounded-xl bg-neo-bg shadow-neo-inset-sm px-3.5 py-2 text-xs text-neo-ink placeholder-neo-ink/40 focus:outline-none focus:ring-2 focus:ring-tpc-primary/25"
                />
                <select
                    name="status"
                    class="rounded-xl bg-neo-bg shadow-neo-inset-sm px-2.5 py-2 text-xs text-neo-ink focus:outline-none focus:ring-2 focus:ring-tpc-primary/25 cursor-pointer"
                >
                    <option value="unread" @selected($status === 'unread')>Unread</option>
                    <option value="read"   @selected($status === 'read')>Read</option>
                    <option value="all"    @selected($status === 'all')>All</option>
                </select>
            </div>
            <button class="rounded-xl bg-tpc-primary px-4 py-2 text-xs font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                Filter
            </button>
        </form>
    </div>

    @auth
        @if(auth()->user()->is_super_admin)

            {{-- Bulk-delete action bar --}}
            <div id="bulk-bar"
                 class="hidden mb-5 flex items-center justify-between gap-3 rounded-2xl bg-neo-bg shadow-neo-inset-sm px-4 py-3">
                <p class="text-xs font-semibold text-neo-ink/70">
                    <span id="bulk-count">0</span> message(s) selected
                </p>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="clearAllSelections()"
                            class="rounded-xl bg-neo-surface shadow-neo-sm px-3 py-1.5 text-[11px] font-semibold text-neo-ink/60 hover:shadow-neo-hover transition">
                        Cancel
                    </button>
                    <button type="button" onclick="submitBulkDelete()"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-red-700 transition">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        Delete Selected
                    </button>
                </div>
            </div>

            {{-- Hidden bulk-delete form --}}
            <form id="bulk-form" method="POST"
                  action="{{ route('admin.messages.bulkDestroy') }}"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>

            {{-- Select-all row --}}
            @if($messages->count())
                <div class="flex items-center gap-2.5 mb-3 px-1">
                    <input type="checkbox" id="select-all"
                           class="h-3.5 w-3.5 rounded border-neo-ink/20 text-tpc-primary cursor-pointer"
                           onchange="toggleAll(this)">
                    <label for="select-all" class="text-[11px] text-neo-ink/40 cursor-pointer select-none">
                        Select all on this page
                    </label>
                </div>
            @endif

        @endif
    @endauth

    <div class="space-y-2.5">
        @forelse ($messages as $m)
            <div class="relative group">

                @auth
                    @if(auth()->user()->is_super_admin)
                        <div class="absolute top-4 left-4 z-10">
                            <input type="checkbox"
                                   class="msg-checkbox h-4 w-4 rounded border-neo-ink/20 text-tpc-primary cursor-pointer"
                                   value="{{ $m->id }}"
                                   onchange="onMsgCheck()">
                        </div>
                    @endif
                @endauth

                <a href="{{ route('admin.messages.show', $m) }}"
                   class="block rounded-2xl bg-neo-surface shadow-neo-sm hover:shadow-neo-hover transition-all duration-200 overflow-hidden
                          @auth @if(auth()->user()->is_super_admin) pl-10 @endif @endauth">

                    <div class="p-4">
                        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                            <div class="min-w-0 flex-1">

                                {{-- Status + Subject --}}
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    @if(!$m->is_read)
                                        <span class="inline-block bg-tpc-primary text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                            Unread
                                        </span>
                                    @else
                                        <span class="inline-block bg-neo-bg text-neo-ink/40 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                                            Read
                                        </span>
                                    @endif
                                    <p class="text-xs font-semibold text-neo-ink group-hover:text-tpc-primary transition truncate">
                                        {{ $m->subject }}
                                    </p>
                                </div>

                                {{-- Sender --}}
                                <p class="text-xs text-neo-ink/50 mb-1.5">
                                    <span class="font-medium text-neo-ink/70">{{ $m->name }}</span>
                                    <span class="ml-1">({{ $m->email }})</span>
                                </p>

                                {{-- Preview --}}
                                <p class="text-xs text-neo-ink/40 line-clamp-2 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit($m->message, 160) }}
                                </p>
                            </div>

                            {{-- Date + single delete --}}
                            <div class="shrink-0 flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                                <p class="text-[11px] font-semibold text-neo-ink/50">{{ $m->created_at->timezone('Asia/Manila')->format('M d, Y') }}</p>
                                <p class="text-[10px] text-neo-ink/35">{{ $m->created_at->timezone('Asia/Manila')->format('h:i A') }}</p>

                                @auth
                                    @if(auth()->user()->is_super_admin)
                                        <form method="POST"
                                              action="{{ route('admin.messages.destroy', $m) }}"
                                              onsubmit="event.stopPropagation(); return confirm('Delete this message?');"
                                              onclick="event.stopPropagation();">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[10px] font-semibold text-red-500 hover:bg-red-50 transition">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="py-16 text-center rounded-2xl bg-neo-bg shadow-neo-inset-sm">
                <p class="text-sm font-semibold text-neo-ink/30 mb-1">No messages found</p>
                <p class="text-xs text-neo-ink/40">
                    {{ $status === 'unread' ? 'All caught up! No unread messages.' : 'No messages match your search.' }}
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>

@auth
@if(auth()->user()->is_super_admin)
@push('scripts')
<script>
    function onMsgCheck() {
        var checked = document.querySelectorAll('.msg-checkbox:checked');
        var all     = document.querySelectorAll('.msg-checkbox');

        var sa = document.getElementById('select-all');
        if (sa) {
            sa.indeterminate = checked.length > 0 && checked.length < all.length;
            sa.checked       = checked.length === all.length && all.length > 0;
        }

        document.getElementById('bulk-count').textContent = checked.length;

        var bar = document.getElementById('bulk-bar');
        if (checked.length > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }
    }

    function toggleAll(cb) {
        document.querySelectorAll('.msg-checkbox').forEach(function (c) { c.checked = cb.checked; });
        onMsgCheck();
    }

    function clearAllSelections() {
        document.querySelectorAll('.msg-checkbox').forEach(function (c) { c.checked = false; });
        var sa = document.getElementById('select-all');
        if (sa) { sa.checked = false; sa.indeterminate = false; }
        var bar = document.getElementById('bulk-bar');
        bar.classList.add('hidden');
        bar.classList.remove('flex');
    }

    function submitBulkDelete() {
        var checked = document.querySelectorAll('.msg-checkbox:checked');
        if (!checked.length) return;
        if (!confirm('Delete ' + checked.length + ' selected message(s)? This cannot be undone.')) return;

        var form = document.getElementById('bulk-form');
        form.querySelectorAll('input[name="ids[]"]').forEach(function (el) { el.remove(); });

        checked.forEach(function (cb) {
            var input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        form.submit();
    }
</script>
@endpush
@endif
@endauth

@endsection
