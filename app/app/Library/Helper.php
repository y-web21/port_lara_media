<?php

namespace App\Library;

use App\Consts\Navigation;

class Helper
{
    public function __construct()
    {
    }

    /**
     * 文字列を指定の長さまでに制限してエリプシスをつけて返します。
     * エリプシスの長さは制限に含まれません。
     *
     * @param string $value 対象文字列
     * @param integer $limit optional 表示文字数制限の指定
     * @param string $end optional エリプシス文字列の指定
     * @return void
     */
    public static function strLimit(string $value, int $limit = 100, string $end = '...')
    {
        if (mb_strlen($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
    }

    /**
     * @param model $model
     * @return array<int, string>
     */
    public static function getTableColumnName($model)
    {
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        return $columns;
    }

    /**
     * 相対URL(ベースURLなし)から最初の文字列を返します
     * 例えば、 first/second/third では、 first が返ります。
     * スラッシュを含みません。
     *
     * @param string $rel
     * @return string
     */
    public static function getUrlCategory(string $rel){
        if ($rel === '' ) {
            return Navigation::HOME;
        };
        if (!strpos('/', $rel)) {
            return $rel;
        };
        return explode('/', $rel)[0];
    }
}
