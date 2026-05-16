@extends('admin.layout', ['title' => 'New Program'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-tpc-ink">Create Program</h1>
            <p class="text-xs text-tpc-ink/50 mt-0.5">Add a new academic program.</p>
        </div>
        <a href="{{ route('admin.programs.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Programs
        </a>
    </div>

    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data"
          class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6">
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">

            <div>
                <label class="block text-sm font-medium text-tpc-ink mb-1.5">Code</label>
                <input name="code" type="text" value="{{ old('code') }}" placeholder="e.g. BSIT"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('code') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-tpc-ink mb-1.5">Department</label>
                <input name="department" type="text" value="{{ old('department') }}" placeholder="e.g. Technology"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('department') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-tpc-ink mb-1.5">Program name</label>
                <input name="name" type="text" value="{{ old('name') }}" placeholder="Bachelor of Science in..."
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-tpc-ink mb-1.5">Description</label>
                <textarea name="description" rows="4" placeholder="Brief description of the program..."
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-tpc-ink mb-2">Program logo <span class="text-tpc-ink/40 font-normal">(optional)</span></label>
                <div class="flex items-center gap-4">
                    <span class="h-14 w-14 rounded-xl border border-tpc-primary/12 bg-tpc-primary/5 flex items-center justify-center text-2xl shrink-0">🎓</span>
                    <div class="flex-1">
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/webp"
                               class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                                      file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                                      hover:file:bg-tpc-primary/15 transition" />
                        <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB</p>
                    </div>
                </div>
                @error('logo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                    <input id="is_active" type="checkbox" name="is_active" value="1" checked
                           class="rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/20 w-4 h-4" />
                    <span class="text-sm text-tpc-ink/80">Active — visible on the public site</span>
                </label>
            </div>

        </div>

        <div class="mt-6 pt-5 border-t border-tpc-primary/8 flex flex-wrap gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Save Program
            </button>
            <a href="{{ route('admin.programs.index') }}"
               class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                Cancel
            </a>
        </div>

    </form>

@endsection
