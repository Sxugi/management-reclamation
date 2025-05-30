<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-md bg-darkslategray px-3 py-2 text-sm font-semibold text-white hover:text-darkslategray shadow-sm hover:bg-gainsboro focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-darkslategray border-none font-outfit']) }}>
    {{ $slot }}
</button>