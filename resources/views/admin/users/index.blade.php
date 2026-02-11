@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\User[] $users */
@endphp

@extends('admin.layout', ['title' => 'Manage Admin/Staff'])

@section('content')
    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 rounded-2xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                   hover:bg-tpc-secondary transition
                   focus:outline-none focus:ring-2 focus:ring-tpc-primary/30"
        >
            <span class="text-base leading-none">+</span>
            New Admin/Staff
        </a>
    </div>

    {{-- ✅ Mobile cards --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($users as $user)
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="font-semibold text-tpc-ink truncate">{{ $user->name }}</div>
                        <div class="text-sm text-tpc-ink/70 break-all">{{ $user->email }}</div>

                        <div class="mt-2">
                            <span class="rounded-full bg-tpc-primary/10 px-2 py-1 text-xs font-semibold text-tpc-primary">
                                Admin/Staff
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center gap-4">
                        <a
                            href="{{ route('admin.users.edit', $user) }}"
                            class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition"
                        >
                            Edit
                        </a>

                        <form
                            method="POST"
                            action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Delete this Admin/Staff account?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button class="text-sm font-semibold text-red-600 hover:text-red-700 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-tpc-primary/10 bg-white p-6 text-center text-tpc-ink/70">
                No Admin/Staff accounts yet.
            </div>
        @endforelse

        <div class="pt-2">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ✅ Desktop table --}}
    <div class="mt-5 hidden sm:block overflow-hidden rounded-2xl border border-tpc-primary/10 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-tpc-primary/5 text-tpc-ink/70">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">Role</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($users as $user)
                        <tr class="hover:bg-tpc-primary/5 transition">
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-tpc-ink/70">{{ $user->email }}</td>

                            <td class="px-4 py-3">
                                <span class="rounded-full bg-tpc-primary/10 px-2 py-1 text-xs font-semibold text-tpc-primary">
                                    Admin/Staff
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="font-semibold text-tpc-primary hover:text-tpc-secondary transition"
                                >
                                    Edit
                                </a>

                                <form
                                    class="inline"
                                    method="POST"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Delete this Admin/Staff account?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-3 font-semibold text-red-600 hover:text-red-700 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-tpc-ink/70" colspan="4">
                                No Admin/Staff accounts yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 hidden sm:block">
        {{ $users->links() }}
    </div>
@endsection
