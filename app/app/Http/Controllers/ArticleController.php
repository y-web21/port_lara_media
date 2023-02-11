<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostArticleRequest;
use App\Models\Article;
use App\Models\ArticleStatus;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
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
     * @param  \Illuminate\Http\StorePostArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostArticleRequest $request)
    {

        $validated = $request->validated();

        $newArticle = new Article;
        $newArticle->title = $validated['title'];
        $newArticle->content = $validated['content'];
        $newArticle->author = Auth::user()->id;
        $newArticle->status_id = $validated['status_id'];
        $newArticle->save();

        return redirect()->route('dashboard')->with('flash', __('Post has been completed.'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
