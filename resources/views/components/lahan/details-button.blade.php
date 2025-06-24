@props(['lahan'])

<a href="{{ route('detail-lahan.dashboard', $lahan->lahan_id) }}" {{ $attributes->merge(['class' => 'self-stretch relative shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-slategray-200 border-none overflow-hidden flex flex-row justify-center py-3 px-4 text-left text-[15px] font-bold text-white font-outfit no-underline hover:bg-gray-200']) }}>
    View Details
</a>