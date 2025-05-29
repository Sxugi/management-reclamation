@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-indigo-500 rounded-md shadow-sm font-outfit']) }}>
