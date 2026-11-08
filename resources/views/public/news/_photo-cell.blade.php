{{--
    One tile in a news post's photo grid. Must be included INSIDE the post's
    Alpine component (it calls openAt() to open the photo viewer).

    Variables:
      $i     – index of the photo (int)
      $src   – image URL
      $class – layout classes for the tile (aspect ratio / column span)
      $label – accessible label
      $more  – optional int; when > 0 a "+N" overlay is drawn on the tile
--}}
<button type="button" @click="openAt({{ $i }})"
        class="group relative block overflow-hidden bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-tpc-primary {{ $class }}"
        aria-label="{{ $label }}">
    <img src="{{ $src }}" alt="" loading="lazy"
         class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">

    @if (($more ?? 0) > 0)
        <span class="absolute inset-0 flex items-center justify-center bg-black/55 text-2xl font-bold text-white sm:text-3xl">
            +{{ $more }}
        </span>
    @endif
</button>
