<?php

namespace App\Models;

use App\Traits\XenHelper;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
	use XenHelper;

	protected $fillable = [
		'project_id','project_status_id','title','description','tracker','priority','user_id','assignee_id','start_date','end_date','estimated_time_hours', 'time_log','work_done_percent', 'position', 'category', 'type', 'status'
	];

	public function project() {
		return $this->belongsTo('App\Models\Project', 'project_id');
	}

	public function projectStatus() {
		return $this->belongsTo('App\Models\ProjectStatus', 'project_status_id');
	}

	public function assignees() {
		return $this->belongsToMany('App\Models\User', 'issues_assignees', 'issue_id', 'assignee_id');
	}

	public function assignedBy() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

	public function comments() {
		return $this->hasMany('App\Models\IssueComment', 'issue_id');
	}

	public function timelogs() {
		return $this->hasMany('App\Models\IssueTimeLog', 'issue_id');
	}

	public function categoryName() {
		$category = [
			'now' => 'Get it done now',
			'need' => 'Needs to be completed',
			'free' => 'Whenever free',
			'rockstar' => 'You are a Rockstar'
		];

		return $category[$this->category];
	}
}
