<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 更新可能フィールドの定義
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author',
        'updated_by',
        'status_id',
    ];

    /**
     * Eloquent 非表示フィールド定義
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * DBから取得したフィールドデータのキャストを定義する
     * 未指定フィールドは文字列にキャストされる
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * 記事の状態名を取得する
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->belongsTo(ArticleStatus::class, 'status_id');
    }

    /**
     * 公開状態にあるレコードに絞り込むローカルスコープ
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish($query)
    {
        return $query->where('status_id', '=', 2)->where('deleted_at', '=', null);
    }

    /**
     * 指定したユーザーの記事を返すスコープ
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param integer|string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthor($query, int|string|null $type = null)
    {
        $id = $type ?? Auth::user()->id;
        return $query->where('author', $id);
    }

    /**
     * 表示用のログインユーザの投稿一覧を取得する
     */
    public function getMyPosts(int $per_page = 10): LengthAwarePaginator
    {
        return $this->author()
            ->with('status')
            ->orderBy('articles.updated_at', 'desc')
            ->paginate($per_page);
    }

    /**
     * 既存の記事更新処理
     * @todo 更新可能フィールドが form html の改ざんによって送られてきた場合に問題がないか検討する
     * @param Illuminate\Http\Request $request
     * @param integer $id  レコードID
     * @return bool isSuccess
     */
    public function updateArticle($request, int $id): bool
    {
        $target = $this->query()->findOrFail($id);
        return $target->update($request->toArray());
    }
}
