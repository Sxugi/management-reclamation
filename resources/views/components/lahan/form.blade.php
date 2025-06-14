<div class="mx-auto w-full rounded-2xl bg-white border border-gainsboro shadow-sm">
    <form 
        x-data="formData()" 
        method="POST" 
        action="{{ route('lahan.store') }}" 
        class="w-full"
    >
        @csrf
        
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            <x-lahan.form-fields />

            <!-- Hidden Coordinate Fields -->
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" />
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" />
            <x-main.input-error :messages="$errors->get('longitude')" class="mt-2" />
            <x-main.input-error :messages="$errors->get('latitude')" class="mt-2" />
        </div>
        
        <!-- Save Button -->
        <div class="flex items-center justify-center px-4 py-4 sm:px-6 sm:pb-6">
            <x-main.primary-button 
                type="submit" 
                class="bg-darkslategray-300 hover:bg-opacity-90 w-full sm:w-auto"
                x-bind:disabled="!isValidForm"
            >
                Save
            </x-main.primary-button>
        </div>
    </form>
</div>