<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    // 活跃用户的计算
    use Traits\ActiveUserHelper;

    // 记录用户最后一次登录时间
    use Traits\LastActivedAtHelper;

    // 获取到扩展包提供的所有权限和角色的操作方法
    use HasRoles;

    use Notifiable {
        notify as protected laravelNotify;
    }

    // 可批量插入的数据字段
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 用户与评论：一对多的对应关系
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 用户与话题为 一对多 的关系
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 是否授权
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    // 消息通知
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    // 清除未读消息提示
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    // 设置密码 - 对密码进行加密处理
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {
            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    // 设置头像的保存路径
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }



}
