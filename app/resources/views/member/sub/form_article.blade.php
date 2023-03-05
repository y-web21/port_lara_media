@php
    $action = isset($action) ? $action : 'create';
    $id = isset($article) ? $id : '';

    // 作成初期値
    $formValue = [
        'title' => '',
        'content' => '',
        'status_id' => 0,
        'image_id' => 0,
    ];
    // db の値の反映
    if (isset($article)) {
        $formValue = [
            'title' => $article['title'],
            'content' => $article['content'],
            'status_id' => $article['status_id'],
            'image_id' => $article['image_id'],
        ];
    }
    // old の値の反映
    $formValue = [
        'title' => old('title', $formValue['title']),
        'content' => old('content', $formValue['content']),
        'status_id' => old('status_id', $formValue['status_id']),
        'image_id' => old('image_id', $formValue['image_id']),
    ];

    $preview_image = isset($article->image->path) ? asset($article->image->path) : '';

@endphp

@push('script')
    <script src="{{ asset('/js/form.js') }}" defer></script>
@endpush

<div class="px-4 py-8">

    <div class="flex flex-col space-y-8">

        {{-- form inputs --}}
        <div class="flex flex-col items-end space-y-4 md:flex-row md:space-y-0 md:space-x-4">

            <div class="w-full">
                <x-forms.input-error for="title" class="mt-2" />
                <label class="text-xl">タイトル</label>
                <x-forms.input-box class id="new_title" placeholder="タイトルを入力してください" name="title" form="main_form"
                    value="{{ old('title', $formValue['title'] ?: '') }}" />
            </div>

            <div class="w-full">
                <label class="text-xl">投稿者</label>
                <x-forms.input-box disabled class="bg-gray-200" name="author" form="main_form"
                    value="{{ Auth::user()->name }}" />
            </div>
        </div>

        <div class="w-full">
            <x-forms.input-error for="content" class="mt-2" />
            <label class="text-xl">投稿内容</label>
            <textarea form="main_form" id="new_content" name="content" class="form-active-blue minh-300px w-full text-opacity-10"
                placeholder="内容を入力してください">{{ $formValue['content'] }}</textarea>
        </div>

        <div class="flex w-full flex-col flex-wrap justify-start space-y-4 py-1 md:flex-row md:items-end md:space-y-0">
            <label class="text-xl">公開ステータス</label>
            <x-forms.radio form="main_form" name="status_id" :items="$articleStatuses" key="id" value="name"
                :checked="(int) $formValue['status_id']" />
            <x-forms.input-error for="status_id" class="ml-4" />
        </div>

        <hr>

        {{-- form buttons --}}
        <div class="flex flex-col items-start gap-4 md:flex-row md:space-y-0 md:space-x-4">
            <div class="flex w-full items-start justify-around">

                <div class="flex items-center justify-center">
                    @if ($action === 'edit')
                        <x-forms.button form="main_form" id="btn-edit" class="btn-green"
                            formaction="{{ route('article.update', ['article' => $id]) }}">
                            {{ __('Edit') }}
                        </x-forms.button>
                    @else
                        <x-forms.button form="main_form" id="btn-post" class="btn-blue"
                            formaction="{{ route('article.store') }}">
                            {{ __('Create') }}
                        </x-forms.button>
                    @endif
                </div>

                <div class="flex items-center justify-center">
                    <x-forms.input-file id="input" form="main_form" name="image">画像を選択</x-forms.input-file>
                </div>

                <div class="flex items-center justify-center">
                    <x-forms.button class="btn-gray" type="button" onclick="javascript:history.back();">
                        {{ __('Go back') }}
                    </x-forms.button>
                </div>
            </div>

            <div class="w-full">
                <x-forms.input-error for="image" class="mt-2" />
                <label class="text-xl">画像</label>
                <figure id="figure">
                    @if ($preview_image)
                        <figcaption id="disp-filename">プレビュー</figcaption>
                    @else
                        <figcaption id="disp-filename">選択されていません</figcaption>
                    @endif
                    <img src="{{ $preview_image }}" id="figureImage">
                </figure>
            </div>
        </div>
    </div>

    <form id="main_form" enctype="multipart/form-data">
        @csrf
        @if ($action === 'edit')
            @method('put')
        @endif
    </form>
</div>
