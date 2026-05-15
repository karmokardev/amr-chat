<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex
        items-center
        justify-center
        px-5
        py-3
        bg-[#D97757]
        border
        border-transparent
        rounded-xl
        font-semibold
        text-xs
        sm:text-sm
        text-white
        uppercase
        tracking-widest
        hover:bg-[#c96547]
        active:bg-[#b85a3d]
        focus:outline-none
        focus:ring-2
        focus:ring-[#D97757]
        focus:ring-offset-2
        transition
        duration-200
        w-full
    '
]) }}>
    {{ $slot }}
</button>