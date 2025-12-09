@props(['lahan' => null])

<!-- Nama Lahan -->
<div class="space-y-1.5">
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label for="nama_lahan" :value="__('Nama Lahan')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div>
        <x-main.text-input 
            id="nama_lahan"
            name="nama_lahan"
            type="text"
            class="block w-full text-sm"
            :value="old('nama_lahan', $lahan?->nama_lahan ?? '')"
            placeholder="Masukkan nama lahan"
            required
            oninvalid="this.setCustomValidity('Nama lahan harus diisi')"
            oninput="this.setCustomValidity('')"
        />
    </div>
    <x-main.input-error :messages="$errors->get('nama_lahan')" data-turbo-temporary class="mt-2" />
</div>

<!-- Luas Lahan -->
<div class="space-y-1.5">
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label for="luas_lahan" :value="__('Luas Lahan (ha)')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div>
        <x-main.text-input
            id="luas_lahan"
            name="luas_lahan"
            type="number"
            step="0.01"
            class="block w-full text-sm"
            :value="old('luas_lahan', $lahan?->luas_lahan ?? '')"
            placeholder="0.00"
            required
            oninvalid="this.setCustomValidity('Luas lahan harus diisi')"
            oninput="this.setCustomValidity('')"
        />
    </div>
    <x-main.input-error :messages="$errors->get('luas_lahan')" data-turbo-temporary class="mt-2" />
</div>

<!-- Periode Tahun -->
<div class="space-y-1.5">
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label :value="__('Periode Tahun')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Tahun Awal -->
        <div>
            <select
                name="tahun_awal"
                id="tahun_awal"
                required
                oninvalid="this.setCustomValidity('Tahun awal harus diisi')"
                oninput="this.setCustomValidity('')"
                class="text-sm border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit"
            >
                <option value="" disabled @selected(old('tahun_awal', $lahan?->tahun_awal ?? '') == '')>Tahun Awal</option>
                @for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++)
                    <option value="{{ $year }}" @selected(old('tahun_awal', $lahan?->tahun_awal) == $year)>
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
                required
                oninvalid="this.setCustomValidity('Tahun akhir harus diisi')"
                oninput="this.setCustomValidity('')"
                class="text-sm border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit"
            >
                <option value="" disabled @selected(old('tahun_akhir', $lahan?->tahun_akhir ?? '') == '')>Tahun Akhir</option>
                @for ($year = date('Y') - 1; $year <= date('Y') + 9; $year++)
                    <option value="{{ $year }}" @selected(old('tahun_akhir', $lahan?->tahun_akhir) == $year)>
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
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label for="pic_reklamasi" :value="__('PIC Reklamasi')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div>
        <x-main.text-input
            id="pic_reklamasi"
            name="pic_reklamasi"
            type="text"
            class="block w-full text-sm"
            :value="old('pic_reklamasi', $lahan?->pic_reklamasi ?? '')"
            placeholder="Masukkan nama PIC"
            required
            oninvalid="this.setCustomValidity('PIC reklamasi harus diisi')"
            oninput="this.setCustomValidity('')"
        />
    </div>
    <x-main.input-error :messages="$errors->get('pic_reklamasi')" data-turbo-temporary class="mt-2" />
</div>