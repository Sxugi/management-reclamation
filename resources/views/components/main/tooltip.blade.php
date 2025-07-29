@if($content && trim($content) !== '')
    <div x-data="{ showTooltip: false }" 
         @mouseenter="showTooltip = true" 
         @mouseleave="showTooltip = false" 
         class="relative inline-block cursor-help">
        
        {{-- Shortened text --}}
        <span class="truncate block max-w-[150px]">
            {{ Str::limit($content, $maxLength) }}
        </span>
        
        {{-- Tooltip --}}
        <div x-show="showTooltip" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @class([
                 'absolute z-50 px-3 py-2 text-xs text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64',
                 'bottom-full mb-2' => $position === 'top',
                 'top-full mt-2' => $position === 'bottom',
                 'left-1/2 transform -translate-x-1/2' => in_array($position, ['top', 'bottom']),
             ])
             style="display: none;">
            {{ $content }}
            
            {{-- Arrow --}}
            @if($position === 'top')
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
            @elseif($position === 'bottom')
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-900"></div>
            @endif
        </div>
    </div>
@else
    <span class="text-gray-400">-</span>
@endif