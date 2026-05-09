{{-- resources/views/admin/programs/index.blade.php --}}
@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\Program[] $programs */
@endphp

@extends('admin.layout', ['title' => 'Programs'])

@section('content')

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.programs.create') }}"
           class="inline-flex items-center gap-2 rounded-2xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                  hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
            <span class="text-base leading-none">+</span>
            New Program
        </a>
    </div>

    <div class="mt-5 overflow-x-auto rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <table class="min-w-[820px] w-full text-sm">
            <thead class="bg-tpc-primary/5 text-tpc-ink/70">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Logo</th>
                    <th class="px-4 py-3 text-left font-medium">Code</th>
                    <th class="px-4 py-3 text-left font-medium">Name</th>
                    <th class="px-4 py-3 text-left font-medium">Department</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-right font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($programs as $prog)
                    <tr class="hover:bg-tpc-primary/5 transition">
                        <td class="px-4 py-3">
                            @if ($prog->logo_path)
                                <img src="{{ asset('storage/' . $prog->logo_path) }}"
                                     alt="{{ $prog->code }} logo"
                                     class="h-10 w-10 sm:h-11 sm:w-11 rounded-xl border border-tpc-primary/10 bg-white object-contain p-1"
                                     loading="lazy" />
                            @else
                                <span class="inline-flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl bg-tpc-accent/30 text-tpc-secondary">
                                    🎓
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $prog->code }}</td>
                        <td class="px-4 py-3">{{ $prog->name }}</td>
                        <td class="px-4 py-3 text-tpc-ink/70">{{ $prog->department ?? '—' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($prog->is_active)
                                <span class="rounded-full bg-tpc-accent/30 px-2 py-1 text-xs font-semibold text-tpc-secondary">Active</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.programs.show', $prog) }}"
                               class="font-semibold text-tpc-ink/60 hover:text-tpc-primary transition">
                                Manage
                            </a>
                            <a href="{{ route('admin.programs.edit', $prog) }}"
                               class="ml-3 font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                                Edit
                            </a>
                            <form class="inline" method="POST"
                                  action="{{ route('admin.programs.destroy', $prog) }}"
                                  onsubmit="return confirm('Delete this program?');">
                                @csrf @method('DELETE')
                                <button class="ml-3 font-semibold text-red-600 hover:text-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-tpc-ink/70" colspan="6">No programs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $programs->links() }}
    </div>

@endsection
