@if($content && trim($content) !== '')
    <div 
        x-data="{ showTooltip: false, pos: { top: 0, left: 0 } }"
        class="relative inline-block cursor-help"
        x-ref="trigger"
        @mouseenter="
            showTooltip = true;
            $nextTick(() => {
                const triggerRect = $refs.trigger.getBoundingClientRect();
                pos = {
                    top: '{{ $position }}' === 'top' 
                        ? triggerRect.top - 8 
                        : triggerRect.bottom + 8,
                    left: triggerRect.left + triggerRect.width / 2
                };
            });
        "
        @mouseleave="showTooltip = false"
    >
        {{-- Truncated Text --}}
        <span class="block overflow-hidden text-ellipsis whitespace-nowrap max-w-full">
            {{ Str::limit($content, $maxLength) }}
        </span>

        {{-- Tooltip --}}
        <template x-teleport="body">
            <div x-show="showTooltip"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 x-bind:style="`
                     position: fixed;
                     top: ${pos.top}px;
                     left: ${pos.left}px;
                     transform: translateX(-50%);
                     max-width: min(600px, calc(100vw - 2rem));
                     z-index: 9999;
                 `"
                 class="px-3 py-2 text-xs text-white bg-gray-900 rounded-lg shadow-lg text-left leading-snug">
                 
                <span class="inline-block text-left break-words max-w-fit">
                    {!! nl2br(e(trim($content))) !!}
                </span>

                {{-- Tooltip Arrow --}}
                <div 
                    x-bind:class="{
                        'absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900': '{{ $position }}' === 'top',
                        'absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-900': '{{ $position }}' === 'bottom'
                    }">
                </div>
            </div>
        </template>
    </div>
@else
    <span class="text-gray-400">-</span>
@endif
