<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md bg-darkslategray px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slategray-200 border-none font-outfit transition']) }}>
    {{ $slot }}
</button>