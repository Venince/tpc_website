{{-- resources/views/admin/about-slides/index.blade.php --}}
@extends('admin.layout')

@section('title', 'About Slides')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">About Section – Slides</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage the image carousel shown in the About section on the homepage.</p>
        </div>
        <a href="{{ route('admin.about-slides.create') }}"
           class="inline-flex items-center gap-2 bg-tpc-primary text-white text-sm font-bold px-4 py-2 rounded hover:bg-tpc-secondary transition">
            + Add Slide
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($slides->isEmpty())
        <div class="border border-dashed border-gray-300 rounded py-16 text-center text-gray-400 text-sm">
            No slides yet. <a href="{{ route('admin.about-slides.create') }}" class="text-tpc-primary font-bold underline">Add the first one.</a>
        </div>
    @else
        <div class="overflow-x-auto rounded border border-gray-200">
            <table class="min-w-full text-sm bg-white">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 w-16">Order</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 w-24">Image</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Title</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600 w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($slides as $slide)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-500 text-center">{{ $slide->sort_order }}</td>
                            <td class="px-4 py-3">
                                <img src="{{ asset('storage/' . $slide->image_path) }}"
                                     alt="{{ $slide->title ?: 'Slide ' . $slide->sort_order }}"
                                     class="h-14 w-24 object-contain bg-gray-50 rounded border border-gray-200">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $slide->title ?: '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($slide->is_active)
                                    <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">Active</span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold px-2 py-0.5 rounded-full">Hidden</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.about-slides.edit', $slide) }}"
                                   class="text-tpc-primary font-bold hover:underline text-xs">Edit</a>

                                <form action="{{ route('admin.about-slides.destroy', $slide) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this slide?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold hover:underline text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
