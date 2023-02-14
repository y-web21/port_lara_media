@php
    $action = isset($action) ? $action : 'create';
    $id = isset($article) ? $id : '';

    // 作成初期値
    $formValue = [
        'title' => '',
        'content' => '',
        'status_id' => 0,
    ];
    // db の値の反映
    if (isset($article)){
        $formValue = [
            'title' => $article['title'],
            'content' => $article['content'],
            'status_id' => $article['status_id'],
        ];
    }
    // old の値の反映
    $formValue = [
        'title' => old('title', $formValue['title']),
        'content' => old('content', $formValue['content']),
        'status_id' => old('status_id', $formValue['status_id']),
    ];
@endphp

@push( 'script' )
    <script src="{{ asset('/js/form.js') }}" defer></script>
@endpush

<div class="px-4 pb-8">

    <form class="flex flex-col space-y-8">
        @csrf

        {{-- form inputs --}}
        <div class="flex flex-col space-y-4 items-end md:space-y-0 md:flex-row md:space-x-4">

            <div class="w-full">
                <x-forms.input-error for="title" class="mt-2" />
                <label class="text-xl">タイトル</label>
                <x-forms.input-box class id="new_title" placeholder="タイトルを入力してください"
                    name="title"
                    value="{{ old('title', $formValue['title'] ?: ''); }}"/>
            </div>

            <div class="w-full">
                <label class="text-xl">投稿者</label>
                <x-forms.input-box disabled class="bg-gray-200"
                    name="author"
                    value="{{ Auth::user()->name }}" />
            </div>
        </div>

        <div class="w-full">
            <x-forms.input-error for="content" class="mt-2" />
            <label class="text-xl">投稿内容</label>
            <textarea id="new_content" name="content" class="w-full form-active-blue text-opacity-10 minh-300px"
            placeholder="内容を入力してください">{{ $formValue['content'] }}</textarea>
        </div>

        <div class="py-1 w-full flex flex-wrap flex-col space-y-4 md:space-y-0 justify-start md:flex-row md:items-end">
            <label class="text-xl">公開ステータス</label>
            <x-forms.radio name="status_id" :items="$articleStatuses" key="id" value="name" :checked="(int)($formValue['status_id'])"/>
            <x-forms.input-error for="status_id" class="ml-4" />
        </div>

        <hr>

        {{-- form buttons --}}
        <div class="flex flex-col md:space-y-0 md:flex-row md:space-x-4 gap-4">
            <div class="flex w-full justify-around">

                <div class="flex items-center justify-center">
                    @if ($action === 'edit')
                        @method('patch')
                        <x-forms.button id="btn_edit" class="btn-green"
                            formaction="{{ route('article.update', ['article' => $id]) }}">
                            {{ __('Edit') }}
                        </x-forms.button>
                    @else
                        <x-forms.button id="btn_post" class="btn-blue"
                            formaction="{{ route('article.store') }}">
                            {{ __('Create') }}
                        </x-forms.button>
                    @endif
                </div>

                <div class="flex items-center justify-center">
                    <button id="btn_submit_select_image" type="submit" formmethod="post" formaction=""
                        class="btn-gray">画像を選択(未)</button>
                </div>
                <div class="flex items-center justify-center">
                    <x-forms.button class="btn-gray" type="button" onclick="javascript:history.back();">
                        {{ __('Go back') }}
                    </x-forms.button>
                </div>
            </div>

            <div class="w-full">
                @if (isset($image) && $image->count() !== 0)
                    <label class="text-xl">投稿画像(未実装)</label>
                    <img src="{{ asset('/storage/images/' . $image->name) }}" alt="{{ $image->description }}"
                        class="w-full maxw-300px mx-auto">
                @endif
            </div>
        </div>
    </form>
</div>
