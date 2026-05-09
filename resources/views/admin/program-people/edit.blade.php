{{-- resources/views/admin/program-people/edit.blade.php --}}
@extends('admin.layout', ['title' => 'Edit Person'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.programs.show', $program) }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to {{ $program->code }}
        </a>
    </div>

    <div class="max-w-lg">
        <h2 class="text-base font-bold text-tpc-ink mb-6">Edit Person</h2>

        <form method="POST"
              action="{{ route('admin.programs.people.update', [$program, $person]) }}"
              enctype="multipart/form-data"
              class="space-y-5">
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
                @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $person->name) }}" required
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                              focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Title / Position <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="position" value="{{ old('position', $person->position) }}"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                              focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                @error('position') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photo --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Photo</label>

                @if ($person->photo_path)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $person->photo_path) }}"
                             class="h-16 w-16 rounded-full object-cover border border-tpc-primary/20"
                             alt="{{ $person->name }}">
                        <label class="inline-flex items-center gap-2 text-sm text-tpc-ink/70 cursor-pointer">
                            <input type="checkbox" name="remove_photo" value="1"
                                   class="rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/20">
                            Remove current photo
                        </label>
                    </div>
                @endif

                <input type="file" name="photo" accept="image/png,image/jpeg,image/webp"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                              focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                <p class="mt-1 text-xs text-tpc-ink/40">PNG / JPG / WEBP, max 5 MB. Upload to replace current photo.</p>
                @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.programs.show', $program) }}"
                   class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">Cancel</a>
            </div>
        </form>
    </div>

@endsection
