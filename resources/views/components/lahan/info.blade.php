@props(['for' => null])

<div {{ $attributes->class("text-sm text-gray-300 font-outfit") }}>
    {{ $slot }}
</div>