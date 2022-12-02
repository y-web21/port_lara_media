@php
  $current_category = Helper::getUrlCategory(Request::path());
  //   var_dump($current_category);
@endphp

@switch($hid_gnav ?? true)
  @case(true)
    <nav class="sticky top-0 bg-gray-200 text-center text-black">
      <div class="flex w-full">
        <ul class="m-auto flex w-full max-w-screen-lg flex-wrap justify-around pb-2">
          @foreach (Navigation::LIST_GLOBAL as $nav_cotegory => $action)
            @if ($nav_cotegory === $current_category || '' === $current_category)
              <li>
                <a class="mx-3 block border-b border-transparent border-red-600 pt-2">{{ ucfirst($nav_cotegory) }}</a>
              </li>
            @else
              <li>
                <a class="mx-3 block cursor-pointer pt-2"
                  href="{{ route("{$nav_cotegory}.{$action}") }}">{{ ucfirst($nav_cotegory) }}</a>
              </li>
            @endif
          @endforeach
        </ul>

        <ul class="m-auto flex w-full max-w-screen-lg flex-wrap justify-around pb-2">
          @foreach (Navigation::LIST_GLOBAL_LOGIN as $nav_cotegory => $action)
            <li><a class="whitespace-no-wrap mx-auto mb-2 block pt-1"
                href="{{ route("{$nav_cotegory}.{$action}") }}">{{ ucfirst($nav_cotegory) }}</a>
          @endforeach
        </ul>
      </div>
    </nav>
  @break

  @default
    {{-- global navigation less --}}
@endswitch
