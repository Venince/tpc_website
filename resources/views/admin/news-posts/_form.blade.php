{{-- Vars: $newsPost (NewsPost|null) --}}
@php
    $n = $newsPost ?? null;
    $categories = ['Announcement', 'Event', 'Advisory', 'Scholarship'];
    $currentCategory = old('category', $n?->category ?? 'Announcement');
    if ($currentCategory && ! in_array($currentCategory, $categories, true)) {
        $categories[] = $currentCategory; // keep legacy categories selectable
    }
@endphp
<div class="space-y-4">
    <x-admin.card title="Post content" class="">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-admin.field label="Title" name="title" required class="sm:col-span-2">
                <input id="title" type="text" name="title" value="{{ old('title', $n?->title) }}" required placeholder="Enter post title…"
                       class="ui-input @error('title') is-invalid @enderror">
            </x-admin.field>

            <x-admin.field label="Category" name="category" required>
                <select id="category" name="category" class="ui-select @error('category') is-invalid @enderror">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($currentCategory === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </x-admin.field>

            <x-admin.field label="Excerpt" name="excerpt" hint="Optional short summary (max 255 characters).">
                <input id="excerpt" type="text" name="excerpt" value="{{ old('excerpt', $n?->excerpt) }}" maxlength="255" placeholder="Short summary…" class="ui-input">
            </x-admin.field>

            <x-admin.field label="Body" name="body" required class="sm:col-span-2">
                <textarea id="body" name="body" rows="12" required placeholder="Write your post content here…"
                          class="ui-textarea @error('body') is-invalid @enderror">{{ old('body', $n?->body) }}</textarea>
            </x-admin.field>
        </div>
    </x-admin.card>

    <x-admin.card :title="$n ? 'Add more photos' : 'Photos'" description="Optional · up to 20 images" class="">
        <x-admin.multi-image-upload name="photos" :label="$n ? 'New photos' : 'Photos'" :max="20" />
    </x-admin.card>
</div>
