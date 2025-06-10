@props(['lahan'])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="p-1.5 hover:bg-slategray-300 rounded-full">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg z-10 text-black">
        <a href="{{ route('lahan.edit', $lahan->lahan_id) }}" class="block px-4 py-2 hover:bg-gray-100 text-sm">Edit</a>
        <form action="{{ route('lahan.destroy', $lahan->lahan_id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-500" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus lahan ini?')">Hapus</button>
        </form>
    </div>
</div>