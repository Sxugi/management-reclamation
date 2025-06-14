<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase hover:bg-gray-500 tracking-widest font-outfit']) }}>
    {{ $slot }}
</button>
