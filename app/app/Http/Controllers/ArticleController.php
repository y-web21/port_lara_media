<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostArticleRequest;
use App\Models\Article;
use App\Models\ArticleStatus;
use App\Models\Image;
use Auth;
use Illuminate\View\ComponentAttributeBag;


class ArticleController extends Controller
{

    private $article;
    private $articleStatus;
    private $image;

    public function __construct(
        Article $article,
        ArticleStatus $articleStatus,
        Image $image
    ) {
        $this->article = $article;
        $this->articleStatus = $articleStatus;
        $this->image = $image;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->limit(200)->get();
        return view('public.articles', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $articleStatuses = ArticleStatus::all();
        return view('member.create_article', compact('articleStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePostArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostArticleRequest $request)
    {
        $imgId = $this->image->saveImage($request);
        if ($imgId <  1) {
            return redirect()->route('article.create')
                ->with('flash', __('Image upload failed.'));
        }

        if (!$this->article->postArticle($request, $imgId)) {
            abort(422, 'post failed.');
        };

        return redirect()->route('dashboard')
            ->with('flash', __('Post has been completed.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $articleStatuses = ArticleStatus::all();
        // author scope で自身の記事以外への要求は 404 とする
        $article = Article::query()->author()->findOrFail($id);
        return view('member.edit_article', compact('id', 'article', 'articleStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StorePostArticleRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Respon
     */
    public function update(StorePostArticleRequest $request, $id)
    {
        $imgId = $this->image->editImage($request, $id);
        if ($imgId !== null && $imgId <  1) {
            return redirect()->route('article.edit', $id)
            ->with('flash', __('Image upload failed.'));
        }
        if (!$this->article->updateArticle($request, $id, $imgId)) {
            abort(422, 'update failed.');
        };
        return redirect()->route('dashboard')
            ->with('flash', __('Update has been completed.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->article->deleteArticle($id)) {
            abort(422, 'deletion failed.');
        };
        return redirect()->route('dashboard')
            ->with('flash', __('Deletion has been completed.'));
    }
}
