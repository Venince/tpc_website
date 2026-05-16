@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\User[] $users */
@endphp

@extends('admin.layout', ['title' => 'Manage Admin/Staff'])

@section('content')

{{-- Page Header --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-8">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Admin / Staff</h1>
        </div>
        <p class="text-sm text-gray-500 pl-3">Manage administrator and staff accounts.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-tpc-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-tpc-secondary active:scale-95 transition-all duration-150 shadow-sm shrink-0">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Admin/Staff
    </a>
</div>

{{-- Stats bar --}}
@if($users->total() > 0)
<div class="flex items-center gap-4 mb-5 text-xs text-gray-500">
    <span><span class="font-bold text-gray-800">{{ $users->total() }}</span> total</span>
    <span class="h-3 w-px bg-gray-200"></span>
    <span class="font-bold text-tpc-primary">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
</div>
@endif

@if($users->isEmpty())
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="h-16 w-16 rounded-2xl bg-tpc-primary/8 flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
        </div>
        <p class="text-sm font-semibold text-gray-700 mb-1">No accounts yet</p>
        <p class="text-xs text-gray-400 mb-5">Create the first admin or staff account to get started.</p>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-tpc-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-tpc-secondary transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Admin/Staff
        </a>
    </div>

@else

    {{-- ── MOBILE: card list (< sm) ── --}}
    <div class="flex flex-col gap-3 sm:hidden">
        @foreach($users as $user)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-4">
                    {{-- Avatar --}}
                    <div class="h-10 w-10 rounded-xl bg-tpc-primary/10 flex items-center justify-center shrink-0">
                        <span class="text-sm font-extrabold text-tpc-primary">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        <span class="inline-block mt-1.5 bg-tpc-primary/10 text-tpc-primary text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Admin/Staff
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex border-t border-gray-100">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 py-3 text-sm font-bold text-tpc-primary hover:bg-tpc-primary/5 active:bg-tpc-primary/10 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                        </svg>
                        Edit
                    </a>
                    <div class="w-px bg-gray-100"></div>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="flex-1"
                          onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-1.5 py-3 text-sm font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        <div class="pt-2">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ── DESKTOP: table (≥ sm) ── --}}
    <div class="hidden sm:block overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Name</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Role</th>
                    <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr class="hover:bg-tpc-primary/3 transition-colors duration-100">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-extrabold text-tpc-primary">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $user->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block bg-tpc-primary/10 text-tpc-primary text-xs font-bold px-2.5 py-1 rounded-full">
                                Admin/Staff
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1 rounded-xl border border-gray-100 overflow-hidden">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-tpc-primary hover:bg-tpc-primary/5 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                    Edit
                                </a>
                                <div class="w-px h-5 bg-gray-100"></div>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                      onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-500 hover:bg-red-50 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
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

    <div class="mt-4 hidden sm:block">
        {{ $users->links() }}
    </div>

@endif

@endsection
