<?php

namespace App\Http\Controllers\Project;

use App\Models\Issue;
use App\Models\ProjectStatus;
use App\Models\IssueTimeLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class IssueController extends Controller
{
    private $_notify_message = "";
    private $_notify_type = "success";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        DB::beginTransaction();
        try {
            $project_status = ProjectStatus::where('title', $request->project_status_slug)->where('project_id', $request->project_id)->first();
            $data = $request->all();
            $data['user_id'] = $request->user()->id;
            $data['type'] = 'company';
            $data['category'] = ($request->project_status_slug == 'Done') ? 'rockstar' : 'need';;
            $data['status'] = ($request->project_status_slug == 'Done') ? 1 : 0;
            $data['project_status_id'] = $project_status->id;
            $data['position'] = Issue::where('project_status_id', $project_status->id)->count();

            //create issue
            $issue = Issue::create($data);
            $issue->assignees()->sync([$request->user()->id]);

            // store time log
            if($request->time_log_minute !== "0" || $request->time_log_hour !== "0") {
                $timelog = $request->time_log_hour + ($request->time_log_minute / 60);
                $issue_timelog = new IssueTimeLog;
                $issue_timelog->issue_id = $issue->id;
                $issue_timelog->user_id = $request->user()->id;
                $issue_timelog->time_log = $timelog;
                if($issue->project) {
                    $issue_timelog->project_id = $issue->project_id;
                }
                $issue_timelog->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        return response()->json(['status' => 200, 'issue' => $issue]);
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

    public function sort($project_id, Request $request) {
        $positions = $request->order;

        DB::beginTransaction();
        try {
            $project_status = ProjectStatus::where('project_id', $project_id)->where('title', $request->status)->first();

            foreach($positions as $key => $position) {
                $issue = Issue::findOrFail($position);
                $issue->position = $key;
                $issue->project_status_id = $project_status->id;
                $issue->status = ($project_status->title == 'Done') ? 1 : 0;
                $issue->category = ($project_status->title == 'Done') ? 'rockstar' : 'need';
                $issue->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        return response()->json(true);
    }

    public function storeTimelog($id, Request $request) {
        try {
            $issue = Issue::findOrFail($id);
            if($request->time_log_minute !== "0" || $request->time_log_hour !== "0") {
                $timelog = $request->time_log_hour + ($request->time_log_minute / 60);
                $issue_timelog = new IssueTimeLog;
                $issue_timelog->issue_id = $id;
                $issue_timelog->user_id = $request->user()->id;
                $issue_timelog->time_log = $timelog;
                if($issue->project) {
                    $issue_timelog->project_id = $issue->project_id;
                }
                $issue_timelog->save();
                
                $this->_notify_message = "Time logged";
            } else {
                $this->_notify_message = "Time log can't be 0";
                $this->_notify_type = "danger";
            }
        } catch (Exception $e) {
            $this->_notify_message = "Failed to save time log, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }
}
