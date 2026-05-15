<input
    {{ $disabled ? 'disabled' : '' }}

    {!! $attributes->merge([
        'class' => '
            border-white/10
            bg-white/5
            text-white
            placeholder-gray-400
            focus:border-[#D97757]
            focus:ring-[#D97757]
            rounded-xl
            shadow-sm
            w-full
            px-4
            py-3
            text-sm
            sm:text-base
        '
    ]) !!}>