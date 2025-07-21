<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Kriteria Keberhasilan Reklamasi</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Kriteria Keberhasilan Reklamasi</div>
            </div>
        </div>
    </x-slot>

    <div class="self-stretch flex flex-row items-center justify-between text-sm font-outfit">
        <x-kriteria-keberhasilan.tab-category :lahan="$lahan" :tab_aktif="$tab_aktif"/>
        <x-kriteria-keberhasilan.action-button :lahan="$lahan" :tab_aktif="$tab_aktif"/>
    </div>

    @if(session('success'))
        <div data-turbo-temporary class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div data-turbo-temporary class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($tab_aktif === 'penatagunaan')
        @include('components.kriteria-keberhasilan.penatagunaan-form', ['kriteria_keberhasilan' => $kriteria, 'readonly' => true])
    @elseif ($tab_aktif === 'revegetasi')
        @include('components.kriteria-keberhasilan.revegetasi-form', ['kriteria_keberhasilan' => $kriteria, 'readonly' => true])
    @elseif ($tab_aktif === 'penyelesaian')
        @include('components.kriteria-keberhasilan.penyelesaian-form', ['kriteria_keberhasilan' => $kriteria, 'readonly' => true])
    @endif

</x-main-layout>