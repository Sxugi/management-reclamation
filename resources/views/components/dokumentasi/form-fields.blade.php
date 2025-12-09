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

    <div class="w-full rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-start justify-start">
        <div class="w-full flex flex-col items-start justify-start p-6 text-center text-xl">
            <div id="dropzone" class="w-full rounded-xl bg-whitesmoke border-lightgray border-dashed border-[1px] overflow-hidden flex flex-col items-center justify-center p-10 gap-[22px] relative min-h-[300px]">
                @if($isEdit && $doc->image_path)
                    <div id="existingImagePreview" class="absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center p-4">
                        <div class="flex-1 w-full flex items-center justify-center mb-4 overflow-hidden max-h-[400px]">
                            <img src="{{ Storage::url($doc->image_path) }}" 
                                class="max-w-full max-h-[350px] w-auto h-auto object-contain rounded-lg" 
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
                    <div id="dropzoneContent" class="w-full flex flex-col items-center justify-start gap-3">
                        <div class="w-fit bg-blue-100 flex items-center justify-center rounded-full p-4 mb-4">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div class="w-full relative leading-10 font-semibold text-gray-700" id="dropzoneText">Drop File Here</div>
                        <div class="w-full flex flex-col items-center justify-start gap-5 text-sm text-darkslategray">
                            <div class="w-full relative leading-5 inline-block">Drag and drop your PNG, JPG, WebP, SVG images here or browse</div>
                            <button type="button" 
                                    onclick="browseFile()" 
                                    class="w-fit relative rounded-md underline leading-5 font-medium text-mediumslateblue border-none hover:text-blue-700 transition-colors cursor-pointer bg-transparent">
                                Browse File
                            </button>
                        </div>
                    </div>
                @endif
                    <div id="imagePreview" class="hidden absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center p-4">
                        <div class="flex-1 w-full flex items-center justify-center mb-4 overflow-hidden max-h-[400px]">
                            <img id="previewImage" 
                                class="max-w-full max-h-[350px] w-auto h-auto object-contain rounded-lg shadow-md" 
                                alt="Preview">
                        </div>

                        <div class="flex gap-2 mt-auto">
                            <x-main.primary-button type="button" 
                                    onclick="removeImage()"
                                    class="bg-red-500 hover:bg-red-600">
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

    <div class="w-full rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-sm">
        <div class="w-full flex flex-col items-start justify-start p-6">
            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Nama Dokumentasi
                    <span class="text-tomato">*</span>
                </x-main.input-label>
                <x-main.text-input type="text"
                    name="nama" 
                    value="{{ old('nama', $doc->nama ?? '') }}"
                    placeholder="Masukkan judul dokumentasi..." 
                    class="w-full leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Nama dokumentasi harus diisi')"
                    oninput="this.setCustomValidity('')"
                    />
                <x-main.input-error :messages="$errors->get('nama')" data-turbo-temporary  />
            </div>
        </div>
    </div>

    <div class="w-full rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-sm">
        <div class="w-full flex flex-col items-start justify-start p-6">
            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">Deskripsi</x-main.input-label>
                <textarea name="deskripsi" 
                    rows="5" 
                    placeholder="Masukkan deskripsi..." 
                    class="w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-0 rounded-md px-3 py-2 resize-none leading-5 bg-transparent font-outfit text-sm min-h-[120px] resize-y">{{ old('deskripsi', $doc->deskripsi ?? '') }}</textarea>
                <x-main.input-error :messages="$errors->get('deskripsi')" data-turbo-temporary  />
            </div>
        </div>
    </div>

    <div class="w-full flex flex-row items-center justify-end gap-3">
        <a href="{{ route('lahan.dokumentasi.index', $lahan) }}" 
            class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline">
            Cancel
        </a>
        <x-main.primary-button type="submit" 
                class="py-3 px-4 gap-2">
            {{ $isEdit ? 'Update' : 'Save' }}
        </x-main.primary-button>
    </div>
</form>

<x-main.modal name="image-error" focusable>
    <div
        x-data="{ title: '', message: '' }"
        x-init="
            // listen event untuk mengisi title & message
            window.addEventListener('show-image-error', e => {
                title = e.detail.title || 'Error';
                message = e.detail.message || '';
            });
        "
    >
        <div class="p-6 text-left whitespace-normal">
            <h2 class="text-lg font-medium text-gray-900" x-text="title">Error</h2>
            <p class="mt-2 text-sm text-gray-600" x-html="message"></p>

            <div class="mt-6 flex justify-end font-outfit">
                <x-main.danger-button
                    type="button"
                    @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'image-error' }))"
                    class="ml-3"
                >
                    {{ __('Close') }}
                </x-main.danger-button>
            </div>
        </div>
    </div>
</x-main.modal>