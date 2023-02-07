<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
    use HasFactory;

    # created_at, updated_at フィールドを持たない
    public $timestamps = false;

}
