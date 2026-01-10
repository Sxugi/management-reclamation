<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg bg-darkslategray px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slategray-200 border-none font-outfit transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>