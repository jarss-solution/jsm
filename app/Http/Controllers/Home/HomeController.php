<?php

namespace App\Http\Controllers\Home;

use App\Models\Weather;
use App\Models\Issue;
use App\Models\Noticeboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function index() {
		$weather = Weather::orderBy('created_at', 'desc')->first();
		// $weather['data'] = unserialize($weather->daily_data);
        $noticeboard = Noticeboard::first();

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
            ->orderBy('issues.created_at', 'desc')
            ->limit(50)
            ->get();

		return view('home.index', compact('weather', 'noticeboard', 'jarss_task_list'));
	}

    public function noticeboard(Request $request) {
        try {
            $noticeboard = Noticeboard::first();
            $noticeboard->content = $request->content;
            $noticeboard->save();
        } catch (Exception $e) {
        }

        return redirect()->back()->with([
            'notify_message' => 'Noticeboard updated.',
            'notify_type' => 'success'
        ]);
    }
}
