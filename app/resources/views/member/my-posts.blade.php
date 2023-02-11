<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="relative my-12 w-full overflow-hidden rounded bg-white shadow-2xl">
        <div class="px-0 py-6 lg:px-4">

            <table class="my-10 hidden table-fixed lg:table">
                <thead>
                    <tr class="font-medium text-gray-200">
                        <th class="x-4 w-2/15 border-r border-blue-200 bg-blue-900 py-2">タイトル</th>
                        <th class="x-4 w-6/15 border-r border-blue-200 bg-blue-900 py-2">内容</th>
                        <th class="x-4 w-1/15 border-r border-blue-200 bg-blue-900 py-2">状態</th>
                        <th class="x-4 w-2/15 border-r border-blue-200 bg-blue-900 py-2">更新日</th>
                        <th class="x-4 w-2/15 border-r border-blue-200 bg-blue-900 py-2">作成日</th>
                        <th class="x-4 w-2/15 border-r border-blue-800 bg-blue-900 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td class="w-2/15 border border-blue-800 px-4 py-2">{{ $article->title }}</td>
                            <td class="w-6/15 border border-blue-800 px-4 py-2">
                                {{ Helper::strlimit($article->content, 100) }}</td>
                            <td class="w-1/15 border border-blue-800 px-4 py-2 text-center">{{ $article->status->name }}</td>
                            <td class="w-2/15 border border-blue-800 px-4 py-2 text-center">{{ $article->updated_at }}
                            </td>
                            <td class="w-2/15 border border-blue-800 px-4 py-2 text-center">{{ $article->created_at }}
                            </td>
                            <td class="w-2/15 border border-blue-800 text-center">
                                <div class="flex items-center justify-around">
                                    <div>
                                        <button type="button" class="btn-green-sm"
                                            onclick="location.href='{{ route('article.edit', ['article' => $article->id]) }}'">編集</button>
                                    </div>
                                    <form action={{ route('article.destroy', ['article' => $article->id]) }}
                                        method="post" class="m-0">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn-red-sm">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="block lg:hidden">
                @foreach ($articles as $article)
                    <table class="my-10 mt-4 table-fixed">
                        <tbody>
                            <tr>
                                <th class="x-4 mt-4 w-1/4 border-t border-blue-800 bg-blue-900 py-2 text-gray-200">タイトル
                                </th>
                                <td class="w-3/4 border border-blue-800 px-4 py-2">{{ $article->title }}</td>
                            </tr>
                            <tr>
                                <th class="x-4 w-1/4 border-t border-b border-blue-200 bg-blue-900 py-2 text-gray-200">
                                    内容
                                </th>
                                <td class="w-3/4 border border-blue-800 px-4 py-2">
                                    {{ Helper::strlimit($article->content, 100) }}</td>
                            </tr>
                            <tr>
                                <th class="x-4 w-1/4 border-b border-blue-200 bg-blue-900 py-2 text-gray-200">状態</th>
                                <td class="w-3/4 border border-blue-800 px-4 py-2 text-center">{{ $article->status->name }}</td>
                            </tr>
                            <tr>
                                <th class="x-4 w-1/4 border-b border-blue-200 bg-blue-900 py-2 text-gray-200">更新日</th>
                                <td class="w-3/4 border border-blue-800 px-4 py-2 text-center">
                                    {{ $article->updated_at }}
                                </td>
                            </tr>
                            <tr>
                                <th class="x-4 w-1/4 border-b border-blue-200 bg-blue-900 py-2 text-gray-200">作成日</th>
                                <td class="w-3/4 border border-blue-800 px-4 py-2 text-center">
                                    {{ $article->created_at }}
                                </td>
                            </tr>
                            <tr>
                                <th class="x-4 w-1/4 border-b border-blue-800 bg-blue-900 py-2 text-gray-200">操作</th>
                                <td class="w-3/4 border border-blue-800 text-center">
                                    <div class="flex items-center justify-around">
                                        <div>
                                            <button type="button" class="btn-green-sm"
                                                onclick="location.href='{{ route('article.edit', ['article' => $article->id]) }}'">編集</button>
                                        </div>
                                        <form action={{ route('article.destroy', ['article' => $article->id]) }}
                                            method="post" class="m-0">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn-red-sm">削除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
            {{ $articles->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</x-app-layout>
