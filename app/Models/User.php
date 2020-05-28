<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function issues() {
        return $this->belongsToMany('App\Models\Issue', 'issues_assignees', 'assignee_id', 'issue_id');
    }

    public function profilePicture() {
        if(is_file('uploads/user/'. $this->image)) {
            return asset('uploads/user/'. $this->image);
        }
        return "https://via.placeholder.com/200?text=Image";
    }

    public function role() {
        if($this->role == 1) {
            return 'Admin';
        } else if ($this->role == 2)  {
            return 'Normal';
        }
    }

    public function projects() {
        return $this->belongsToMany('App\Models\Project', 'project_users', 'project_id', 'user_id');
    }
}
