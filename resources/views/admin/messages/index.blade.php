@extends('layouts.site')

@section('title', 'Admin - Messages')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Admin</p>
            <div class="flex flex-col gap-2">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Inbox</h1>
                    <p class="mt-2 text-sm text-white/70">
                        Unread messages:
                        <span class="font-bold text-white">{{ $unreadCount }}</span>
                    </p>
                </div>

                {{-- Filter form --}}
                <form method="GET" class="w-full mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                    <div class="flex gap-2 flex-1">
                        <input
                            name="q"
                            value="{{ $q }}"
                            placeholder="Search name, email, subject..."
                            class="flex-1 min-w-0 rounded-xl border-2 border-white/30 bg-white/10 text-white placeholder-white/50 px-4 py-2.5 text-sm focus:border-white focus:outline-none backdrop-blur-sm"
                        />
                        <select
                            name="status"
                            class="rounded-xl border-2 border-white/30 bg-tpc-primary text-white px-3 py-2.5 text-sm focus:border-white focus:outline-none appearance-none cursor-pointer"
                        >
                            <option value="unread" class="text-tpc-ink" @selected($status === 'unread')>Unread</option>
                            <option value="read"   class="text-tpc-ink" @selected($status === 'read')>Read</option>
                            <option value="all"    class="text-tpc-ink" @selected($status === 'all')>All</option>
                        </select>
                    </div>
                    <button class="w-full sm:w-auto rounded-xl border-2 border-white bg-white px-6 py-2.5 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
                        Filter
                    </button>
                </form>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-8 sm:h-10">
                <path d="M0 40 C360 0 1080 0 1440 40 L1440 40 L0 40 Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="bg-gray-50 min-h-[60vh]">
        <div class="max-w-7xl mx-auto px-4 py-14">

            <div class="flex items-center gap-4 mb-8">
                <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
                <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Messages</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
                @if($unreadCount > 0)
                    <span class="inline-block bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        {{ $unreadCount }} unread
                    </span>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 flex gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-sm text-green-700">
                    <svg class="h-5 w-5 shrink-0 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><span class="font-bold">OK:</span> {{ session('success') }}</span>
                </div>
            @endif

            @auth
                @if(auth()->user()->is_super_admin)

                    {{-- Bulk-delete action bar --}}
                    <div id="bulk-bar"
                         class="hidden mb-6 flex items-center justify-between gap-3
                                rounded-2xl border border-red-200 bg-white px-5 py-3 shadow-lg shadow-red-50">
                        <p class="text-sm font-semibold text-gray-700">
                            <span id="bulk-count">0</span> message(s) selected
                        </p>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="clearAllSelections()"
                                    class="rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-500 hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="button" onclick="submitBulkDelete()"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700 transition">
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
                        <div class="flex items-center gap-3 mb-3 px-1">
                            <input type="checkbox" id="select-all"
                                   class="h-3.5 w-3.5 rounded border-gray-300 text-red-600 cursor-pointer"
                                   onchange="toggleAll(this)">
                            <label for="select-all" class="text-xs text-gray-400 cursor-pointer select-none">
                                Select all on this page
                            </label>
                        </div>
                    @endif

                @endif
            @endauth

            <div class="space-y-4">
                @forelse ($messages as $m)
                    <div class="relative group">

                        @auth
                            @if(auth()->user()->is_super_admin)
                                {{-- Checkbox (sits outside the <a> so clicking it doesn't navigate) --}}
                                <div class="absolute top-4 left-4 z-10">
                                    <input type="checkbox"
                                           class="msg-checkbox h-4 w-4 rounded border-gray-300 text-red-600 cursor-pointer shadow-sm"
                                           value="{{ $m->id }}"
                                           onchange="onMsgCheck()">
                                </div>
                            @endif
                        @endauth

                        <a href="{{ route('admin.messages.show', $m) }}"
                           class="block bg-white rounded-2xl border border-gray-200 shadow-sm
                                  hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300
                                  overflow-hidden
                                  @auth @if(auth()->user()->is_super_admin) pl-10 @endif @endauth">

                            {{-- Unread indicator bar --}}
                            <div class="h-1 w-full {{ $m->is_read ? 'bg-gray-100' : 'bg-tpc-primary' }} group-hover:bg-tpc-accent transition-colors duration-300"></div>

                            <div class="p-5">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                                    <div class="min-w-0 flex-1">

                                        {{-- Status + Subject --}}
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            @if(!$m->is_read)
                                                <span class="inline-block bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                                    Unread
                                                </span>
                                            @else
                                                <span class="inline-block bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                                    Read
                                                </span>
                                            @endif
                                            <p class="text-sm font-bold text-gray-800 group-hover:text-tpc-primary transition truncate">
                                                {{ $m->subject }}
                                            </p>
                                        </div>

                                        {{-- Sender --}}
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-tpc-primary/10 text-tpc-primary text-xs font-bold">
                                                {{ strtoupper(substr($m->name, 0, 1)) }}
                                            </span>
                                            <p class="text-sm text-gray-500">
                                                <span class="font-semibold text-gray-700">{{ $m->name }}</span>
                                                <span class="text-gray-400 ml-1">({{ $m->email }})</span>
                                            </p>
                                        </div>

                                        {{-- Preview --}}
                                        <p class="text-sm text-gray-400 line-clamp-2 leading-relaxed">
                                            {{ \Illuminate\Support\Str::limit($m->message, 160) }}
                                        </p>
                                    </div>

                                    {{-- Date + single delete --}}
                                    <div class="shrink-0 flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                                        <p class="text-xs font-semibold text-gray-500">{{ $m->created_at->timezone('Asia/Manila')->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $m->created_at->timezone('Asia/Manila')->format('h:i A') }}</p>

                                        @auth
                                            @if(auth()->user()->is_super_admin)
                                                {{-- Single delete (stop propagation so the <a> doesn't fire) --}}
                                                <form method="POST"
                                                      action="{{ route('admin.messages.destroy', $m) }}"
                                                      onsubmit="event.stopPropagation(); return confirm('Delete this message?');"
                                                      onclick="event.stopPropagation();">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-semibold text-red-500 hover:bg-red-50 transition">
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
                    <div class="py-24 text-center border border-dashed border-gray-300 rounded-2xl bg-white">
                        <p class="text-lg font-semibold text-gray-300 mb-1">No messages found</p>
                        <p class="text-sm text-gray-400">
                            {{ $status === 'unread' ? 'All caught up! No unread messages.' : 'No messages match your search.' }}
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $messages->links() }}
            </div>
        </div>
    </section>

@auth
@if(auth()->user()->is_super_admin)
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
@endif
@endauth

@endsection
