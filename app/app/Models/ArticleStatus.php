<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
    use HasFactory;

    # created_at, updated_at フィールドを持たない
    public $timestamps = false;

    /**
     * 記事を取得
     *
     * @return void
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'id', 'status_id');
    }

}
