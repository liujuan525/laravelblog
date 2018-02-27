<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 回复与话题：一对一的对应关系
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 回复与用户：一对一的对应关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
