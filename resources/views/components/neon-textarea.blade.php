@props([
    'name',
    'label' => null,
    'rows' => 3,
])

<div>
    <label class="block text-sm font-bold text-accent mb-2">
        {{ $label ?? ucfirst(str_replace('_', ' ', $name)) }}
    </label>
    <textarea 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'w-full bg-glass border border-glass-border rounded-xl px-5 py-4 text-light placeholder-gray-500 focus:border-accent focus:ring-2 focus:ring-accent/30 transition-all resize-none']) }}
    >{{ old($name, $slot) }}</textarea>
</div>