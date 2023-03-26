@if (!isset($checked))
    {{ $checked = -1 }}
@endif

<div>
    @foreach ($items as $item)
        <input type="radio"
            id="rb_{{ $item->$key }}_{{ $item->$key }}"
            value={{ $item->$key }}
            {{ $item->$key === $checked ? 'checked' : '' }}
            {{ $attributes->merge(['class' => 'ml-3 mr-1'])->only(['form', 'name', 'class']) }}>
        <label for="rb_{{ $item->$key }}_{{ $item->$key }}">{{ $item->$value }}</label>
    @endforeach
</div>
