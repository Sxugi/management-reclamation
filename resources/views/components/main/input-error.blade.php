@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'text-tomato text-xs mt-1']) }}>
        @foreach ((array) $messages as $message)
            <span>{{ $message }}</span>
        @endforeach
    </div>
@endif
