@props([
    'lahan',
    'dokumentasi',
])

@php
    $doc = $dokumentasi ?? null;
    $isEdit = isset($doc) && $doc->exists;
@endphp

<form action="{{ 
    $isEdit ? route('lahan.dokumentasi.update', [$lahan->lahan_id, $doc->dokumentasi_id]) : route('lahan.dokumentasi.store', $lahan->lahan_id) }}" 
    method="POST" 
    enctype="multipart/form-data" 
    class="w-full flex flex-col gap-6">

    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-start justify-start">
        <div class="self-stretch flex flex-col items-start justify-start p-6 text-center text-xl">
            <div id="dropzone" class="self-stretch rounded-xl bg-whitesmoke border-lightgray border-dashed border-[1px] overflow-hidden flex flex-col items-center justify-center p-10 gap-[22px] relative min-w-[405px] min-h-[215px]">
                <div class="w-2xl bg-blue-100 flex items-center justify-center rounded-full p-4 mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                @if($isEdit && $doc->image_path)
                    <div id="existingImagePreview" class="absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center p-4">
                        <div class="flex-1 w-full flex items-center justify-center mb-4 overflow-hidden">
                            <img src="{{ Storage::url($doc->image_path) }}" 
                                class="max-w-full max-h-full object-contain rounded-lg" 
                                alt="Current Image">
                        </div>
                        <div class="flex gap-2 mt-auto">
                            <x-main.primary-button type="button" 
                                    onclick="browseFile()">
                                Change Image
                            </x-main.primary-button>
                        </div>
                    </div>
                @else
                    <div class="self-stretch flex flex-col items-center justify-start gap-3">
                        <div class="self-stretch relative leading-10 font-semibold text-gray-700" id="dropzoneText">Drop File Here</div>
                        <div class="self-stretch flex flex-col items-center justify-start gap-5 text-sm text-darkslategray">
                            <div class="self-stretch relative leading-5 inline-block">Drag and drop your PNG, JPG, WebP, SVG images here or browse</div>
                            <button type="button" 
                                    onclick="browseFile()" 
                                    class="w-fit relative rounded-md underline leading-5 font-medium text-mediumslateblue border-none hover:text-blue-700 transition-colors cursor-pointer">
                                Browse File
                            </button>
                        </div>
                    </div>
                @endif
                    <div id="imagePreview" class="hidden absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center p-4">
                        <div class="flex-1 w-full flex items-center justify-center mb-4 overflow-hidden">
                            <img id="previewImage" 
                                class="max-w-full max-h-full object-contain rounded-lg" 
                                alt="Preview">
                        </div>

                        <div class="flex gap-2 mt-auto">
                            <x-main.primary-button type="button" 
                                    onclick="removeImage()">
                                Remove
                            </x-main.primary-button>
                            <x-main.primary-button type="button" 
                                    onclick="browseFile()">
                                Change
                            </x-main.primary-button>
                        </div>
                    </div>
                <input type="file" 
                        id="fileInput" 
                        name="image" 
                        accept="image/*" 
                        class="hidden">
            </div>
            <x-main.input-error :messages="$errors->get('image')" data-turbo-temporary class="mt-2" />
        </div>
    </div>

    <div class="relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-sm text-darkslategray">
        <div class="self-stretch flex flex-col items-start justify-start p-6">
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Nama Dokumentasi</x-main.input-label>
                <x-main.text-input type="text"
                        name="nama" 
                        value="{{ old('nama', $doc->nama ?? '') }}"
                        placeholder="Masukkan judul dokumentasi..." 
                        class="flex-1 leading-5 bg-transparent"/>
                <x-main.input-error :messages="$errors->get('nama')" data-turbo-temporary class="mt-2" />
            </div>
        </div>
    </div>

    <div class="relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-sm text-darkslategray">
        <div class="self-stretch flex flex-col items-start justify-start p-6">
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Deskripsi</x-main.input-label>
                <div class="self-stretch flex-1 rounded-lg bg-white border-gray-300 border-solid border-[1px] overflow-hidden flex flex-row items-start justify-start text-darkgray">
                    <textarea name="deskripsi" 
                                rows="5" 
                                placeholder="Masukkan deskripsi..." 
                                class="flex-1 border-none outline-none resize-none leading-5 bg-transparent font-outfit">{{ old('deskripsi', $doc->deskripsi ?? '') }}</textarea>
                    <x-main.input-error :messages="$errors->get('deskripsi')" data-turbo-temporary class="mt-2" />
                </div>
            </div>
        </div>
    </div>

    <div class="self-stretch flex flex-row items-center justify-end gap-3">
        <a href="{{ route('lahan.dokumentasi.index', $lahan) }}" 
            class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
            Cancel
        </a>
        <x-main.primary-button type="submit" 
                class="py-3 px-4 gap-2 font-medium">
            {{ $isEdit ? 'Update' : 'Save' }}
        </x-main.primary-button>
    </div>
</form>