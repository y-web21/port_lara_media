<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function path()
    {
        return $this->hasOne(ArticleStatus::class);
    }

    /**
     * 画像をローカルに保存し、DBにパスを格納する
     * @param Illuminate\Http\Request $request
     * @return int 画像テーブルのID、失敗は0を返す
     */
    public function saveImage($request): int
    {
        $img = $request->file('image');
        if (!isset($img)) return 0;

        // save to storage/public/images
        $path = $img->store('images', 'public');
        if (!$path) return 0;

        return $this->add($path);
    }

    /**
     * 新規画像レコードを追加
     * @param Illuminate\Http\Request $request
     */
    public function add(string $path): int
    {
        $newImage = new Image;
        $newImage->path = $path;
        return $newImage->save() ? $newImage->id : 0;
    }
}
