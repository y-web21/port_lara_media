<?php

namespace App\Http\Controllers;

use App\Models\Article;

class DashboardController extends Controller
{

    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Display a listing of the resource.
        *
        * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('member.my-posts')
            ->with('articles', $this->article->getMyPosts(config('const.pagination.per_page.my_posts')));
    }
}
