<?php

// AUTHENTICATION ===============================
Route::get('/login', 'Auth\AuthController@getLogin')->name('auth.login');
Route::post('/login', 'Auth\AuthController@postLogin')->name('auth.login');
Route::get('/logout', 'Auth\AuthController@getLogout')->name('auth.logout');

Route::group(['middleware' => ['auth', 'init']], function() {
	Route::get('/', 'Home\HomeController@index')->name('home.index');
	Route::post('/noticeboard', 'Home\HomeController@noticeboard')->name('home.noticeboard');

	// PROJECT COMMENTS AND FILES ===============
	Route::get('/project/search', [
		'uses' => 'Project\ProjectController@search',
		'as' => 'project.search'
	]);
	Route::post('/project/{project}/store-project-files', [
		'uses' => 'Project\ProjectController@storeProjectFiles',
		'as' => 'project.store-project-files'
	]);
	Route::delete('/project/{projectfile}/delete-project-files', [
		'uses' => 'Project\ProjectController@deleteProjectFiles',
		'as' => 'project.delete-project-files'
	]);
	Route::patch('/project/{projectstatus}/update-project-status', [
		'uses' => 'Project\ProjectController@updateProjectStatus',
		'as' => 'project.update-project-status'
	]);

	// PROJECT DELIVERABLES =====================
	Route::post('/project/{project}/add-project-deliverable', [
		'uses' => 'Project\ProjectController@addProjectDeliverable',
		'as' => 'project.add-project-deliverable'
	]);
	Route::delete('/project/{projectdeliverable}/delete-project-deliverable', [
		'uses' => 'Project\ProjectController@deleteProjectDeliverable',
		'as' => 'project.delete-project-deliverable'
	]);
	Route::get('/project/{projectdeliverable}/update-project-deliverable', [
		'uses' => 'Project\ProjectController@updateProjectDeliverable',
		'as' => 'project.update-project-deliverable'
	]);

	Route::get('/project/{project}/star', [
		'uses' => 'Project\ProjectController@star',
		'as' => 'project.star'
 	]);
 	Route::post('/project/{project}/store-comments', [
 		'uses' => 'Project\ProjectController@storeComments',
 		'as' => 'project.store-comments'
 	]);
	Route::get('/project/{project}/gantt-chart', [
		'uses' => 'Project\ProjectController@ganttChart',
		'as' => 'project.gantt-chart'
	]);
	Route::get('/project/{project}/gantt-chart-data', [
		'uses' => 'Project\ProjectController@ganttChartData',
		'as' => 'project.gantt-chart-data'
	]);
	// PROJECT ==================================
	Route::resource('/project', 'Project\ProjectController');

	// ISSUE ====================================
	Route::post('/issue/{project}/sort', [
		'uses' => 'Project\IssueController@sort',
		'as' => 'issue.sort'
	]);
	Route::post('/issue/{issue}/store-timelog', [
		'uses' => 'Project\IssueController@storeTimelog',
		'as' => 'issue.store-timelog'
	]);
	Route::get('/task/{project}/project-status', [
		'uses' => 'Task\TaskController@getProjectStatus',
		'as' => 'task.project-status'
	]);
	Route::resource('/issue', 'Project\IssueController');
	Route::resource('/task', 'Task\TaskController');
	Route::resource('/task-comment', 'Task\TaskCommentController');

	// CALENDAR =================================
	Route::get('/calendar', [
		'uses' => 'Calendar\CalendarController@index',
		'as' => 'calendar.index'
	]);
	Route::resource('/asset', 'Asset\AssetController');
	Route::resource('/user', 'User\UserController');
	
	// TASK REPORT ==============================
	Route::get('/task-reports/search', [
		'uses' => 'Task\TaskReportController@search',
		'as' => 'task-reports.search'
	]);
	Route::resource('/task-reports', 'Task\TaskReportController');
	
	// DROPBOX ==================================
	Route::get('/dropbox', [
		'uses' => 'Dropbox\DropboxController@index',
		'as' => 'dropbox.index'
	]);
	Route::get('/dropbox/{file}/delete', [
		'uses' => 'Dropbox\DropboxController@delete',
		'as' => 'dropbox.delete'
	]);
	Route::post('/dropbox/upload', [
		'uses' => 'Dropbox\DropboxController@upload', 
		'as' => 'dropbox.upload'
	]);
	
	// NOTIFICATION =============================
	Route::get('/notification/{notification}/mark-seen', [
		'uses' => 'Notification\NotificationController@markSeen',
		'as' => 'notification.mark-seen'
	]);
	Route::get('/notification/mark-seen-all', [
		'uses' => 'Notification\NotificationController@markSeenAll',
		'as' => 'notification.mark-seen-all'
	]);
});

// Route::get('/archive-script', function() {
// 	$projects = App\Models\Project::all();
// 	foreach($projects as $project) {
// 		$process = unserialize($project->process);
// 		if(is_array($process)) {
// 			if(in_array('project_complete_design', $process) || in_array('project_complete_tech', $process)) {
// 	            $status = 0;
// 	        	$project->status = $status;
// 	        	$project->save();
// 	        }
// 		}
// 	}

// 	dd('done');
// });