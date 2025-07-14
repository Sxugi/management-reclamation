@props(['tahun_aktif', 'lahan'])

<div class="relative rounded-lg flex flex-row items-start justify-start text-left text-sm text-slategray font-outfit bg-lightgray p-0.5">
    <div class="self-stretch rounded-lg bg-lightgray flex flex-row items-center justify-start">
        @for($year = $lahan->tahun_awal; $year <= $lahan->tahun_akhir; $year++)
            <a href="{{ route('lahan.rencana-reklamasi.index', ['lahan' => $globalLahanId, 'tahun' => $year]) }}"
                class="self-stretch rounded-md flex flex-row items-center justify-center py-2.5 px-3 no-underline
                {{ $tahun_aktif == $year ? 'bg-white !text-darkslategray font-bold' : 'bg-transparent !text-slategray-100' }}">
                {{ $year }}
            </a>
        @endfor
    </div>
</div>