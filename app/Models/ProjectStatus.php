<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    protected $fillable = [
    	'project_id', 'title'
    ];

    public function project() {
    	return $this->belongsTo('App\Models\Project', 'project_id');
    }

    public function issues() {
    	return $this->hasMany('App\Models\Issue', 'project_status_id');
    }
}
