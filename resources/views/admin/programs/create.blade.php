@extends('admin.layout', ['title' => 'New Program'])

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">Create Program</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">Add a new academic program.</p>
        </div>
        <a href="{{ route('admin.programs.index') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
            ← Back
        </a>
    </div>

    <form
        class="mt-6 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm"
        method="POST"
        action="{{ route('admin.programs.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-tpc-ink">Code</label>
                <input name="code" value="{{ old('code') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Department</label>
                <input name="department" value="{{ old('department') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('department') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Name</label>
                <input name="name" value="{{ old('name') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Description</label>
                <textarea name="description" rows="4"
                          class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20">{{ old('description') }}</textarea>
                @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- ✅ Logo upload --}}
            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Program Logo (optional)</label>
                <input type="file" name="logo" accept="image/png,image/jpeg,image/webp"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 bg-white px-3 py-2
                              focus:border-tpc-primary focus:ring-tpc-primary/20" />
                <p class="mt-2 text-xs text-tpc-ink/60">PNG/JPG/WEBP up to 5MB.</p>
                @error('logo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <input id="is_active" type="checkbox" name="is_active" value="1" checked
                       class="rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/20" />
                <label for="is_active" class="text-sm text-tpc-ink/80">Active</label>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white hover:bg-green-700">
                Save
            </button>
            <a href="{{ route('admin.programs.index') }}"
               class="rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                Cancel
            </a>
        </div>
    </form>
@endsection
