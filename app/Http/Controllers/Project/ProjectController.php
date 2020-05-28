<?php

namespace App\Http\Controllers\Project;

use App\Models\Company;
use App\Models\Project;
use App\Models\Issue;
use App\Models\ProjectStatus;
use App\Models\ProjectFile;
use App\Models\ProjectDeliverable;
use App\Models\IssueTimeLog;
use App\Models\ProjectUserStar;
use App\Models\User;
use App\Models\GanttTask;
use App\Models\GanttLink;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    private $_notify_message = "Project saved.";
    private $_notify_type = "success";
    private $process_tech = [
        'initial_talks_tech' => 'Initial talks', 
        'contract_signed_tech' => 'Contract signed', 
        'initial_meeting_tech' => 'Initial meeting', 
        'wireframe_approved_tech' => 'Wireframe approved', 
        'concept_approved_tech' => 'Concept approved', 
        'design_round_1_tech' => 'Design round 1', 
        'design_round_2_tech' => 'Design round 2', 
        'design_round_3_tech' => 'Design round 3', 
        'design_round_4_tech' => 'Design round 4', 
        'design_round_5_tech' => 'Design round 5', 
        'sent_to_developers_tech' => 'Sent to developers', 
        'round_1_tech_demo_tech' => 'Round 1 tech demo', 
        'round_2_tech_demo_tech' => 'Round 2 tech demo', 
        'round_3_tech_demo_tech' => 'Round 3 tech demo', 
        'round_4_tech_demo_tech' => 'Round 4 tech demo', 
        'round_5_tech_demo_tech' => 'Round 5 tech demo', 
        'web_launch_tech' => 'Web launch', 
        'project_complete_tech' => 'Project complete',
    ];

    private $process_design = [
        'initial_talks_design' => 'Initial talks', 
        'contract_signed_design' => 'Contract signed', 
        'project_concept_status_design' => 'Project concept status', 
        'concept_approved_design' => 'Concept approved', 
        'design_round_1_status_design' => 'Design round 1 status', 
        'design_round_2_status_design' => 'Design round 2 status', 
        'design_round_3_status_design' => 'Design round 3 status', 
        'design_round_4_status_design' => 'Design round 4 status', 
        'design_round_5_status_design' => 'Design_round 5 status', 
        'design_finalized_design' => 'Design finalized', 
        'sent_to_printers_design' => 'Sent to printers', 
        'project_complete_design' => 'Project complete',
    ];

    private $process_amc = [
        'initial_talk_amc' => 'Initial talks', 
        'contracts_signed_amc' => 'Contract signed', 
        'update_fixes_amc' => 'Update/Fixes', 
        'project_complete_amc' => 'Project complete',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // PROJECTS QUERY =========================================
        $query = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('status', 1);

        if(request()->user()->role !== 1) {
            $query->whereHas('assignedUsers', function($q) {
                $q->where('user_id', request()->user()->id);
            });
        }
        $projects = $query->orderBy('star_count', 'desc')
            ->where('type', '!=', 'potential_lead')
            ->where('type', '!=', 'amc')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();
            // ->paginate(10, ['*'], 'projects');

        // dd($projects);
        $process_tech = $this->process_tech;
        $process_design = $this->process_design;

        // ARCHIVED PROJECTS QUERY =========================================
        $archived_query = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('status', 0);

        if(request()->user()->role !== 1) {
            $archived_query->whereHas('assignedUsers', function($q) {
                $q->where('user_id', request()->user()->id);
            });
        }
        $archived_projects = $archived_query->orderBy('star_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();
            // ->paginate(10, ['*'], 'archived');

        $project_leads = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('type', 'potential_lead')
        ->orderBy('created_at', 'desc')
        ->limit(1000)->get();

        $project_amc = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('type', 'amc')
        ->orderBy('created_at', 'desc')
        ->limit(1000)->get();

        $users = User::orderBy('name', 'asc')->where('status', 1)->pluck('name', 'id');

        return view('project.index', compact('projects', 'archived_projects', 'project_leads', 'project_amc', 'process_tech', 'process_design', 'users'));
    }

    public function search(Request $request) 
    {
        // PROJECTS =========================================================
        $query = Project::withCount(['issues' => function($query) {
            $query->where('status', 0);
        }])->with(['star', 'assignedUsers', 'timelogs'])->where('status', 1)->orderBy('created_at', 'desc');
        if($request->search) {
            $query->where('title', 'LIKE', '%'. $request->search .'%');
        }
        if($request->assigned_user) {
            $query->whereHas('assignedUsers', function($query) use ($request) {
                $query->where('user_id', $request->assigned_user);
            });
        }
        if($request->type) {
            $query->where('type', $request->type);
        }
        // $query->where('type', '!=', 'potential_lead')
        // $query->where('type', '!=', 'amc')
        $projects = $query->paginate(10, ['*'], 'projects');
        $process_tech = $this->process_tech;
        $process_design = $this->process_design;


        // ARCHIVED PROJECTS =================================================
        $archived_query = Project::withCount(['issues' => function($query) {
            $query->where('status', 0);
        }])->with(['star', 'assignedUsers', 'timelogs'])->orderBy('created_at', 'desc')->where('status', 0);
        if($request->search) {
            $archived_query->where('title', 'LIKE', '%'. $request->search .'%');
        }
        if($request->assigned_user) {
            $archived_query->whereHas('assignedUsers', function($query) use ($request) {
                $query->where('user_id', $request->assigned_user);
            });
        }
        if($request->type) {
            $archived_query->where('type', $request->type);
        }
        $archived_projects = $archived_query->paginate(10, ['*'], 'archived');

        $project_leads = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('type', 'potential_lead')
        ->orderBy('created_at', 'desc')
        ->limit(1000)->get();

        $project_amc = Project::withCount(['issues' => function($q) {
            $q->where('status', 0);
        }, 'star' => function($q) {
            $q->where('user_id', request()->user()->id);
        }])->with(['star', 'assignedUsers', 'timelogs'])
        ->where('type', 'amc')
        ->orderBy('created_at', 'desc')
        ->limit(1000)->get();

        $users = User::orderBy('name', 'asc')->where('status', 1)->pluck('name', 'id');

        return view('project.index', compact('projects', 'archived_projects', 'project_leads', 'project_amc', 'process_tech', 'process_design', 'users'));
    }

    public function category($category, Request $request) 
    {
        $projects = Project::withCount(['issues' => function($query) {
            $query->where('status', 0);
        }])
        ->where('status', 1)
        ->where('type', $category)
        ->orderBy('created_at', 'desc')
        ->paginate(11);
        $process_tech = $this->process_tech;
        $process_design = $this->process_design;

        $archived_projects = Project::withCount(['issues' => function($query) {
            $query->where('status', 0);
        }])
        ->where('status', 0)
        ->where('type', $category)
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('project.index', compact('projects', 'archived_projects', 'process_tech', 'process_design'));
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
        if($request->user()->role !== 1) {
            abort(500);
        }

        DB::beginTransaction();
        try {
            $request['company_id'] = 1;
            $data = $request->all();
            $data['deliverables'] = explode(',', $request->deliverables);
            $data['slug'] = str_slug($request->title);
            $data['process'] = $request->process ? serialize($request->process) : NULL;
            $find_project = Project::where('slug', $data['slug'])->first();
            if($find_project) {
                $this->_notify_message = "Project already exists with that name";
                $this->_notify_type = "danger";

                return redirect()->back()->with([
                    'notify_message' => $this->_notify_message,
                    'notify_type' => $this->_notify_type,
                ])->withInput($request->all());
            }
            
            // PROJECT STATUS ================================================================
            if($data['type'] == 'design') {
                $project_status = ['Initial Talks', 'Concept', 'Design', 'Printing', 'Complete'];
            } else if ($data['type'] == 'tech') {
                $project_status = ['Initial talks', 'Concept', 'Design', 'Programming', 'Bugs and fixes', 'On Hold', 'QA', 'Deployment', 'Complete'];
            } else if ($data['type'] == 'product') {
                $project_status = ['Concept', 'Design', 'Production', 'Complete'];
            } else if ($data['type'] == 'potential_lead') {
                $project_status = ['Initial talks', 'Contract sent', 'Project won', 'Project lost', 'Complete'];
            } else if ($data['type'] == 'web_hosting') {
                $project_status = ['Todo', 'Complete'];
            } else if ($data['type'] == 'amc') {
                $project_status = ['Initial talks', 'Contract signed', 'Update/Fixes', 'Project complete'];
            }

            // PROJECT CREATE ================================================================
            $project = Project::create($data);
            foreach($project_status as $projectstatus) {
                $ps = new ProjectStatus;
                $ps->project_id = $project->id;
                $ps->title = $projectstatus;
                $ps->save();
            }

            $project->assignedUsers()->sync($request->input('assigned_users', []));
            foreach($data['deliverables'] as $deliverable) {
                if($deliverable) {
                    $project_deliverable = new ProjectDeliverable;
                    $project_deliverable->project_id = $project->id;
                    $project_deliverable->title = $deliverable;
                    $project_deliverable->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $this->_notify_message = "Failed to save project, Try again.";
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
    public function show($project_id)
    {
        $project = Project::with('files')->where('slug', $project_id)->first();
        $project_statuses = ProjectStatus::with(['issues' => function($query) {
            $query->orderBy('position');
        }])->where('project_id', $project->id)->get();

        //GET TOTAL TIME LOG
        $total_hours_worked = IssueTimeLog::where('project_id', $project->id)->get()->sum('time_log');
        $time_log_hour = floor($total_hours_worked);
        $time_log_minute =  ($total_hours_worked - $time_log_hour) * 60;
        $time_log_minute =  round($time_log_minute);
        $total_time_logged = $time_log_hour . 'hr '. $time_log_minute . 'min';

        $project_process = $project->process ? unserialize($project->process) : [];

        $process_tech = $this->process_tech;
        $process_design = $this->process_design;

        return view('project.show', compact('project', 'project_statuses', 'project_process', 'process_tech', 'process_design', 'total_time_logged'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $project = Project::with('assignedUsers', 'deliverables')->where('slug', $slug)->first();
        $project_process = $project->process ? unserialize($project->process) : [];
        $process_tech = $this->process_tech;
        $process_design = $this->process_design;
        $users = User::orderBy('name', 'asc')->where('status', 1)->pluck('name', 'id');

        return view('project.edit', compact('project', 'project_process', 'process_tech', 'process_design', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $project = Project::where('slug', $slug)->first();
            if(isset($request['process'])) {
                if(in_array('project_complete_design', $request['process']) || in_array('project_complete_tech', $request['process'])) {
                    $request['status'] = 0;
                }
            }
            $request['process'] = $request->process ? serialize($request->process) : NULL;
            $request['deliverables'] = explode(',',$request->deliverables);
            $project->update($request->all());
            $project->assignedUsers()->sync($request->input('assigned_users', []));
        } catch (Exception $e) {
            $this->_notify_message = "Failed to update project, Try again.";
            $this->_notify_type = "danger";
        }
        
        return redirect()->route('project.index')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
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
            $project = Project::where('slug', $id)->first();
            $project->delete();

            $this->_notify_message = "Project deleted.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to delete project, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->route('project.index')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    public function updateProjectStatus($slug, Request $request) {
        try {
            $project = Project::where('slug', $slug)->first();
            if(in_array('project_complete_design', $request['process']) || in_array('project_complete_tech', $request['process'])) {
                $request['status'] = 0;
            }
            $request['process'] = $request->process ? serialize($request->process) : NULL;
            $project->update($request->all());
        } catch (Exception $e) {
            
        }

        return redirect()->back();
    }
    
    public function storeProjectFiles($id, Request $request) 
    {
        try {
            $project = Project::findOrFail($id);
            
            foreach($request->project_files as $project_file) {
                // Upload file
                $file = $project_file;
                $fileName = time() ."-". $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $fileName);

                $image = 'uploads/project/' . $id . '/' .$fileName;
                $upload_success = $file->move('uploads/project/' . $id, $fileName);
                
                // Save to database
                $db_project_file = new ProjectFile;
                $db_project_file->filename =  $image;
                $db_project_file->project_id =  $id;
                $db_project_file->save();
            }

            $this->_notify_message = "Files uploaded.";
        } catch (Exception $e) {
            $this->_notify_message = "Files upload failed, Try again.";
            $this->_notify_type = "danger";
        }
        
        return redirect(route('project.show', $project->slug) .'#additionalinfo')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    public function deleteProjectFiles($project_file_id) 
    {
        try {
            $project_file = ProjectFile::findOrFail($project_file_id);
            $project = Project::findOrFail($project_file->project_id);

            //Find and delete file
            if($project_file && is_file($project_file->filename)) {
                unlink($project_file->filename);
            }
            //Delete from database
            $project_file->delete();

            $this->_notify_message = "File deleted";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to delete file, Try again";
            $this->_notify_type = "danger"; 
        }

        return redirect(route('project.show', $project->slug) .'#additionalinfo')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    public function addProjectDeliverable($slug, Request $request) 
    {
        try {
            $project = Project::where('slug', $slug)->first();

            $project_deliverable = new ProjectDeliverable;
            $project_deliverable->project_id = $project->id;
            $project_deliverable->title = $request->title;
            $project_deliverable->save();

            $this->_notify_message = "Project deliverable added.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to add project deliverable, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect('/project/'. $project->slug . '#additionalinfo')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }

    public function updateProjectDeliverable($id) 
    {
        try {
            $project_deliverable = ProjectDeliverable::findOrFail($id);
            $project_deliverable->status = !$project_deliverable->status;
            $project_deliverable->save();
        } catch (Exception $e) {
            
        }

        return response()->json($project_deliverable);
    }

    public function deleteProjectDeliverable($id) 
    {
        try {
            ProjectDeliverable::destroy($id);

            $this->_notify_message = "Deliverable deleted.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to delete project deliverable, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type
        ]);
    }

    public function star($id, Request $request) {
        try {
            $project_user_stared = ProjectUserStar::where('project_id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if($project_user_stared) {
                $project_user_stared->delete();
                $status = 'deleted';
            } else {
                $project_user_stared = new ProjectUserStar;
                $project_user_stared->user_id = $request->user()->id;
                $project_user_stared->project_id = $id;
                $project_user_stared->save();
                $status = 'created';
            }
        } catch (Exception $e) {
            
        }

        return response()->json(['status' => $status]);
    }

    public function storeComments($id, Request $request) {
        try {
            $project = Project::findOrFail($id);
            $project->comments = $request->comments;
            $project->save();
            
            $this->_notify_message = "Comments stored.";
        } catch (Exception $e) {
            $this->_notify_message = "Failed to store comments, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    public function ganttChart($slug) {
        $project = Project::where('slug', $slug)->first();

        return view('project.gantt-chart', compact('project'));
    }

    public function ganttChartData($project) {
        $tasks = GanttTask::where('project_id', $project)->get();
        $links = GanttLink::all();

        return response()->json([
            'data' => $tasks,
            'links' => $links,
        ]);
    }
}
