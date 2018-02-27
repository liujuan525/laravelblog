<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
//         return $topic->user_id == $user->id;
        // 下面代码等同于上面的代码
        return $user->isAuthorOf($topic);
    }

    public function destroy(User $user, Topic $topic)
    {
//        return $topic->user_id == $user->id;
        return $user->isAuthorOf($topic);
    }
}
