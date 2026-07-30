@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#ff6b2b] text-start text-base font-medium text-[#ff6b2b] bg-[#241c19] focus:outline-none focus:text-[#ff6b2b] focus:bg-[#241c19] focus:border-[#ff6b2b] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#d4c5bf] hover:text-[#fdf5f1] hover:bg-[#241c19] hover:border-[#3b2f2b] focus:outline-none focus:text-[#fdf5f1] focus:bg-[#241c19] focus:border-[#3b2f2b] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
