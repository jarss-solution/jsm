<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $fillable = [
		'slug', 'company_id', 'title', 'logo', 'description', 'status', 'client_name', 'type', 'comments', 'budget_currency', 'budget', 'deadline', 'estimated_hours', 'process'
	];

	public function projectStatus() {
		return $this->hasMany('App\Models\ProjectStatus', 'project_id');
	}

	public function issues() {
		return $this->hasMany('App\Models\Issue', 'project_id');
	}

	public function files() {
		return $this->hasMany('App\Models\ProjectFile', 'project_id');
	}

	public function assignedUsers() {
		return $this->belongsToMany('App\Models\User', 'project_users', 'project_id', 'user_id');
	}

	public function deliverables() {
		return $this->hasMany('App\Models\ProjectDeliverable', 'project_id');
	}

	public function star() {
		return $this->belongsToMany('App\Models\User', 'project_user_stars', 'project_id', 'user_id');
	}

	public function timelogs() {
		return $this->hasMany('App\Models\IssueTimeLog', 'project_id');
	}
}
