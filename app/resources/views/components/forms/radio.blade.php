@if (!isset($checked))
    {{ $checked = -1 }}
@endif

@foreach ($items as $id => $label )
  <div>
    <input class="ml-3 mr-1" type="radio" name="{{ $name }}" id="rb_{{ $name }}_{{ $id }}" value={{ $id }}
    {{ $id === $checked ? 'checked' : '' }}>
    <label for="rb_{{ $name }}_{{ $id }}">{{ $label }}</label>
  </div>
@endforeach

