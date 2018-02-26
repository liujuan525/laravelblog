<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 批量插入数据的字段
    protected $fillable = [
        'name', 'description',
    ];
}
