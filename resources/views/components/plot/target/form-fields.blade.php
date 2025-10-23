@props(['action', 'method' => 'POST', 'data' => [], 'isEdit' => false, 'plot' => null])

@php
    $indicators = config('indicators.targets');
    $preSelected = collect($data)
        ->filter(fn($v) => $v !== null && $v !== '')
        ->keys()
        ->values()
        ->toArray();
@endphp

<form
    method="POST"
    action="{{ $action }}"
    x-data='{"selected": @json($preSelected) }'
    class="space-y-6"
    autocomplete="off"
>
    @csrf
    @if(strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($indicators as $key => $field)
            @php
                $checked = in_array($key, old('selected', $preSelected));
                $val = old('value.' . $key, $data[$key] ?? '');
            @endphp
            <div class="space-y-1">
                <label class="block text-sm font-medium" title="Masukkan target indikator {{ $field['label'] }}">
                    {{ $field['label'] }}
                    <span class="text-xs text-gray-400">({{ $field['satuan'] }})</span>
                    <span class="ml-1 text-xs text-blue-400 cursor-pointer" title="{{ $field['description'] }}">?</span>
                </label>
                <div class="flex items-center gap-3">
                    <x-main.text-input
                        type="number"
                        step="0.01"
                        name="value[{{ $key }}]"
                        value="{{ $val }}"
                        x-bind:disabled="!selected.includes('{{ $key }}')"
                        :class="selected.includes('{{ $key }}') ? 'bg-white' : 'bg-gray-200'"
                        class="disabled:bg-gray-100 disabled:cursor-not-allowed transition"
                    />
                    <input
                        type="checkbox"
                        name="selected[]"
                        value="{{ $key }}"
                        x-model="selected"
                        @checked($checked)
                        class="rounded w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                        title="Aktifkan indikator ini"
                    >
                </div>
                @error('value.' . $key)
                    <x-main.input-error :messages="$errors->get('value.' . $key)"/>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t bg-white z-10 sticky bottom-0">
        <button type="button" @click="$dispatch('close-modal', 'form-target-reklamasi')"
            class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
            Cancel
        </button>
        <x-main.primary-button type="submit">
            {{ $isEdit ? 'Update' : 'Save' }}
        </x-main.primary-button>
    </div>
</form>