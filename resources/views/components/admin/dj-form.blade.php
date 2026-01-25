@props(['dj'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <label class="block">
        <span class="text-sm text-gray-700">Name</span>
        <input class="mt-1 block w-full border rounded" type="text" name="name" value="{{ old('name', $dj->name ?? '') }}" required>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Stage Name</span>
        <input class="mt-1 block w-full border rounded" type="text" name="stage_name" value="{{ old('stage_name', $dj->stage_name ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Slug</span>
        <input class="mt-1 block w-full border rounded" type="text" name="slug" value="{{ old('slug', $dj->slug ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Email</span>
        <input class="mt-1 block w-full border rounded" type="email" name="email" value="{{ old('email', $dj->email ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Phone</span>
        <input class="mt-1 block w-full border rounded" type="text" name="phone" value="{{ old('phone', $dj->phone ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Specialty</span>
        <input class="mt-1 block w-full border rounded" type="text" name="specialty" value="{{ old('specialty', $dj->specialty ?? '') }}">
    </label>
    <label class="block md:col-span-2">
        <span class="text-sm text-gray-700">Avatar URL</span>
        <input class="mt-1 block w-full border rounded" type="text" name="avatar_url" value="{{ old('avatar_url', $dj->avatar_url ?? '') }}">
    </label>
    <label class="block md:col-span-2">
        <span class="text-sm text-gray-700">Bio</span>
        <textarea class="mt-1 block w-full border rounded" name="bio" rows="4">{{ old('bio', $dj->bio ?? '') }}</textarea>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Instagram</span>
        <input class="mt-1 block w-full border rounded" type="text" name="instagram" value="{{ old('instagram', $dj->instagram ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Twitter</span>
        <input class="mt-1 block w-full border rounded" type="text" name="twitter" value="{{ old('twitter', $dj->twitter ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Facebook</span>
        <input class="mt-1 block w-full border rounded" type="text" name="facebook" value="{{ old('facebook', $dj->facebook ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Mixcloud</span>
        <input class="mt-1 block w-full border rounded" type="text" name="mixcloud" value="{{ old('mixcloud', $dj->mixcloud ?? '') }}">
    </label>
    <label class="flex items-center space-x-2 md:col-span-2">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $dj->is_featured ?? false))>
        <span>Featured on homepage</span>
    </label>
</div>

<div class="flex justify-end mt-4">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
        Save
    </button>
</div>

