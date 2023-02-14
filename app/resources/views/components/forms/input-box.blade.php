@props(['name', 'value'])

<input {{ $attributes->merge(['type' => 'text',
    'class' => 'w-full form-active-blue text-opacity-10']) }}
    name={{ $name }} value={{ $value }}>
