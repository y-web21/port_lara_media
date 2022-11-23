@extends('layouts.master')
@section('articles', 'Fictitious company')

@section('content')
  <h2>Articles</h2>
  <table>
    <thead>
      <tr>
        <th>タイトル</th>
        <th>内容</th>
        <th>状態</th>
        <th>削除日</th>
        <th>更新日</th>
        <th>作成日</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($articles as $article)
        <tr>
          <td>{{ $article->title }}</td>
          <td>{{ Helper::strLimit($article->content, 100) }}</td>
          <td>{{ $article->status }}</td>
          <td>{{ $article->deleted_at }}</td>
          <td>{{ $article->updated_at }}</td>
          <td>{{ $article->created_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
