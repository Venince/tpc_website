{{--
    Reusable social links builder partial.
    Usage: @include('admin.services._social_links', ['existing' => $service->social_links ?? []])
    Requires: $platforms (passed from controller)
--}}
<div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5"
     x-data="{
         links: {{ json_encode(
             !empty($existing)
                 ? array_values($existing)
                 : [['platform' => 'facebook', 'url' => '', 'label' => '']]
         ) }},
         addLink() {
             this.links.push({ platform: 'facebook', url: '', label: '' });
         },
         removeLink(i) {
             this.links.splice(i, 1);
         },
         platformIcon(platform) {
             const icons = {
                 facebook:  'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z',
                 instagram: 'M0 0h24v24H0z|M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z',
                 twitter:   'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.213 5.567zm-1.161 17.52h1.833L7.084 4.126H5.117z',
                 youtube:   'M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58zM9.75 15.02V8.98L15.5 12z',
                 tiktok:    'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.3 6.3 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z',
                 linkedin:  'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z|M4 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4z',
                 other:     'M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101|M14.828 12.172a4 4 0 0 1 5.656 0l4 4a4 4 0 0 1-5.656 5.656l-1.102-1.101|M9 9l6 6',
             };
             return icons[platform] || icons.other;
         }
     }">

    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-bold text-gray-600">
            Social Media Links
            <span class="ml-1 font-normal text-gray-400">(optional)</span>
        </label>
        <button type="button" @click="addLink()"
                class="inline-flex items-center gap-1 rounded-full bg-tpc-primary/10 px-3 py-1 text-[11px] font-bold text-tpc-primary hover:bg-tpc-primary/20 transition">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Link
        </button>
    </div>

    <div class="space-y-3">
        <template x-for="(link, i) in links" :key="i">
            <div class="flex items-start gap-2 rounded-xl border border-gray-200 bg-white p-3">

                {{-- Platform icon badge --}}
                <div class="mt-0.5 shrink-0 h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="currentColor">
                        <path :d="platformIcon(link.platform).split('|')[0]"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0 space-y-2">
                    {{-- Platform select --}}
                    <select :name="`social_links[${i}][platform]`"
                            x-model="link.platform"
                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-700 focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition">
                        @foreach ($platforms as $key => $meta)
                            <option value="{{ $key }}">{{ $meta['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- URL --}}
                    <input type="url"
                           :name="`social_links[${i}][url]`"
                           x-model="link.url"
                           placeholder="https://..."
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs text-gray-700 placeholder-gray-400 focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition">

                    {{-- Optional custom label --}}
                    <input type="text"
                           :name="`social_links[${i}][label]`"
                           x-model="link.label"
                           placeholder="Custom label (optional)"
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs text-gray-700 placeholder-gray-400 focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition">
                </div>

                {{-- Remove --}}
                <button type="button" @click="removeLink(i)"
                        class="mt-0.5 shrink-0 inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>

        <div x-show="links.length === 0"
             class="rounded-xl border border-dashed border-gray-200 py-5 text-center text-xs text-gray-400">
            No social links added yet. Click <span class="font-semibold text-tpc-primary">Add Link</span> to get started.
        </div>
    </div>

    @error('social_links')
        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
    @enderror
    @error('social_links.*.url')
        <p class="mt-2 text-xs text-red-600">One or more URLs are invalid. Make sure they start with https://</p>
    @enderror
</div>
