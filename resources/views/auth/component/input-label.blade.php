@props(['value'])

<label {{ $attributes->merge([
    'class' => '
        block
        font-medium
        text-sm
        sm:text-base
        text-gray-200
        mb-1
    '
]) }}>
    {{ $value ?? $slot }}
</label>