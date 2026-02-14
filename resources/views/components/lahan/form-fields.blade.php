@props(['lahan' => null, 'users' => []])

<!-- Nama Lahan -->
<div x-data="{ 
        startYear: '{{ old('tahun_awal', $lahan?->tahun_awal ?? '') }}',
        
        // Getter for endYear
        get endYear() {
            return this.startYear ? parseInt(this.startYear) + 4 : '';
        }
    }" 
    class="space-y-1.5">
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
<div class="space-y-1.5" 
    x-data="{ 
         startYear: '{{ old('tahun_awal', $lahan?->tahun_awal ?? '') }}', 
         endYear: '',
         
         // 1. Method to update endYear when startYear changes
         calculate() {
             if (this.startYear) {
                 this.endYear = parseInt(this.startYear) + 4;
             } else {
                 this.endYear = '';
             }
         },

         // 2. Method to handle change event on startYear input
         init() {
             this.calculate(); 
         }
     }">
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label :value="__('Periode Tahun')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Tahun Awal -->
        <div>
            <input
                name="tahun_awal"
                id="tahun_awal"
                x-model="startYear"
                @change="calculate()"
                required
                oninvalid="this.setCustomValidity('Tahun awal harus diisi')"
                oninput="this.setCustomValidity('')"
                placeholder="Tahun Awal"
                value="{{ old('tahun_awal', $lahan?->tahun_awal ?? '') }}"
                class="text-sm border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit"
            >
            </input>
            <x-main.input-error :messages="$errors->get('tahun_awal')" data-turbo-temporary class="mt-2" />
        </div>

        <!-- Tahun Akhir -->
        <div>
            <input
                name="tahun_akhir"
                id="tahun_akhir"
                x-model="endYear"
                required
                oninvalid="this.setCustomValidity('Tahun akhir harus diisi')"
                oninput="this.setCustomValidity('')"
                placeholder="Tahun Akhir"
                value="{{ old('tahun_akhir', $lahan?->tahun_akhir ?? '') }}"
                disabled
                class="text-sm border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit disabled:cursor-not-allowed"
            >
            </input>
            <input type="hidden" name="tahun_akhir" :value="endYear">
            <x-main.input-error :messages="$errors->get('tahun_akhir')" data-turbo-temporary class="mt-2" />
        </div>
    </div>
</div>

<!-- PIC Reklamasi -->
<div class="space-y-1.5 mb-4">
    <div class="flex flex-row relative leading-5 font-medium gap-1.5">
        <x-main.input-label for="pic_id" :value="__('PIC Reklamasi (Owner)')" class="text-darkslategray-200" />
        <span class="text-red-500">*</span>
    </div>
    <div>
        <select
            name="pic_id" 
            id="pic_id"
            required
            oninvalid="this.setCustomValidity('PIC Reklamasi harus diisi')"
            oninput="this.setCustomValidity('')"
            class="text-sm border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md block w-full font-outfit"
        >
            <option value="">Pilih User PIC</option>
            <option value="null" @selected(old('pic_id', $lahan?->pic_id) === null && isset($lahan))>
                Data Arsip Lama
            </option>
            @foreach($users as $user)
                <option value="{{ $user->user_id }}" @selected(old('pic_id', $lahan?->pic_id ?? '') == $user->user_id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <p class="text-xs text-gray-500 mt-1">*Pilih "Data Arsip Lama" jika menginput data lampau/arsip.</p>
    <x-main.input-error :messages="$errors->get('pic_id')" class="mt-2" />
</div>