<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','page name is not defined')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <noscript>ウェブサイトを正しく表示するにはJavaScriptが必要です。<br>ブラウザの設定をオンにしてからページをリロードしてください。</noscript>
</head>

@php
$disp_header = @$disp_header ?: false;
$disp_gnav = @$disp_gnav ?: false;
$disp_footer = @$disp_footer ?: false;

@endphp

<body>
    <div class="footer-fixed">
        @section('header')
            {{-- @include('layouts.header', [ $disp_header ]) --}}
        @show

        @section('global-nav')
            {{-- @include('layouts.gnav', [ $disp_gnav ]) --}}
        @show

        <div class="container mx-auto">

            <main>
                <div class="container">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    @section('footer')
        {{-- @include('layouts.footer', [ $disp_footer ]) --}}
    @show
</body>

</html>
