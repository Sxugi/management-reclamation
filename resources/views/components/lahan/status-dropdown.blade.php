@props([
    'status' => 'Active', 
    'lahan' => null,
    'statuses' => ['Active', 'Non - Active', 'Finished']
])

<div x-data="{ open: false, status: '{{ $status }}' }" class="w-full relative flex flex-col items-start justify-start text-left text-smt">
    <!-- Current Status Button -->
    <button 
        @click="open = !open" 
        type="button"
        class="self-stretch rounded-lg bg-slategray-200 h-7 flex flex-row items-center justify-center py-2 px-3 border-none gap-3 font-outfit hover:bg-gray-200"
    >
        <div class="flex-1 flex flex-row items-center justify-start text-white capitalize">
            <div class="flex-1 relative leading-5 font-medium font-outfit">{{ $status }}</div>
        </div>
        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <!-- Dropdown Menu -->  
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-10 mt-1 w-full rounded-lg top-8 bg-slategray-200 ring-black/5 focus:outline-hidden"
    >
        <div class="py-1">
            @foreach($statuses as $option)
                <form method="POST" action="{{ route('lahan.update-status', $lahan ? $lahan->lahan_id : '') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $option }}">
                    <button 
                        type="submit"
                        class="w-full text-left block px-3 py-2 text-sm text-white hover:bg-gray-200 font-outfit border-none bg-slategray-200 rounded-lg
                              {{ $status === $option ? 'bg-slategray-200' : '' }}"
                    >
                        {{ $option }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</div>