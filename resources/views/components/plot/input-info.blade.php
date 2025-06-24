@props(['initialPoints' => [], 'lahan' => null, 'plot' => null])

<div class="self-stretch rounded-2xl flex flex-col items-center justify-start text-darkgray">
    <!-- Title and Add Point Button -->
    <div class="self-stretch flex flex-row items-center justify-between gap-1 mb-6">
        <div class="flex-1 flex flex-col items-start justify-start gap-1 text-lg text-gray">
            <div class="self-stretch relative leading-7 font-semibold text-darkslategray font-outfit">Informasi Plot</div>
            <div class="self-stretch relative text-sm leading-5 text-slategray font-outfit">Nama plot dan informasi area</div>
        </div>
    </div>

    <div class="w-full">
        <div class="self-stretch flex flex-col items-start justify-start p-6">
            <div class="self-stretch flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <x-main.input-label>Nama Plot</x-main.input-label>
                    <x-main.text-input type="text" name="nama_plot" x-model="nama_plot" 
                           class="w-full rounded-lg border-lightgray border-solid border-[1px] p-2.5" />
                </div>
                
                <input type="hidden" name="lahan_id" value="{{ $lahan->lahan_id }}" />
                <input type="hidden" name="coordinates" x-model="JSON.stringify(points)" />
                
                <div class="flex flex-col gap-2">
                    <x-main.input-label>Luas Area (Ha)</x-main.input-label>
                    <x-main.text-input type="text" name="area" x-model="area"
                           class="w-full rounded-lg border-lightgray border-solid border-[1px] p-2.5" />
                </div>
            </div>
        </div>
    </div>
</div>