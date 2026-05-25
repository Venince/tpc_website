@extends('admin.layout')

@section('title', 'Organizational Chart')

@section('page_actions')
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Organizational Chart</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage people and hierarchy displayed on the public org chart page.</p>
        </div>
        <a href="{{ route('admin.org-chart.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-tpc-primary/90 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Person
        </a>
    </div>
@endsection

@section('content')

{{-- Live preview link --}}
<div class="mb-5 flex items-center gap-2 rounded-xl bg-tpc-primary/5 border border-tpc-primary/15 px-4 py-3 text-sm">
    <svg class="h-4 w-4 text-tpc-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    <span class="text-gray-600">View the public page:</span>
    <a href="{{ route('org-chart') }}" target="_blank"
       class="font-semibold text-tpc-primary hover:underline underline-offset-2">
        /org-chart
        <svg class="inline h-3.5 w-3.5 ml-0.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
        </svg>
    </a>
</div>

@if ($nodes->isEmpty())
    {{-- Empty state --}}
    <div class="py-16 text-center text-gray-400">
        <svg class="mx-auto h-12 w-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
        <p class="text-sm font-medium">No people added yet.</p>
        <a href="{{ route('admin.org-chart.create') }}"
           class="mt-3 inline-block text-sm font-semibold text-tpc-primary hover:underline">
            Add the first person →
        </a>
    </div>
@else

    {{-- ── Mobile card list (visible below md) ───────────────────────────── --}}
    <div class="flex flex-col gap-3 md:hidden">
        @foreach ($nodes as $node)
            <div class="relative rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">

                {{-- Accent bar --}}
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl
                    {{ $node->is_active ? 'bg-tpc-primary' : 'bg-gray-300' }}">
                </div>

                <div class="pl-4 pr-4 py-4">
                    {{-- Top row: avatar + name + status --}}
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 overflow-hidden rounded-full shrink-0 ring-2 ring-tpc-primary/15">
                            <img src="{{ $node->photoUrl() }}"
                                 alt="{{ $node->name }}"
                                 class="h-full w-full object-cover"
                                 loading="lazy">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <p class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $node->name }}</p>
                                @if ($node->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700 shrink-0">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-500 shrink-0">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>Hidden
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-tpc-primary font-medium mt-0.5 truncate">{{ $node->title }}</p>
                        </div>
                    </div>

                    {{-- Meta row --}}
                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-500">
                        @if ($node->department)
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                                {{ $node->department }}
                            </span>
                        @endif

                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            @if ($node->parents->isEmpty())
                                <span class="text-tpc-primary font-semibold">Root</span>
                            @else
                                {{ $node->parents->pluck('name')->join(', ') }}
                            @endif
                        </span>

                        <span class="flex items-center gap-1 text-gray-400">
                            <span class="font-mono">#{{ $loop->iteration }}</span>
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-3 flex items-center gap-2 pt-3 border-t border-gray-50">
                        <a href="{{ route('admin.org-chart.edit', $node) }}"
                           class="flex-1 text-center rounded-xl bg-tpc-primary/8 px-3 py-2 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/15 transition">
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('admin.org-chart.destroy', $node) }}"
                              onsubmit="return confirm('Remove {{ addslashes($node->name) }} from the org chart?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Desktop table (visible from md up) ────────────────────────────── --}}
    <div class="hidden md:block overflow-x-auto rounded-2xl border border-gray-100">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left w-10">#</th>
                    <th class="px-4 py-3 text-left">Name / Title</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Reports To</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($nodes as $node)
                    <tr class="hover:bg-tpc-primary/3 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 overflow-hidden rounded-full shrink-0 ring-1 ring-gray-200">
                                    <img src="{{ $node->photoUrl() }}"
                                         alt="{{ $node->name }}"
                                         class="h-full w-full object-cover"
                                         loading="lazy">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $node->name }}</p>
                                    <p class="text-xs text-tpc-primary">{{ $node->title }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $node->department ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600 text-xs">
                            @if ($node->parents->isEmpty())
                                <span class="inline-flex items-center gap-1 rounded-full bg-tpc-primary/8 px-2 py-0.5 text-[10px] font-semibold text-tpc-primary">Root</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($node->parents as $p)
                                        <span class="inline-block rounded-full bg-tpc-primary/8 px-2 py-0.5 text-[10px] font-semibold text-tpc-primary">
                                            {{ $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if ($node->is_active)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-[11px] font-semibold text-green-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] font-semibold text-gray-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>Hidden
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.org-chart.edit', $node) }}"
                                   class="rounded-lg bg-tpc-primary/8 px-3 py-1.5 text-xs font-semibold text-tpc-primary hover:bg-tpc-primary/15 transition">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.org-chart.destroy', $node) }}"
                                      onsubmit="return confirm('Remove {{ addslashes($node->name) }} from the org chart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif

@endsection
