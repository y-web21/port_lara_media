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

    const IMG_NOTHING = 0;

    use HasFactory;

    protected $fillable = [
        'path',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function article()
    {
        return $this->hasOne(Article::class);
    }

    /**
     * 画像をローカルに保存し、DBにパスを格納する
     * @param Illuminate\Http\Request $request
     * @return int 画像テーブルのID、失敗は0を返す
     */
    public function saveImage($request): ?int
    {

        // 画像保存要求なし
        $img = $request->file('image');
        // if ($request->image === null) {
        if (!isset($img)) return null;

        // save to storage/public/images
        $path = $img->store('images', 'public');
        if (!$path) return 0;

        return $this->add($path);
    }

    public function editImage($request, $id): ?int
    {
        // すでに存在する場合はIDを返す
        $imgId = $this->getImgId($id);
        if ($imgId > self::IMG_NOTHING) {
            return $imgId;
        }

        return $this->saveImage($request);
    }

    private function getImgId($articleId): ?int
    {
        $article = Article::query()->findOrFail($articleId);
        $img = $article->image;
        if ($img === null) return null;
        return $img->id;
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
