<?php

namespace App\Http\Controllers\Project;

use App\Models\GanttTask;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GanttChartController extends Controller
{
	public function store(Request $request) {
		$task = new GanttTask();
		$task->text = $request->text;
		$task->start_date = $request->start_date;
		$task->duration = $request->duration;
		$task->project_id = $request->project_id;
		$task->progress = $request->has('progress') ? $request->progress : 0;
		$task->parent = $request->parent;
		$task->save();

		return response()->json([
			'action' => 'inserted',
			'tid' => $task->id
		]);
	}

	public function update($id, Request $request) {
		$task = GanttTask::findOrFail($id);
		$task->text = $request->text;
		$task->start_date = $request->start_date;
		$task->duration = $request->duration;
		$task->progress = $request->has('progress') ? $request->progress : 0;
		$task->parent = $request->parent;
		$task->save();

		return response()->json([
			'action' => 'updated',
		]);
	}

	public function destroy($id) {
		$task = GanttTask::findOrFail($id);
		$task->delete();

		return response()->json([
			'action' => 'deleted'
		]); 
	}
}
