@props([
    'action',
    'method' => 'POST',
    'data' => [],
    'isEdit' => false,
    'kategori' => null,
    'aktivitas' => null,
    'jenisAktivitas' => null,
    'existingFiles' => [],
])

@php
    $categories = config('indicators.categories', []);
    $firstCategory = array_key_first($categories);
    $firstActivity = $firstCategory ? array_key_first($categories[$firstCategory]['activities'] ?? []) : null;

    $jenisAktivitasMap = [];
    foreach ($categories as $catKey => $cat) {
        foreach ($cat['activities'] ?? [] as $actKey => $act) {
            $id = \App\Models\JenisAktivitas::whereHas('kategoriAktivitas', function($q) use ($catKey) {
                $q->where('field', $catKey);
            })->where('field', $actKey)->value('jenis_aktivitas_id');
            $jenisAktivitasMap[$catKey][$actKey] = $id;
        }
    }
@endphp

@if($errors->has('target'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ $errors->first('target') }}
    </div>
@endif

<div class="w-full relative flex flex-col items-center gap-6 text-left text-base text-gray font-outfit"
    x-data="{
        kategori: @js($kategori ?: $firstCategory),
        aktivitas: @js($aktivitas ?: $firstActivity),
        categories: @js($categories),
        jenisAktivitasMap: @js($jenisAktivitasMap),
        get jenisAktivitasId() {
            return this.jenisAktivitasMap[this.kategori]?.[this.aktivitas] || null;
        },
        get activityList() {
            return this.kategori ? this.categories[this.kategori].activities : {};
        }
    }"
    @category-selected.window="kategori = $event.detail; aktivitas = Object.keys(categories[$event.detail]?.activities || {})[0] || ''"
    @activity-selected.window="aktivitas = $event.detail"
>
    <x-plot.progres.category-option :selected="$kategori ?: $firstCategory" />

    <template x-if="kategori">
        <x-plot.progres.activity-option
            :activities="[]"
            :selected="$aktivitas ?: $firstActivity"
        />
    </template>

    <template x-if="kategori && aktivitas">
        <x-plot.progres.form-fields
            :action="$action"
            :method="$method"
            :data="$data"
            :is-edit="$isEdit"
            x-bind:jenis-aktivitas-id="jenisAktivitasId"
            :existingFiles="$existingFiles"
        />
    </template>
</div>