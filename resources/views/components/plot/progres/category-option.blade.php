@props(['selected' => null])

@php
    $categories = config('indicators.categories');
    $firstCategory = array_key_first($categories);
@endphp

<div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-start">
    <div class="self-stretch border-gainsboro border-solid border-b-[1px] flex items-start py-5 px-6">
        <div class="h-6 flex flex-col items-start">
            <div class="relative leading-6 font-medium">Kategori</div>
        </div>
    </div>
    <div class="self-stretch flex items-start justify-center flex-wrap content-start p-6 gap-6 text-sm text-darkslategray-200">
        @foreach($categories as $key => $cat)
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center">
                    <div class="w-5 relative rounded-full bg-white border-lightgray border-solid border-[1.3px] box-border h-5 overflow-hidden shrink-0 flex items-center justify-center cursor-pointer"
                         @click="$dispatch('category-selected', '{{ $key }}')">
                        <div 
                            class="w-3 h-3 rounded-full transition-colors duration-200"
                            :class="kategori === '{{ $key }}' ? 'bg-blue-500' : 'bg-transparent'"
                        ></div>
                    </div>
                </div>
                <label 
                    class="relative leading-5 font-medium cursor-pointer transition-colors duration-200"
                    :class="kategori === '{{ $key }}' ? 'text-blue-700' : 'text-darkslategray-200'"
                    @click="$dispatch('category-selected', '{{ $key }}')"
                >
                    {{ $cat['label'] }}
                </label>
            </div>
        @endforeach
    </div>
</div>