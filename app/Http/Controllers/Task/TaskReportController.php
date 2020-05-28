<?php

namespace App\Http\Controllers\Task;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use App\Traits\XenHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskReportController extends Controller
{
    use XenHelper;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Issue::orderBy('created_at', 'desc');
        
        $total_hours = $this->timeConvert($query->sum('time_log'));
        $issues = $query->paginate(25);

        // Filter data
        $projects = Project::orderBy('title')->pluck('title', 'id');
        $users = User::where('status', 1)->orderBy('name')->pluck('name', 'id');

        return view('task-report.index', compact('issues', 'projects', 'users', 'total_hours'));
    }

    public function search(Request $request) {
        $query = Issue::orderBy('updated_at', 'desc');
        $query->join('issues_assignees', 'issues_assignees.issue_id', '=', 'issues.id');
        $query->select('issues.*', 'issues_assignees.issue_id', 'issues_assignees.assignee_id');
        
        if($request->project_id) 
            $query->where('issues.project_id', $request->project_id);
        if($request->status)
            $query->where('issues.status', $request->status);
        if($request->assigned_by)
            $query->where('issues.user_id', $request->assigned_by);
        if($request->assigned_to)
            $query->where('issues_assignees.assignee_id', request()->assigned_to);
        if($request->from_date)
            $query->whereDate('issues.updated_at', '>=', $request->from_date);
        if($request->to_date)
            $query->whereDate('issues.updated_at', '<=', $request->to_date);

        $total_hours = $query->sum('time_log');
        $issues = $query->paginate(25);

        // Filter data
        $projects = Project::orderBy('title')->pluck('title', 'id');
        $users = User::where('status', 1)->orderBy('name')->pluck('name', 'id');

        return view('task-report.index', compact('issues', 'projects', 'users', 'total_hours'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
