@php
$form_value = [];
$form_value['status_id'] = 0;

if (isset($article)) {
    $form_value += ['title' => $article->title];
    $form_value += ['content' => $article->content];
    $form_value['status_id'] = $article->status;
}

@endphp

<div class="px-4 pb-8">

    <form class="flex flex-col space-y-8">
        @csrf

        <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:space-x-4">

            <div class="w-full">
                <label class="text-xl">タイトル</label>
                <input id="new_title" type="text" name="title"
                    value="{{ isset($form_value['title']) ? $form_value['title'] : old('title') }}"
                    placeholder="タイトルを入力してください" class="w-full form-active-blue text-opacity-10">
            </div>

            <div class="w-full">
                <label class="text-xl">投稿者</label>
                <input type="text" name="author" value="{{ Auth::user()->name }}" class="w-full form-active-blue text-opacity-10 bg-gray-200"
                    disabled>
            </div>
        </div>

        <div class="w-full">
            <label class="text-xl">投稿内容</label>
            <textarea id="new_content" name="content" placeholder="内容を入力してください"
                class="w-full form-active-blue text-opacity-10 minh-300px">{{ isset($form_value['content']) ? $form_value['content'] : old('content') }}</textarea>
        </div>

        <div class="py-1 w-full flex flex-wrap flex-col space-y-4 md:space-y-0 justify-start md:flex-row md:items-end">
            <label class="text-xl">公開ステータス(未)</label>
            <x-forms.radio name="status_id" :items="array('非公開', '公開')" :checked="(int)($form_value['status_id'])"/>
        </div>

        <hr>

        <div class="flex flex-col md:space-y-0 md:flex-row md:space-x-4 gap-4">
            <div class="flex w-full justify-around">

                <div class="flex items-center justyfy-center">
                    <button id="btn_submit_new_post" type="submit" formmethod="post"
                        formaction="{{ route('article.store') }}" class="btn-blue">投稿</button>
                </div>

                <div class="flex items-center justyfy-center">
                  <button id="btn_submit_select_image" type="submit" formmethod="post"
                        formaction="" class="btn-gray">画像を選択(未)</button>
                </div>
                <div class="flex items-center justyfy-center"><button type="button" onclick="javascript:history.back();"
                        class="btn-gray">戻る</button></div>
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
