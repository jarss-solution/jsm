<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationUser;

class NotificationController extends Controller
{
    public function markSeen($notification_id) {
    	$notification_user = NotificationUser::findOrFail($notification_id);
    	$notification_user->seen = 1;
    	$notification_user->save();

    	$navbar_notifications_unseen_count = NotificationUser::with('notification')
            ->where('user_id', request()->user()->id)
            ->where('seen', 0)
            ->count();

    	return $navbar_notifications_unseen_count;
    }

    public function markSeenAll() {
        $notification_user = NotificationUser::where('user_id', request()->user()->id)->where('seen', 0)
            ->update([
                'seen' => 1
            ]);

        return response()->json(0);
    }
}
