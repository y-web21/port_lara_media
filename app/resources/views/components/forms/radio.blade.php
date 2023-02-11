@if (!isset($checked))
    {{ $checked = -1 }}
@endif

<div>
    @foreach ($items as $item)
        <input class="ml-3 mr-1" type="radio" name="{{ $name }}" id="rb_{{ $item->$key }}_{{ $item->$key }}"
            value={{ $item->$key }} {{ $item->$key === $checked ? 'checked' : '' }}>
        <label for="rb_{{ $item->$key }}_{{ $item->$key }}">{{ $item->$value }}</label>
    @endforeach
</div>
