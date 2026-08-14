@extends('admin.layout')

@section('title', 'Feedback')

@section('page_actions')
    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-lg font-semibold text-neo-ink">Feedback</h1>
                <p class="mt-0.5 text-xs text-neo-ink/50">
                    Unread: <span class="font-semibold text-neo-ink">{{ $unreadCount }}</span>
                </p>
            </div>
            <div class="inline-flex items-center gap-1.5 rounded-xl bg-neo-bg shadow-neo-inset-sm px-3 py-1.5">
                <svg class="h-4 w-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                </svg>
                <span class="text-sm font-semibold text-neo-ink">{{ $avgRating ?: '—' }}</span>
                <span class="text-xs text-neo-ink/40">avg</span>
            </div>
        </div>

        {{-- Filter form --}}
        <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center flex-wrap">
            <input
                name="q"
                value="{{ $q }}"
                placeholder="Search name, email, comment..."
                class="flex-1 min-w-[180px] rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition"
            />
            <div class="relative">
                <select name="status"
                        class="appearance-none rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 pl-3 pr-8 py-2.5 text-sm text-neo-ink focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition cursor-pointer">
                    <option value="unread" @selected($status === 'unread')>Unread</option>
                    <option value="read" @selected($status === 'read')>Read</option>
                    <option value="all" @selected($status === 'all')>All</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-neo-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="relative">
                <select name="rating"
                        class="appearance-none rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 pl-3 pr-8 py-2.5 text-sm text-neo-ink focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition cursor-pointer">
                    <option value="all" @selected($rating === 'all')>All Ratings</option>
                    @for($r = 5; $r >= 1; $r--)
                        <option value="{{ $r }}" @selected((string)$rating === (string)$r)>{{ $r }} Star{{ $r > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-neo-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="relative">
                <select name="category"
                        class="appearance-none rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 pl-3 pr-8 py-2.5 text-sm text-neo-ink focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition cursor-pointer">
                    <option value="all" @selected($category === 'all')>All Categories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" @selected($category === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-neo-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <button class="rounded-xl bg-tpc-primary px-6 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                Filter
            </button>
        </form>
    </div>
@endsection

@section('content')

    <div class="flex items-center gap-4 mb-6">
        <span class="block h-5 w-1.5 bg-tpc-primary rounded-sm"></span>
        <h2 class="text-xs font-bold tracking-widest text-tpc-primary uppercase">Submissions</h2>
        <div class="flex-1 h-px bg-black/[0.06]"></div>
        @if($unreadCount > 0)
            <span class="inline-block bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                {{ $unreadCount }} unread
            </span>
        @endif
    </div>

    @auth
        @if(auth()->user()->is_super_admin)
            <div id="bulk-bar"
                 class="hidden sticky top-4 z-30 mb-4 items-center justify-between gap-3
                        rounded-2xl bg-neo-surface shadow-neo px-5 py-3">
                <p class="text-sm font-semibold text-neo-ink">
                    <span class="text-red-600" id="bulk-count">0</span> feedback(s) selected
                </p>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="clearAllSelections()"
                            class="rounded-xl bg-neo-bg shadow-neo-inset-sm px-3 py-1.5 text-xs font-semibold text-neo-ink/60 transition hover:text-neo-ink">
                        Cancel
                    </button>
                    <button type="button" onclick="submitBulkDelete()"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        Delete Selected
                    </button>
                </div>
            </div>

            <form id="bulk-form" method="POST" action="{{ route('admin.feedback.bulkDestroy') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            @if($feedbacks->count())
                <div class="flex items-center gap-3 mb-3 px-1">
                    <input type="checkbox" id="select-all"
                           class="h-3.5 w-3.5 rounded border-gray-300 text-red-600 cursor-pointer"
                           onchange="toggleAll(this)">
                    <label for="select-all" class="text-xs text-neo-ink/40 cursor-pointer select-none">
                        Select all on this page
                    </label>
                </div>
            @endif
        @endif
    @endauth

    <div class="space-y-4">
        @forelse ($feedbacks as $f)
            <div class="relative group">

                @auth
                    @if(auth()->user()->is_super_admin)
                        <div class="absolute top-4 left-4 z-10">
                            <input type="checkbox"
                                   class="msg-checkbox h-4 w-4 rounded border-gray-300 text-red-600 cursor-pointer"
                                   value="{{ $f->id }}"
                                   onchange="onMsgCheck()">
                        </div>
                    @endif
                @endauth

                <a href="{{ route('admin.feedback.show', $f) }}"
                   class="block rounded-2xl bg-neo-surface shadow-neo transition-all duration-300 hover:shadow-neo-hover
                          overflow-hidden
                          @auth @if(auth()->user()->is_super_admin) pl-10 @endif @endauth">

                    <div class="h-1 w-full {{ $f->is_read ? 'bg-neo-bg' : 'bg-tpc-primary' }}"></div>

                    <div class="p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    @if(!$f->is_read)
                                        <span class="inline-block bg-tpc-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                            Unread
                                        </span>
                                    @else
                                        <span class="inline-block bg-neo-bg shadow-neo-inset-sm text-neo-ink/40 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                            Read
                                        </span>
                                    @endif

                                    {{-- Stars --}}
                                    <span class="inline-flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-3.5 w-3.5 {{ $i <= $f->rating ? 'text-amber-400' : 'text-neo-ink/15' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/>
                                            </svg>
                                        @endfor
                                    </span>

                                    @if($f->category)
                                        <span class="inline-block bg-neo-bg shadow-neo-inset-sm text-tpc-primary text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                            {{ $f->categoryLabel() }}
                                        </span>
                                    @endif

                                    @if($f->responded_at)
                                        <span class="inline-block bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                            Responded
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 mb-2">
                                    <span class="shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-neo-bg shadow-neo-inset-sm text-tpc-primary text-xs font-bold">
                                        {{ $f->name ? strtoupper(substr($f->name, 0, 1)) : '?' }}
                                    </span>
                                    <p class="text-sm text-neo-ink/60">
                                        <span class="font-semibold text-neo-ink">{{ $f->name ?: 'Anonymous' }}</span>
                                        @if($f->email)
                                            <span class="text-neo-ink/40 ml-1">({{ $f->email }})</span>
                                        @endif
                                    </p>
                                </div>

                                <p class="text-sm text-neo-ink/45 line-clamp-2 leading-relaxed">
                                    {{ $f->message ? \Illuminate\Support\Str::limit($f->message, 160) : 'No comment left.' }}
                                </p>
                            </div>

                            <div class="shrink-0 flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                                <p class="text-xs font-semibold text-neo-ink/50">{{ $f->created_at->timezone('Asia/Manila')->format('M d, Y') }}</p>
                                <p class="text-xs text-neo-ink/35">{{ $f->created_at->timezone('Asia/Manila')->format('h:i A') }}</p>

                                @auth
                                    @if(auth()->user()->is_super_admin)
                                        <form method="POST"
                                              action="{{ route('admin.feedback.destroy', $f) }}"
                                              onsubmit="event.stopPropagation(); return confirm('Delete this feedback?');"
                                              onclick="event.stopPropagation();">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-semibold text-red-500 hover:bg-neo-bg transition">
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
            <div class="py-24 text-center rounded-2xl bg-neo-bg shadow-neo-inset-sm">
                <p class="text-lg font-semibold text-neo-ink/30 mb-1">No feedback found</p>
                <p class="text-sm text-neo-ink/40">
                    {{ $status === 'unread' ? 'All caught up! No unread feedback.' : 'No feedback matches your filters.' }}
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $feedbacks->links() }}
    </div>

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
        if (!confirm('Delete ' + checked.length + ' selected feedback(s)? This cannot be undone.')) return;

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
