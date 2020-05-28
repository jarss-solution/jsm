<?php

namespace App\Http\Controllers\Task;

use App\Models\User;
use App\Models\Issue;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Notification;
use App\Models\IssueTimeLog;

use Carbon\Carbon;
use DB;
use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    private $_notify_message = "Task saved.";
    private $_notify_type = "success";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $projects_query = Project::orderBy('title', 'asc');
        $projects_query->whereHas('assignedUsers', function($query) {
            $query->where('user_id', request()->user()->id);
        });
        $projects = $projects_query->pluck('title', 'id');

        $jarss_task_list = Issue::join('issues_assignees', 'issues_assignees.issue_id', '=', 'issues.id')
            ->with('assignedBy', 'project')
            ->select('issues.*', 'issues_assignees.issue_id', 'issues_assignees.assignee_id')
            ->where(function($q) {
                $q->where('issues_assignees.assignee_id', request()->user()->id);
                $q->where('issues.type', 'company');
                $q->where('issues.status', 0);
            })
            ->orWhere(function($q) {
                $q->where('issues_assignees.assignee_id', request()->user()->id);
                $q->where('issues.type', 'company');
                $q->whereDate('issues.updated_at', Carbon::today());
            })
            ->where('issues.status', 0)
            ->orderBy('issues.created_at', 'desc')
            ->limit(50)
            ->get();

        $assigned_to_others = Issue::orderBy('created_at', 'desc')
            ->with('assignees', 'assignedBy', 'project')
            ->whereHas('assignees', function($query) {
                $query->where('assignee_id', '!=', request()->user()->id);
            })
            ->where('user_id', request()->user()->id)
            ->limit(50)->get();

        $personal_task_list = Issue::join('issues_assignees', 'issues_assignees.issue_id', '=', 'issues.id')
            ->with('assignedBy', 'project')
            ->select('issues.*', 'issues_assignees.issue_id', 'issues_assignees.assignee_id')
            ->where('issues_assignees.assignee_id', request()->user()->id)
            ->where('issues.type', 'personal')
            ->orderBy('issues.created_at', 'desc')
            ->limit(50)
            ->get();

        $jarss_users_list = User::with(['issues' => function ($q) {
            $q->with('assignedBy', 'project');
            $q->where(function($sq) {
                $sq->where('issues.type', 'company');
                $sq->where('issues.status', 0);
            });
            $q->orWhere(function($sq) {
                $sq->where('issues.type', 'company');
                $sq->whereDate('issues.updated_at', Carbon::today());
            });

            $q->orderBy('issues.created_at', 'desc');
            $q->where('issues.status', 0);
            $q->limit(500);
        }])
        ->where('status', 1)
        ->orderBy('name')->get();

        $time_logs = IssueTimeLog::whereDate('created_at', date('Y-m-d'))
            ->groupBy('user_id')
            ->selectRaw('sum(time_log) as total_time, user_id')
            ->get()
            ->keyBy('user_id')
            ->toArray();

        return view('task.index', compact('users', 'projects', 'jarss_task_list', 'personal_task_list', 'time_logs', 'jarss_users_list', 'assigned_to_others'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
        $projects_query = Project::orderBy('title', 'asc');
        $projects_query->whereHas('assignedUsers', function($query) {
            $query->where('user_id', request()->user()->id);
        });
        $projects = $projects_query->pluck('title', 'id');

        return view('task.create', compact('projects', 'users'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request['user_id'] = $request->user()->id;
            if($request['project_id'] && $request['project_status_id']) {
                $project_status = ProjectStatus::findOrFail($request['project_status_id']);   

                if($project_status) {
                    $request['position'] = Issue::where('project_id', $request['project_id'])->where('project_status_id', $request['project_status_id'])->count();

                    foreach($request->title as $task_title) {
                        $data = $request->all();
                        unset($data['title']);
                        $data['title'] = $task_title;

                        $issue = Issue::create($data);
                        $issue->assignees()->sync($data['assigned_to']);
                    }
                } else {
                    abort(404);
                }
            } else {
                foreach($request->title as $task_title) {
                    $data = $request->all();
                    unset($data['title']);
                    $data['title'] = $task_title;
                    
                    $issue = Issue::create($data);
                    $issue->assignees()->sync($data['assigned_to']);
                }
            }

            // users to send notification to, removed user who created the task
            $notify_users = $request->input('assigned_to', []);
            $notify_users = array_diff( $notify_users, [$request->user()->id] );

            if(count($notify_users)) {            
                //send notification to assigned users
                $notification = new Notification;
                $notification->link = route('task.index', 'jarss');
                $notification->user_id = $request->user()->id;
                $notification->detail = $issue->title . '.  Assigned by '. $issue->assignedBy->name;
                $notification->save();

                $notification->users()->sync($notify_users);

                foreach($notify_users as $notify_user_id) {
                    $notify_user = User::findOrFail($notify_user_id);
                    $assigned_by = $request->user();
                    Mail::send('emails.task-notify', compact('issue', 'assigned_by'), function ($message) use ($notify_user) {
                        $message->subject('Task assigned to you.');
                        $message->from('noreply@jarsssolution.com', 'JARSS Solution');
                        $message->to($notify_user->email);
                    });
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            $this->_notify_message = "Failed to save task, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        DB::beginTransaction();
        try {
            // Update the issue
            $issue = Issue::findOrFail($id);
            $issue->status = $request->status == 'true' ? 1 : 0;
            $issue->category = $request->status == 'true' ? 'rockstar' : 'need';
            // if($issue->project_id && $issue->project_status_id) {
            //     if($request->status == 'true') {
            //         $project_status = ProjectStatus::where('project_id', $issue->project_id)
            //             ->where('title', 'Complete')
            //             ->first();
            //     } else {
            //         $project_status = ProjectStatus::findOrFail($issue->project_status_id);
            //     }
                
            //     $project_status_count = Issue::where('project_id', $issue->project_id)
            //         ->where('project_status_id', $project_status->id)
            //         ->count();

            //     if($project_status) {
            //         $issue->project_status_id = $project_status->id;
            //         $issue->position = $project_status_count;
            //     }
            // }
            $issue->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
        }

        return response()->json($request->status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $issue = Issue::with(['timelogs' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'comments'])->findOrFail($id);
        // $issue['time_log_hour'] = floor($issue->time_log);
        // $issue['time_log_minute'] =  ($issue->time_log - $issue['time_log_hour']) * 60;
        // $issue['time_log_minute'] =  round($issue['time_log_minute']);

        $users = User::orderBy('name', 'asc')->pluck('name', 'id');
        $projects = Project::orderBy('title', 'asc')->pluck('title', 'id');

        return view('task.edit', compact('issue', 'users', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $issue = Issue::findOrFail($id);
            $old_assignees = $issue->assignees->pluck('id')->toArray();
            $new_assignees = $request->input('assigned_to', []);
            $new_assignees = array_map( function($value) { return (int)$value; }, $new_assignees );

            // convert hours and minutes fields to one
            $request['time_log'] = $request->time_log_hour + ($request->time_log_minute / 60);
            
            if($request->project_id) {
                if((int)$request->project_id !== $issue->project_id) {
                    $new_project_status = ProjectStatus::where('project_id', $request->project_id)
                                                        // ->where('title', 'Complete')
                                                        ->first();
                    $request['project_status_id'] = $new_project_status->id;
                }
            }

            //save issue and sync assignees
            $issue->update($request->all());
            $issue->assignees()->sync($request->input('assigned_to', []));
            $have_new_assignees = array_diff($new_assignees, $old_assignees);
            
            //send notification if new assignee
            if($have_new_assignees) {
                $notification = new Notification;
                $notification->link = route('task.index', 'jarss');
                $notification->user_id = $request->user()->id;
                $notification->detail = $issue->title . '.  Assigned by '. $issue->assignedBy->name;
                $notification->save();

                $notification->users()->sync($have_new_assignees);
            }

            $this->_notify_message = "Task updated.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to update task, try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $issue = Issue::findOrFail($id);
            $issue->delete();

            $this->_notify_message = "Task deleted.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to delete task, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }

    public function getProjectStatus($project) {
        $project_status = ProjectStatus::where('project_id', $project)->pluck('title','id');

        return $project_status;
    }

}
