@props(['type'])
@php
    // set default value
    $attributes['formmethod'] = $attributes->has('formmethod') ?: 'post';
    $type = isset($type) ? $type : 'submit';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => '']) }}>{{ $slot }}</button>
