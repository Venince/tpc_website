@extends('admin.layout', ['title' => 'Create Admin/Staff'])

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-tpc-ink mb-1">Name</label>
            <input name="name" value="{{ old('name') }}"
                   class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-tpc-ink mb-1">Email</label>
            <input name="email" value="{{ old('email') }}"
                   class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-tpc-ink mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-tpc-ink mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
        </div>

        <div class="pt-2 flex gap-3">
            <button class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary">
                Create Admin/Staff
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5">
                Cancel
            </a>
        </div>
    </form>
@endsection
