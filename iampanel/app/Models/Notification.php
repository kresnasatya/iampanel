<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Query unread notifications
     */
    public function queryUnread($user_groups, $user_id)
    {
        $groups_where = " ";
        $user_groups = flatten_groups($user_groups);
        if (!empty($user_groups)) {
            foreach ($user_groups as $key => $user_group) {
                if ($key) {
                    $groups_where .= " OR ";
                }
                $groups_where .= "find_in_set('".$user_group['name']."', target_group_id)";
            }
        } else {
            $groups_where = "1=1";
        }

        return self::whereRaw('('.$groups_where.')')
                ->whereRaw('DATE(NOW()) BETWEEN DATE(`published_at`) and DATE(`finished_at`)')
                ->where(function ($query1) use ($user_id) {
                    $query1->doesntHave('notification_user_actions', 'or', function ($query11) use ($user_id) {
                        $query11->where('user_id', $user_id);
                    });
                    $query1->orWhereHas('notification_user_actions', function ($query12) use ($user_id) {
                        $query12->whereNull('done_at');
                        $query12->where('user_id', $user_id);
                    });
                });
    }

    public function countUnread($user_groups, $user_id)
    {
        return $this->queryUnread($user_groups, $user_id)->get()->count();
    }

    public function notification_user_actions()
    {
        return $this->belongsTo('App\Models\NotificationUserAction', 'id', 'notification_id');
    }
}
