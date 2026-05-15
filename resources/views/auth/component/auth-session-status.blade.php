@props(['status'])

@if ($status)

    <div
        {{ $attributes->merge([
            'class' => '
                mb-4
                rounded-xl
                border
                border-green-500/20
                bg-green-500/10
                px-4
                py-3
                text-sm
                text-green-400
            '
        ]) }}
    >
        {{ $status }}
    </div>

@endif