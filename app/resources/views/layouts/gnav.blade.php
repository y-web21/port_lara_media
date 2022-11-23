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
                <a class="nav-focus mx-3 block cursor-pointer pt-2"
                  href="{{ route("{$nav_cotegory}.{$action}") }}">{{ ucfirst($nav_cotegory) }}</a>
              </li>
            @endif
          @endforeach
        </ul>

        <div class="dropdown relative inline-block lg:mr-10">
          <p class="minw-100px whitespace-nowrap p-2 px-2">
            {{ Auth::check() ? Auth::user()->name : 'Login Menu' }}</p>
          <div class="dropdown-content w-100px absolute hidden">
            <ul class="rounded-md border border-gray-400 bg-white px-2 pt-2 text-sm">
              @if (!Auth::check())
                @foreach (Navigation::LIST_GLOBAL_LOGIN as $nav_cotegory => $action)
                  <li><a class="whitespace-no-wrap nav-focus mx-auto mb-2 block pt-1"
                      href="{{ route("{$nav_cotegory}.{$action}") }}">{{ ucfirst($nav_cotegory) }}</a>
                @endforeach
              @else
                @foreach (Navigation::LIST_GLOBAL_LOGIN_AUTH as $nav_cotegory => $action)
                  <li><a class="whitespace-no-wrap nav-focus mx-auto mb-2 block pt-1"
                      href="{{ route("{$nav_cotegory}.{$action}") }}">{{ ucfirst($nav_cotegory) }}</a>
                @endforeach
                <form method="POST" name="form_logout" action="{{ route('logout') }}">
                  @csrf
                  <a class="whitespace-no-wrap nav-focus mx-auto mb-2 block pt-1"
                    href="javascript:form_logout.submit()">ログアウト</a>
                </form>
                </li>
              @endif

            </ul>
          </div>
        </div>

      </div>
    </nav>
  @break

  @default
    {{-- global navigation less --}}
@endswitch
