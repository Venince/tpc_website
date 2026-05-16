@extends('admin.layout', ['title' => 'Edit Person'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.programs.show', $program) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $program->code }}
            </a>
            <h1 class="text-lg font-bold text-tpc-ink">Edit Person</h1>
        </div>
    </div>

    <div class="max-w-lg">
        <form method="POST"
              action="{{ route('admin.programs.people.update', [$program, $person]) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Role --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">Role</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach (['head' => 'Program Head', 'coordinator' => 'Coordinator', 'instructor' => 'Instructor'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="{{ $val }}"
                                   {{ old('role', $person->role) === $val ? 'checked' : '' }}
                                   class="sr-only peer" />
                            <span class="block rounded-xl border-2 px-3 py-2.5 text-center text-xs font-semibold transition
                                         border-gray-200 text-tpc-ink/60
                                         peer-checked:border-tpc-primary peer-checked:bg-tpc-primary peer-checked:text-white
                                         hover:border-tpc-primary/40">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('role') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $person->name) }}" required
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Title / Position <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="position" value="{{ old('position', $person->position) }}"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('position') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photo --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Photo</label>

                @if ($person->photo_path)
                    <div class="flex items-center gap-4 mb-3 p-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3">
                        <img src="{{ asset('storage/' . $person->photo_path) }}"
                             class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-sm shrink-0"
                             alt="{{ $person->name }}">
                        <div>
                            <p class="text-xs font-semibold text-tpc-ink mb-1.5">{{ $person->name }}</p>
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remove_photo" value="1"
                                       class="rounded border-red-300 text-red-500 focus:ring-red-300/30 w-3.5 h-3.5">
                                <span class="text-xs text-red-600 font-medium">Remove current photo</span>
                            </label>
                        </div>
                    </div>
                @endif

                <input type="file" name="photo" accept="image/png,image/jpeg,image/webp"
                       class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                              file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                              hover:file:bg-tpc-primary/15 transition" />
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB. Upload to replace current photo.</p>
                @error('photo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.programs.show', $program) }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
