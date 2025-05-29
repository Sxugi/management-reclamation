<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-darkslategray px-3 py-1.5 text-sm font-semibold text-white hover:bg-slategray-200 transition ease-in-out duration-150 font-outfit border-none']) }}>
    {{ $slot }}
</button>