@props(['activities' => [], 'selected' => null])

<div class="self-stretch rounded-2xl bg-white border-gainsboro border-solid border-[1px] flex flex-col items-start">
    <div class="self-stretch border-gainsboro border-solid border-b-[1px] flex items-start py-5 px-6">
        <div class="h-6 flex flex-col items-start">
            <div class="relative leading-6 font-medium">Aktivitas</div>
        </div>
    </div>
    <div class="self-stretch flex flex-col items-start p-6 gap-6 text-sm text-darkslategray-200">
        <div class="self-stretch flex flex-col items-start gap-1.5">
            <x-main.input-label class="relative leading-5 font-medium">Pilih Jenis Aktivitas</x-main.input-label>
            <select 
                class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent"
                @change="$dispatch('activity-selected', $event.target.value)"
                :value="aktivitas"
            >
                <option value="">- Pilih Aktivitas -</option>
                <template x-for="(activity, key) in activityList" :key="key">
                    <option :value="key" x-text="activity.label" :selected="aktivitas === key"></option>
                </template>
            </select>
        </div>
    </div>
</div>