<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
	protected $guarded = [];
	
	public function notification() {
		return $this->belongsTo('App\Models\Notification', 'notification_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
}
