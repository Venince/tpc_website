{{-- Shared form fields for create & edit --}}
{{-- Variables: $node (OrgChartNode|null), $parents (Collection) --}}

{{-- Name --}}
<div>
    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
    <input type="text" id="name" name="name"
           value="{{ old('name', $node?->name) }}"
           placeholder="e.g. Dr. Maria Santos"
           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm
                  focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 focus:outline-none
                  @error('name') border-red-400 @enderror"
           required>
    @error('name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Title / Position --}}
<div>
    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Position / Title <span class="text-red-500">*</span></label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $node?->title) }}"
           placeholder="e.g. College President"
           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm
                  focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 focus:outline-none
                  @error('title') border-red-400 @enderror"
           required>
    @error('title')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Department --}}
<div>
    <label for="department" class="block text-sm font-semibold text-gray-700 mb-1.5">Department / Office <span class="text-gray-400 font-normal">(optional)</span></label>
    <input type="text" id="department" name="department"
           value="{{ old('department', $node?->department) }}"
           placeholder="e.g. Office of the President"
           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm
                  focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 focus:outline-none">
</div>

{{-- Parent (Reports To) --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
        Reports To
        <span class="text-gray-400 font-normal">(select one or more)</span>
    </label>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm divide-y divide-gray-100 max-h-56 overflow-y-auto">
        @foreach ($parents as $parent)
            @if (!$node || $parent->id !== $node->id)
                <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-tpc-primary/4 cursor-pointer transition">
                    <input type="checkbox"
                           name="parent_ids[]"
                           value="{{ $parent->id }}"
                           {{ (collect(old('parent_ids', $node?->parents?->pluck('id')->toArray() ?? []))->contains($parent->id)) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/30">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $parent->name }}</p>
                        <p class="text-xs text-tpc-primary truncate">{{ $parent->title }}</p>
                    </div>
                </label>
            @endif
        @endforeach
    </div>
    <p class="mt-1 text-xs text-gray-400">Leave all unchecked to make this a top-level (root) node.</p>
    @error('parent_ids')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Sort Order --}}
<div>
    <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-1.5">Display Order</label>
    <input type="number" id="sort_order" name="sort_order" min="0"
           value="{{ old('sort_order', $node?->sort_order ?? 0) }}"
           class="w-32 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm
                  focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 focus:outline-none">
    <p class="mt-1 text-xs text-gray-400">Lower numbers appear first among siblings.</p>
</div>

{{-- Row placement --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Row Placement</label>
    <div class="flex gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="row" value="1"
                   {{ old('row', $node?->row ?? 1) == 1 ? 'checked' : '' }}
                   class="h-4 w-4 border-gray-300 text-tpc-primary focus:ring-tpc-primary/30">
            <span class="text-sm text-gray-700">Same row as siblings</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="row" value="2"
                   {{ old('row', $node?->row ?? 1) == 2 ? 'checked' : '' }}
                   class="h-4 w-4 border-gray-300 text-tpc-primary focus:ring-tpc-primary/30">
            <span class="text-sm text-gray-700">Next row (below siblings)</span>
        </label>
    </div>
    <p class="mt-1 text-xs text-gray-400">Controls whether this person appears beside or below their row-mates.</p>
</div>

{{-- Photo --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Photo <span class="text-gray-400 font-normal">(optional, max 2 MB)</span></label>

    @if ($node?->photo)
        <div class="mb-3 flex items-center gap-3">
            <div class="h-14 w-14 overflow-hidden rounded-full ring-2 ring-tpc-primary/20">
                <img src="{{ asset('storage/' . $node->photo) }}" alt="{{ $node->name }}"
                     class="h-full w-full object-cover">
            </div>
            <p class="text-xs text-gray-500">Current photo. Upload a new one to replace it.</p>
        </div>
    @endif

    <input type="file" id="photo" name="photo" accept="image/*"
           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm
                  file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/8 file:px-3 file:py-1.5
                  file:text-xs file:font-semibold file:text-tpc-primary hover:file:bg-tpc-primary/15
                  focus:outline-none @error('photo') border-red-400 @enderror">
    @error('photo')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Active toggle --}}
<div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           {{ old('is_active', $node?->is_active ?? true) ? 'checked' : '' }}
           class="h-4 w-4 rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/30">
    <label for="is_active" class="text-sm font-semibold text-gray-700">
        Visible on public org chart
        <span class="block text-xs font-normal text-gray-400">Uncheck to hide without deleting.</span>
    </label>
</div>
