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
        'status',
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

}
