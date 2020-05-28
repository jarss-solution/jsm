<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueComment extends Model
{
	protected $guarded = [
		'created_at', 'updated_at'
	];

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
}
