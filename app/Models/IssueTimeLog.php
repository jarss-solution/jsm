<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueTimeLog extends Model
{
	public function issue() {
		return $this->belongsTo('App\Models\Issue', 'issue_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	
	public function project() {
		return $this->belongsTo('App\Models\Project', 'project_id');
	}

	public function timelog() {
		$time_log_hour = floor($this->time_log);
        $time_log_minute =  ($this->time_log - $time_log_hour) * 60;
        $time_log_minute =  round($time_log_minute);
		
		return $time_log_hour.'hr '.$time_log_minute.'min';
	}
}
