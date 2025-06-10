@props(['for' => null])

<div {{ $attributes->class("text-sm font-bold text-white font-outfit") }}>
    {{ $slot }}
</div>