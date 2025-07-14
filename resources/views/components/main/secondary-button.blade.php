<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase hover:bg-slategray-200 hover:text-white tracking-widest font-outfit']) }}>
    {{ $slot }}
</button>
