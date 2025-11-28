@props(['show' => null, 'djs'])

<div class="space-y-4">
    <label class="block">
        <span class="text-sm text-gray-700">Title</span>
        <input class="mt-1 block w-full border rounded" type="text" name="title" value="{{ old('title', $show->title ?? '') }}" required>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Slug</span>
        <input class="mt-1 block w-full border rounded" type="text" name="slug" value="{{ old('slug', $show->slug ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Host</span>
        <select class="mt-1 block w-full border rounded" name="dj_id">
            <option value="">Select DJ</option>
            @foreach($djs as $dj)
                <option value="{{ $dj->id }}" @selected(old('dj_id', $show->dj_id ?? '') == $dj->id)>{{ $dj->stage_name ?? $dj->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Day of Week</span>
        <input class="mt-1 block w-full border rounded" type="text" name="day_of_week" value="{{ old('day_of_week', $show->day_of_week ?? '') }}">
    </label>
    <div class="grid grid-cols-2 gap-4">
        <label class="block">
            <span class="text-sm text-gray-700">Start Time</span>
            <input class="mt-1 block w-full border rounded" type="time" name="start_time" value="{{ old('start_time', $show->start_time ?? '') }}">
        </label>
        <label class="block">
            <span class="text-sm text-gray-700">End Time</span>
            <input class="mt-1 block w-full border rounded" type="time" name="end_time" value="{{ old('end_time', $show->end_time ?? '') }}">
        </label>
    </div>
    <label class="block">
        <span class="text-sm text-gray-700">Hero Image URL</span>
        <input class="mt-1 block w-full border rounded" type="text" name="hero_image" value="{{ old('hero_image', $show->hero_image ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Stream URL</span>
        <input class="mt-1 block w-full border rounded" type="text" name="stream_url" value="{{ old('stream_url', $show->stream_url ?? '') }}">
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Status</span>
        <select class="mt-1 block w-full border rounded" name="status">
            @foreach(['scheduled','live','completed','cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $show->status ?? 'scheduled') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm text-gray-700">Description</span>
        <textarea class="mt-1 block w-full border rounded" name="description" rows="4">{{ old('description', $show->description ?? '') }}</textarea>
    </label>
    <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_live" value="1" @checked(old('is_live', $show->is_live ?? false))>
        <span>Mark as currently live</span>
    </label>
    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    </div>
</div>

