@props(['disabled' => false])

<div class="w-full relative flex items-center">
    <input  
        {{ $disabled ? 'disabled' : '' }} 
        type="password"
        {!! $attributes->merge(['class' => 'block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm px-3 py-2 box-border font-outfit']) !!}
    >
    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <img class="w-5 h-5 object-cover cursor-pointer toggle-password" 
             alt="Toggle password visibility" 
             src="{{ asset('images/eye-enabled.svg') }}">
    </div>
</div>