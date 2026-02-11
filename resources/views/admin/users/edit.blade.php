@extends('admin.layout', ['title' => 'Edit Admin/Staff'])

@section('content')
    <div class="mb-5">
        <h1 class="text-xl font-semibold text-tpc-ink">Edit Admin/Staff</h1>
        <p class="mt-1 text-sm text-tpc-ink/60">
            Update Admin/Staff details and reset password if needed.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-tpc-ink">Name</label>
            <input name="name" value="{{ old('name', $user->name) }}"
                   class="mt-2 w-full rounded-xl border border-tpc-primary/15 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-tpc-ink">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}"
                   class="mt-2 w-full rounded-xl border border-tpc-primary/15 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="rounded-2xl border border-tpc-primary/10 bg-tpc-primary/5 p-4">
            <div class="text-sm font-semibold text-tpc-ink">Reset Password (optional)</div>
            <p class="text-xs text-tpc-ink/60 mt-1">Leave blank to keep the current password.</p>

            <div class="mt-3">
                <label class="block text-sm font-semibold text-tpc-ink">New Password</label>
                <input name="password" type="password"
                       class="mt-2 w-full rounded-xl border border-tpc-primary/15 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-3">
                <label class="block text-sm font-semibold text-tpc-ink">Confirm New Password</label>
                <input name="password_confirmation" type="password"
                       class="mt-2 w-full rounded-xl border border-tpc-primary/15 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-tpc-primary/20" />
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="rounded-xl border border-tpc-primary/20 bg-white px-4 py-2.5 text-sm font-semibold text-tpc-ink/70 hover:bg-tpc-primary/5">
                Back
            </a>

            <button class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-tpc-secondary">
                Save Changes
            </button>
        </div>
    </form>
@endsection
