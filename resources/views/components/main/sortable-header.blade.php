<th scope="col" 
    class="h-6 py-3 px-3 text-left leading-5 font-bold border-gainsboro border-solid border-b-[0px] border-t-[0px] border-r-[1px] border-l-[0px] whitespace-nowrap group">
    <a href="{{ $sortUrl }}" class="flex items-center justify-between w-full text-darkslategray no-underline">
        <span>{{ $title }}</span>
        @if($isActive)
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 transition-transform duration-200 text-darkslategray ml-1 {{ $currentDirection === 'desc' ? 'rotate-180' : '' }}">
                <path d="M4.79175 7.39581L10.0001 12.6041L15.2084 7.39581"
                      stroke="darkslategray" stroke-width="1.5"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @else
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                 xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 transition-transform duration-200 text-darkslategray ml-1 opacity-50 group-hover:opacity-100">
                <path d="M4.79175 7.39581L10.0001 12.6041L15.2084 7.39581"
                      stroke="darkslategray" stroke-width="1.5"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @endif
    </a>
</th>