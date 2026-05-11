@extends('layouts.site')

@section('title', 'Admin - Messages')

@section('content')

    {{-- PAGE HEADER --}}
    <section class="relative overflow-hidden bg-tpc-primary">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">Admin</p>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Inbox</h1>
                    <p class="mt-2 text-sm text-white/70">
                        Unread messages:
                        <span class="font-bold text-white">{{ $unreadCount }}</span>
                    </p>
                </div>

                {{-- Filter form --}}
                <form method="GET" class="w-full sm:w-auto flex flex-col gap-2 sm:flex-row sm:items-end">
                    <input
                        name="q"
                        value="{{ $q }}"
                        placeholder="Search name, email, subject..."
                        class="rounded-full border-2 border-white/30 bg-white/10 text-white placeholder-white/50 px-4 py-2 text-sm focus:border-white focus:outline-none backdrop-blur-sm w-64"
                    />
                    <select
                        name="status"
                        class="rounded-full border-2 border-white/30 bg-white/10 text-white px-4 py-2 text-sm focus:border-white focus:outline-none"
                    >
                        <option value="unread" class="text-tpc-ink" @selected($status === 'unread')>Unread</option>
                        <option value="read" class="text-tpc-ink" @selected($status === 'read')>Read</option>
                        <option value="all" class="text-tpc-ink" @selected($status === 'all')>All</option>
                    </select>
                    <button class="rounded-full border-2 border-white bg-white px-5 py-2 text-sm font-bold text-tpc-primary hover:bg-tpc-accent hover:border-tpc-accent transition">
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

            <div class="space-y-4">
                @forelse ($messages as $m)
                    <a href="{{ route('admin.messages.show', $m) }}"
                       class="group block bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-tpc-primary/40 transition-all duration-300 overflow-hidden">

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

                                {{-- Date --}}
                                <div class="shrink-0 flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-0.5">
                                    <p class="text-xs font-semibold text-gray-500">{{ $m->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $m->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
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

@endsection
