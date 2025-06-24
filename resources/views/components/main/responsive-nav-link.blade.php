@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full text-start text-base font-medium !text-white bg-gainsboro focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full text-start text-base font-medium !text-darkslategray hover:!text-white bg-gainsboro hover:bg-darkslategray focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>