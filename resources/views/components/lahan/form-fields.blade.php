@props(['lahan' => null])

<!-- Nama Lahan -->
<div class="space-y-1.5">
    <x-main.input-label for="nama_lahan" :value="__('Nama Lahan')" class="text-darkslategray-200" />
    <div>
        <x-main.text-input 
            id="nama_lahan"
            name="nama_lahan"
            type="text"
            class="block w-full"
            :value="old('nama_lahan', $lahan?->nama_lahan ?? '')"
            required
            placeholder="Masukkan nama lahan"
        />
    </div>
    <x-main.input-error :messages="$errors->get('nama_lahan')" data-turbo-temporary class="mt-2" />
</div>

<!-- Luas Lahan -->
<div class="space-y-1.5">
    <x-main.input-label for="luas_lahan" :value="__('Luas Lahan (ha)')" class="text-darkslategray-200" />
    <div>
        <x-main.text-input
            id="luas_lahan"
            name="luas_lahan"
            type="number"
            step="0.01"
            class="block w-full"
            :value="old('luas_lahan', $lahan?->luas_lahan ?? '')"
            required
            placeholder="0.00"
        />
    </div>
    <x-main.input-error :messages="$errors->get('luas_lahan')" data-turbo-temporary class="mt-2" />
</div>

<!-- Periode Tahun -->
<div class="space-y-1.5">
    <x-main.input-label :value="__('Periode Tahun')" class="text-darkslategray-200" />
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Tahun Awal -->
        <div>
            <select
                name="tahun_awal"
                id="tahun_awal"
                class="border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm block w-full font-outfit"
                required
            >
                <option value="" disabled {{ old('tahun_awal', $lahan?->tahun_awal ?? '') ? '' : 'selected' }}>Tahun Awal</option>
                @for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++)
                    <option value="{{ $year }}" {{ old('tahun_awal') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
            <x-main.input-error :messages="$errors->get('tahun_awal')" data-turbo-temporary class="mt-2" />
        </div>
        
        <!-- Tahun Akhir -->
        <div>
            <select
                name="tahun_akhir"
                id="tahun_akhir"
                class="border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md shadow-sm block w-full font-outfit"
                required
            >
                <option value="" disabled {{ old('tahun_akhir', $lahan?->tahun_akhir ?? '') ? '' : 'selected' }}>Tahun Akhir</option>
                @for ($year = date('Y') - 1; $year <= date('Y') + 9; $year++)
                    <option value="{{ $year }}" {{ old('tahun_akhir') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
            <x-main.input-error :messages="$errors->get('tahun_akhir')" data-turbo-temporary class="mt-2" />
        </div>
    </div>
</div>

<!-- PIC Reklamasi -->
<div class="space-y-1.5">
    <x-main.input-label for="pic_reklamasi" :value="__('PIC Reklamasi')" class="text-darkslategray-200" />
    <div>
        <x-main.text-input
            id="pic_reklamasi"
            name="pic_reklamasi"
            type="text"
            class="block w-full"
            :value="old('pic_reklamasi', $lahan?->pic_reklamasi ?? '')"
            required
            placeholder="Masukkan nama PIC"
        />
    </div>
    <x-main.input-error :messages="$errors->get('pic_reklamasi')" data-turbo-temporary class="mt-2" />
</div>