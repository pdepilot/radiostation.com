@props(['podcast' => null])

<label class="block mb-4">
    <span class="text-sm text-gray-700">Title</span>
    <input class="mt-1 block w-full border rounded" type="text" name="title" value="{{ old('title', $podcast->title ?? '') }}" required>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Slug</span>
    <input class="mt-1 block w-full border rounded" type="text" name="slug" value="{{ old('slug', $podcast->slug ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Host</span>
    <input class="mt-1 block w-full border rounded" type="text" name="host" value="{{ old('host', $podcast->host ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Sponsor</span>
    <input class="mt-1 block w-full border rounded" type="text" name="sponsor" value="{{ old('sponsor', $podcast->sponsor ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Cover Image</span>
    <input class="mt-1 block w-full border rounded" type="text" name="cover_image" value="{{ old('cover_image', $podcast->cover_image ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Audio URL</span>
    <input class="mt-1 block w-full border rounded" type="text" name="audio_url" value="{{ old('audio_url', $podcast->audio_url ?? '') }}" required>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Duration</span>
    <input class="mt-1 block w-full border rounded" type="text" name="duration" value="{{ old('duration', $podcast->duration ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Description</span>
    <textarea class="mt-1 block w-full border rounded" name="description" rows="5">{{ old('description', $podcast->description ?? '') }}</textarea>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Published At</span>
    <input class="mt-1 block w-full border rounded" type="datetime-local" name="published_at" value="{{ old('published_at', optional($podcast->published_at)->format('Y-m-d\TH:i')) }}">
</label>
<div class="flex justify-end mt-4">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Episode</button>
</div>

