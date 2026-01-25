@props([
    'name',
    'type' => 'text',
    'value' => '',
    'label' => null,
    'required' => false,
])

<div>
    <label class="block text-sm font-bold text-accent mb-2">
        {{ $label ?? ucfirst(str_replace('_', ' ', $name)) }}
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full bg-glass border border-glass-border rounded-xl px-5 py-4 text-light placeholder-gray-500 focus:border-accent focus:ring-2 focus:ring-accent/30 transition-all']) }}
        {{ $required ? 'required' : '' }}
    />
</div>