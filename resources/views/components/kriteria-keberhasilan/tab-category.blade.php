@props(['tab_aktif', 'lahan'])

<div class="relative rounded-lg flex flex-row items-start justify-start text-left text-sm text-slategray font-outfit bg-lightgray p-0.5">
    <div class="self-stretch rounded-lg bg-lightgray flex flex-row items-center justify-start">
        @php
            $tabs = [
                'penatagunaan' => 'Penatagunaan Lahan',
                'revegetasi' => 'Revegetasi',
                'penyelesaian' => 'Penyelesaian Akhir',
            ];
        @endphp

        @foreach($tabs as $key => $label)
            <a
                href="{{ route('lahan.kriteria-keberhasilan.show', ['lahan' => $lahan->lahan_id, 'tab' => $key]) }}"
                class="self-stretch rounded-md flex flex-row items-center justify-center py-2.5 px-3 no-underline
                {{ $tab_aktif === $key ? 'bg-white !text-darkslategray font-bold' : 'bg-transparent !text-slategray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
