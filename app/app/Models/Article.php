<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

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
     * 記事の状態を取得する
     *
     * @return void
     */
    public function status()
    {
        return $this->hasOne(ArticleStatus::class, 'id', 'status_id');
    }
}
