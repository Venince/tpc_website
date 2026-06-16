@extends('admin.layout', ['title' => 'Add Person'])

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
            <h1 class="text-lg font-bold text-tpc-ink">Add Person</h1>
            <p class="text-xs text-tpc-ink/50 mt-0.5">Adding to: <span class="font-semibold text-tpc-ink">{{ $program->name }}</span></p>
        </div>
    </div>

    <div class="max-w-lg">
        <form method="POST"
              action="{{ route('admin.programs.people.store', $program) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf

            {{-- Role --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">Role</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach (['head' => 'Program Head', 'coordinator' => 'Coordinator', 'instructor' => 'Instructor'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="{{ $val }}"
                                   {{ old('role', 'instructor') === $val ? 'checked' : '' }}
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
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition"
                       placeholder="e.g. Venince Dave Quiamco Autida" />
                @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Title / Position <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="position" value="{{ old('position') }}"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition"
                       placeholder="e.g. Associate Professor" />
                @error('position') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photo --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Photo <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>

                <div class="flex items-center gap-3">
                    <div id="photo-preview" class="hidden shrink-0">
                        <img id="preview-img" src="" alt="Preview"
                            class="h-20 w-20 rounded-full object-cover border-2 border-tpc-primary/20 shadow-sm" />
                    </div>
                    <div class="flex-1">
                        <div class="relative inline-flex">
                            <span class="inline-flex items-center gap-2 rounded-xl border border-tpc-primary/25 bg-white px-4 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition pointer-events-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16l4-4a3 3 0 014.24 0L16 16m-2-2l1.59-1.59A3 3 0 0119.41 12L21 13.41M8 11a2 2 0 110-4 2 2 0 010 4zm13 9H3a2 2 0 01-2-2V7a2 2 0 012-2h3.17A2 2 0 007 4h10a2 2 0 011.83 1H21a2 2 0 012 2v11a2 2 0 01-2 2z"/>
                                </svg>
                                <span id="photo-pick-label">Choose Photo</span>
                            </span>
                            <input id="photo-pick" type="file" accept="image/png,image/jpeg,image/webp"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </div>
                        <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB · will be cropped to square</p>
                    </div>
                </div>

                <input type="hidden" name="photo_crop" id="photo-crop" />
                @error('photo_crop') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Add Person
                </button>
                <a href="{{ route('admin.programs.show', $program) }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Crop Modal --}}
    <div id="crop-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-tpc-ink">Crop Photo</h2>
                <button type="button" id="crop-cancel" class="text-tpc-ink/40 hover:text-tpc-ink transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="crop-container" class="relative bg-gray-950" style="height:340px;"></div>
            <div class="px-5 py-4 flex items-center justify-between gap-3 border-t border-gray-100">
                <p class="text-xs text-tpc-ink/50">Drag to reposition · Pinch or scroll to zoom</p>
                <div class="flex gap-2 shrink-0">
                    <button type="button" id="crop-cancel-btn"
                            class="rounded-xl border border-tpc-primary/25 px-4 py-2 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                        Cancel
                    </button>
                    <button type="button" id="crop-confirm"
                            class="rounded-xl bg-tpc-primary px-4 py-2 text-sm font-semibold text-white hover:bg-tpc-secondary transition">
                        Use Photo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        const cropInput     = document.getElementById('photo-crop');
        const modal         = document.getElementById('crop-modal');
        const cropContainer = document.getElementById('crop-container');
        const confirmBtn    = document.getElementById('crop-confirm');
        const previewImg    = document.getElementById('preview-img');
        const previewWrap   = document.getElementById('photo-preview');
        const pickLabel     = document.getElementById('photo-pick-label');

        let cropper = null;

        function getPickInput() {
            return document.getElementById('photo-pick');
        }

        function resetFileInput() {
            const el = getPickInput();
            if (el) el.value = '';
        }

        getPickInput().addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                openModal(e.target.result);
            };
            reader.readAsDataURL(file);
            setTimeout(resetFileInput, 300);
        });

        function openModal(dataUrl) {
            cropContainer.innerHTML = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            const img = document.createElement('img');
            img.style.cssText = 'display:block;max-width:100%;';
            img.onload = function () {
                if (cropper) { cropper.destroy(); cropper = null; }
                cropper = new Cropper(img, {
                    aspectRatio      : 1,
                    viewMode         : 1,
                    dragMode         : 'move',
                    autoCropArea     : 0.9,
                    movable          : true,
                    zoomable         : true,
                    rotatable        : false,
                    scalable         : false,
                    cropBoxMovable   : false,
                    cropBoxResizable : false,
                    highlight        : false,
                    background       : true,
                    responsive       : true,
                });
            };
            cropContainer.appendChild(img);
            img.src = dataUrl;
        }

        function closeModal() {
            if (cropper) { cropper.destroy(); cropper = null; }
            cropContainer.innerHTML = '';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.getElementById('crop-cancel').addEventListener('click', closeModal);
        document.getElementById('crop-cancel-btn').addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        confirmBtn.addEventListener('click', function () {
            if (!cropper) return;
            const canvas  = cropper.getCroppedCanvas({ width: 400, height: 400 });
            const dataUrl = canvas.toDataURL('image/jpeg', 0.85);

            cropInput.value = dataUrl;
            previewImg.src  = dataUrl;
            previewImg.classList.remove('hidden');
            if (previewWrap) previewWrap.classList.remove('hidden');
            pickLabel.textContent = 'Change Photo';

            closeModal();
        });
    })();
    </script>

@endsection
