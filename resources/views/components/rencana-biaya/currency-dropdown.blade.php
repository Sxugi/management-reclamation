@props([
    'selected' => '',
])

<div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center">
    <select name="currency" id="currency"
        class="text-sm font-outfit font-medium rounded-lg cursor-pointer bg-darkslategray py-3 text-white focus:border-darkslategray focus:ring-darkslategray border-none"
    >
        <option value="IDR" {{ $selected == 'IDR' ? 'selected' : '' }}>IDR</option>
        <option value="USD" {{ $selected == 'USD' ? 'selected' : '' }}>USD</option>
    </select>
</div>