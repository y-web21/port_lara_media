@props(['type'])
@php
    // set default value
    $attributes['formmethod'] = $attributes->has('formmethod') ?: 'post';
    $type = isset($type) ? $type : 'submit';
    $buttonDefault = strpos($attributes['class'], 'btn-') === false;
@endphp

<button type="{{ $type }}" {{ $attributes->class(['btn-gray' => $buttonDefault]) }}>{{ $slot }}</button>
