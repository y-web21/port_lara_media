<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('New Post') }}
    </h2>
  </x-slot>

  <div class="my-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-2xl sm:rounded-lg">
        @include('member.sub.form_article')
      </div>
    </div>
  </div>
</x-app-layout>
