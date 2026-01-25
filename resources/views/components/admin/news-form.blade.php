@props(['post' => null])

<label class="block mb-4">
    <span class="text-sm text-gray-700">Title</span>
    <input class="mt-1 block w-full border rounded" type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Slug</span>
    <input class="mt-1 block w-full border rounded" type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Excerpt</span>
    <textarea class="mt-1 block w-full border rounded" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Body</span>
    <textarea class="mt-1 block w-full border rounded" name="body" rows="8" required>{{ old('body', $post->body ?? '') }}</textarea>
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Hero Image</span>
    <input class="mt-1 block w-full border rounded" type="text" name="hero_image" value="{{ old('hero_image', $post->hero_image ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Author Name</span>
    <input class="mt-1 block w-full border rounded" type="text" name="author_name" value="{{ old('author_name', $post->author_name ?? '') }}">
</label>
<label class="block mb-4">
    <span class="text-sm text-gray-700">Tags (comma separated)</span>
    <input class="mt-1 block w-full border rounded" type="text" name="tags_string" value="{{ old('tags_string', $post?->tags ? implode(',', $post->tags) : '') }}">
</label>
<div class="grid grid-cols-2 gap-4">
    <label class="block">
        <span class="text-sm text-gray-700">Status</span>
        <select class="mt-1 block w-full border rounded" name="status">
            @foreach(['draft','scheduled','published'] as $status)
                <option value="{{ $status }}" @selected(old('status', $post->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Published At</span>
        <input class="mt-1 block w-full border rounded" type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
    </label>
</div>
<label class="flex items-center space-x-2 mt-4">
    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured ?? false))>
    <span>Mark as featured</span>
</label>
<div class="flex justify-end mt-6">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Article</button>
</div>

