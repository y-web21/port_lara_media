@props(['name'])
<label class="btn-gray">
    <input class="sm:pl-10 m-2 cursor-pointer" type="file" name="{{ $name }}" accept="image/png, image/jpeg, image/png, image/gif"
        {{ $attributes }}>
    {{ $slot }}
</label>
