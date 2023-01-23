@extends('layouts.master')
@php
  $disp_gnav = false;
@endphp

@section('content')
  @include('navigation-menu')

  <div class="relative my-12 mt-6 w-full overflow-hidden rounded bg-white shadow-2xl">
    <div class="px-0 py-6 lg:px-4">

      <div>
        <h2 class="text-2xl font-semibold">記事一覧</h2>
        <hr>
      </div>

      <div class="">
      </div>
    </div>
  </div>
@endsection
