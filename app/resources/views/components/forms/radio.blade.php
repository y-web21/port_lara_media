@if (!isset($checked))
    {{ $checked = -1 }}
@endif

@foreach ($items as $item)
    <div>
        <input class="ml-3 mr-1" type="radio" name="{{ $item->$key }}" id="rb_{{ $item->$key }}_{{ $item->$key }}"
            value={{ $item->$key }} {{ $item->$key === $checked ? 'checked' : '' }}>
        <label for="rb_{{ $item->$key }}_{{ $item->$key }}">{{ $item->$value }}</label>
    </div>
@endforeach
