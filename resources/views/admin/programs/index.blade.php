@extends('admin.layout', ['title' => 'Programs'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-tpc-ink">Programs</h1>
            <p class="text-xs text-tpc-ink/50 mt-0.5">Manage academic programs offered by TPC.</p>
        </div>
        <a href="{{ route('admin.programs.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white
                  hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Program
        </a>
    </div>

    {{-- Mobile cards --}}
    <div class="flex flex-col gap-3 sm:hidden">
        @forelse ($programs as $prog)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-4 border-b border-tpc-primary/8">
                    @if ($prog->logo_path)
                        <img src="{{ asset('storage/' . $prog->logo_path) }}"
                             class="h-11 w-11 rounded-xl border border-tpc-primary/10 bg-white object-contain p-1 shrink-0"
                             loading="lazy" alt="{{ $prog->code }}">
                    @else
                        <span class="h-11 w-11 rounded-xl bg-tpc-primary/8 flex items-center justify-center text-xl shrink-0">🎓</span>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-block bg-tpc-primary/10 text-tpc-secondary text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg">
                                {{ $prog->code }}
                            </span>
                            @if ($prog->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Inactive
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-tpc-ink mt-0.5 truncate">{{ $prog->name }}</p>
                        @if($prog->department)
                            <p class="text-xs text-tpc-ink/50">{{ $prog->department }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4 px-4 py-3">
                    <a href="{{ route('admin.programs.show', $prog) }}"
                       class="text-xs font-semibold text-tpc-ink/50 hover:text-tpc-primary transition">Manage</a>
                    <a href="{{ route('admin.programs.edit', $prog) }}"
                       class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                    <form class="inline ml-auto" method="POST"
                          action="{{ route('admin.programs.destroy', $prog) }}"
                          onsubmit="return confirm('Delete this program?');">
                        @csrf @method('DELETE')
                        <button class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-tpc-primary/20 bg-white py-16 text-center">
                <p class="text-sm text-tpc-ink/40">No programs yet.</p>
                <a href="{{ route('admin.programs.create') }}"
                   class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                    + Create your first program
                </a>
            </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="hidden sm:block rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-[720px] w-full text-sm">
                <thead>
                    <tr class="bg-tpc-primary/5 text-tpc-ink/60">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide w-16">Logo</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Code</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Program</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Department</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-tpc-primary/5">
                    @forelse ($programs as $prog)
                        <tr class="hover:bg-tpc-primary/3 transition group">
                            <td class="px-5 py-3">
                                @if ($prog->logo_path)
                                    <img src="{{ asset('storage/' . $prog->logo_path) }}"
                                         alt="{{ $prog->code }}"
                                         class="h-10 w-10 rounded-xl border border-tpc-primary/10 bg-white object-contain p-1"
                                         loading="lazy" />
                                @else
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-tpc-primary/8 text-lg">🎓</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-block bg-tpc-primary/10 text-tpc-secondary text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg">
                                    {{ $prog->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-semibold text-tpc-ink">{{ $prog->name }}</td>
                            <td class="px-5 py-3 text-tpc-ink/55">{{ $prog->department ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if ($prog->is_active)
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.programs.show', $prog) }}"
                                   class="text-xs font-semibold text-tpc-ink/45 hover:text-tpc-primary transition">Manage</a>
                                <a href="{{ route('admin.programs.edit', $prog) }}"
                                   class="ml-4 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">Edit</a>
                                <form class="inline" method="POST"
                                      action="{{ route('admin.programs.destroy', $prog) }}"
                                      onsubmit="return confirm('Delete this program?');">
                                    @csrf @method('DELETE')
                                    <button class="ml-4 text-xs font-semibold text-red-500 hover:text-red-700 transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-16 text-center text-tpc-ink/40 text-sm" colspan="6">
                                No programs yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $programs->links() }}</div>

@endsection
