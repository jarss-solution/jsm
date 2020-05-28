@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10" style="padding-top: 3px !important;">
			<h5 style="color: #85858e;">Users can create your own or assign tasks to other team members based on their project involvement. An admin can view the workload for each employee for better decision making. <a href="{{ route('task-reports.index') }}" style="text-decoration: underline; font-weight: bold; color: #f35958;">Task Reports</a></h5>
			<div class="clearfix"></div>
			<div class="sideBySide row">
				<div class="timelog-cat col-md-4">
					<div class="card task-card">
						<div class="card-header">
							JARSS Task List &nbsp; 

							<span style="font-size: 10px;">
							Todays time log - 
							@if(isset($time_logs[Auth::user()->id]))
								@php
									$time_log = $time_logs[Auth::user()->id]['total_time'];
									
									$time_log_hour = floor($time_log);
							        $time_log_minute =  ($time_log - $time_log_hour) * 60;
							        $time_log_minute =  round($time_log_minute);
									
									echo $time_log_hour.'hr '.$time_log_minute.'min';
								@endphp
							@else
								0 hr
							@endif
							</span>

							<button class="add-kazi-task-btn add-task-btn" data-task-user-id="{{ Auth::user()->id }}"><i class="fa fa-plus"></i></button>
						</div>
						<div class="card-body task-card-body">
							<ul class="list-group">
								@foreach($jarss_task_list as $jarss_task)
								<li class="list-group-item">
									<div class="task-status-color task-status-{{ $jarss_task->category }}">
										{{ $jarss_task->categoryName() }}
									</div>
									<span class="task-status">
										<label class="checkbox-container">
											<input type="checkbox" data-issue-id="{{ $jarss_task->id }}" class="task_status" {{ $jarss_task->status ? ' checked' : '' }} value="1">
											<span class="checkmark"></span>
										</label>
									</span>
									<span class="task-name" data-task-id="{{ $jarss_task->id }}" data-toggle="modal" data-target="#exampleModalCenter">
										{{ $jarss_task->title }}

										@if($jarss_task->project)
											<small style="font-size: 10px;color: #666666bd;">
												 {{ $jarss_task->project->title }}
											</small>
										@endif
									</span>
									<span class="task-buttons">
										<div class="task-assigned-by-and-destroy-btn">
											<span class="thumbnail-wrapper task-assigned-by-user circular">
												<img src="{{ $jarss_task->assignedBy->profilePicture() }}" title="Assigned by - {{ $jarss_task->assignedBy->name }}" class="img img-fluid">
											</span>
											{{ html()->form('DELETE', route('task.destroy', [$jarss_task->id]))->class('task-delete-form')->open() }}
											<button class="task-destroy-btn" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
											{{ html()->form()->close() }}
										</div>
									</span>
								</li>
								@endforeach
							</ul>
						</div>
					</div> <!-- card -->
				</div>
				<div class="timelog-cat col-md-4">
					<div class="card task-card">
						<div class="card-header">
							Personal Task List
							<button class="add-personal-task-btn add-task-btn"><i class="fa fa-plus"></i></button>
						</div>
						<div class="card-body task-card-body">
							<ul class="list-group">
								@foreach($personal_task_list as $personal_task)
								<li class="list-group-item">
									<div class="task-status-color task-status-{{ $personal_task->category }}">
										{{ $personal_task->categoryName() }}
									</div>
									<span class="task-status">
										<label class="checkbox-container">
											<input type="checkbox" data-issue-id="{{ $personal_task->id }}" class="task_status" {{ $personal_task->status ? ' checked' : '' }}>
											<span class="checkmark"></span>
										</label>
									</span>
									<span class="task-name" data-task-id="{{ $personal_task->id }}" data-toggle="modal" data-target="#exampleModalCenter">{{ $personal_task->title }} <small style="font-size: 10px;color: #666666bd;"> {{ $personal_task->project ? $personal_task->project->title : '' }}</small></span></span>
									<span class="task-buttons">
										{{ html()->form('DELETE', route('task.destroy', [$personal_task->id]))->class('task-delete-form')->open() }}
										<button class="task-destroy-btn" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
										{{ html()->form()->close() }}
									</span>
								</li>
								@endforeach
							</ul>
						</div>
					</div> <!-- card -->
				</div>
				<div class="timelog-cat col-md-4">
					<div class="card task-card">
						<div class="card-header">
							Assigned to others
						</div>
						<div class="card-body task-card-body">
							<ul class="list-group">
								@foreach($assigned_to_others as $assigned_to_other)
								<li class="list-group-item">
									<div class="task-status-color task-status-{{ $assigned_to_other->category }}">
										{{ $assigned_to_other->categoryName() }}
									</div>
									<span class="task-status">
										<label class="checkbox-container">
											<input type="checkbox" data-issue-id="{{ $assigned_to_other->id }}" class="task_status" {{ $assigned_to_other->status ? ' checked' : '' }}>
											<span class="checkmark"></span>
										</label>
									</span>
									<span class="task-name" data-task-id="{{ $assigned_to_other->id }}" data-toggle="modal" data-target="#exampleModalCenter">{{ $assigned_to_other->title }} 
										<small style="font-size: 10px;color: #666666bd;"> 
											{{ $assigned_to_other->project ? $assigned_to_other->project->title : '' }}
										</small>
									</span>
									<span class="task-buttons">
										<div class="task-assigned-by-and-destroy-btn">
											<span class="thumbnail-wrapper task-assigned-by-user" style="width: auto;">
												@foreach($assigned_to_other->assignees as $other_assignee)
												<span class="thumbnail-wrapper task-assigned-to-user circular" style="float: left;">
													<img src="{{ $other_assignee->profilePicture() }}" title="{{ $other_assignee->name }}" class="img img-fluid">
												</span>
												@endforeach
											</span>

											{{ html()->form('DELETE', route('task.destroy', [$assigned_to_other->id]))->class('task-delete-form')->open() }}
											<button class="task-destroy-btn" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
											{{ html()->form()->close() }}
										</div>
									</span>
								</li>
								@endforeach
							</ul>
						</div>
					</div> <!-- card -->
				</div>
			</div>

			@if(request()->user()->role == 1)
			<h5>Employees Task List</h5>
			<div class="sideBySide row">
				@foreach($jarss_users_list as $kazi_user_list)
				<div class="timelog-cat col-md-4">
					<div class="card task-card">
						<div class="card-header">
							<a href="{{ route('user.show', $kazi_user_list->id) }}" style="color: #fff !important;opacity: 1;">
								{{ $kazi_user_list->name }}
							</a>
							&nbsp;
							<span style="font-size: 10px;">
							Todays time log - 
							@if(isset($time_logs[$kazi_user_list->id]))
								@php
									$time_log = $time_logs[$kazi_user_list->id]['total_time'];
									
									$time_log_hour = floor($time_log);
							        $time_log_minute =  ($time_log - $time_log_hour) * 60;
							        $time_log_minute =  round($time_log_minute);
									
									echo $time_log_hour.'hr '.$time_log_minute.'min';
								@endphp
							@else
								0 hr
							@endif
							</span>
							<button class="add-kazi-task-btn add-task-btn" data-task-user-id="{{ $kazi_user_list->id }}"><i class="fa fa-plus"></i></button>
						</div>
						<div class="card-body task-card-body">
							<ul class="list-group">
								@foreach($kazi_user_list->issues as $kazi_task)
								<li class="list-group-item">
									<div class="task-status-color task-status-{{ $kazi_task->category }}">
										{{ $kazi_task->categoryName() }}
									</div>

									<span class="task-status">
										<label class="checkbox-container">
											<input type="checkbox" disabled {{ $kazi_task->status ? ' checked' : '' }}>
											<span class="checkmark"></span>
										</label>
									</span>
									<span class="task-name" data-task-id="{{ $kazi_task->id }}" data-toggle="modal" data-target="#exampleModalCenter">
										{{ $kazi_task->title }} 
										@if($kazi_task->project)
											<small style="font-size: 10px;color: #666666bd;">
												 {{ $kazi_task->project->title }}
											</small>
										@endif
									</span>
									<span class="task-buttons">
										<div class="task-assigned-by-and-destroy-btn">
											<span class="thumbnail-wrapper task-assigned-by-user circular">
												<img src="{{ $kazi_task->assignedBy->profilePicture() }}" title="Assigned by - {{ $kazi_task->assignedBy->name }}" class="img img-fluid">
											</span>
										</div>
									</span>
								</li>
								@endforeach
							</ul>
						</div>
					</div> <!-- card -->
				</div>
				@endforeach
			</div>
			@endif
		</div> <!-- container -->

		<div class="quickview-wrapper calendar-event" id="kazi-task-quickview" style="width: 800px;right: -800px;">
			<div class="view-port clearfix" id="eventFormController">
				<div class="view bg-white">
					<div class="scrollable">
						<div class="p-l-20 p-r-20 p-t-20">
							<a class="pg-close text-master link pull-right" data-toggle="quickview" data-toggle-element="#kazi-task-quickview" href="#"></a>
							<h4 id="event-date">Add Kazi Task</h4>
						</div>
						<div id="create_task_form_loading" style="text-align: center; margin:50% auto;">
							Loading ...
						</div>
						<div id="create_task_form"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="quickview-wrapper calendar-event" id="personal-task-quickview" style="width: 800px;right: -800px;">
			<div class="view-port clearfix" id="eventFormController">
				<div class="view bg-white">
					<div class="scrollable">
						<div class="p-l-20 p-r-20 p-t-20">
							<a class="pg-close text-master link pull-right" data-toggle="quickview" data-toggle-element="#personal-task-quickview" href="#"></a>
							<h4 id="event-date">Add Personal Task</h4>
						</div>
						<form action="{{ route('task.store') }}" method="post">
							@csrf
							<input type="hidden" name="type" value="personal">

							<div class="p-l-20 p-r-20 p-t-30">
								<div class="form-group">
									<label>Task</label>
									<span class="text-danger">*</span>
									<input type="text" name="title" class="form-control" placeholder="Task" required>
								</div>

								<div class="form-group">
									<label>Category</label>
									<span class="text-danger">*</span>
									<select name="category" class="form-control" required>
										<option value="">Select category</option>
										<option value="now">Get it done now</option>
										<option value="need">Needs to be completed</option>
										<option value="free">Whenever free</option>
										<option value="rockstar">You are a Rockstar</option>
									</select>
								</div>

								<input type="hidden" name="assigned_to[]" value="{{ request()->user()->id }}">

								{{-- <div class="form-group">
									<label>Project</label>
									{{ 
										html()
										->select('project_id', $projects)
										->class('form-control')
										->placeholder('Select project') 
									}}
								</div> --}}

								<button id="eventSave" class="btn btn-warning btn-cons btn-black"><i class="fa fa-plus"></i> Add</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- content -->
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body task-edit-form p-b-0 p-r-0">

			</div>
		</div>
	</div>
</div>
@endsection


@push('styles')
<link href="{{ asset('dist/assets/custom/chosen/chosen.min.css') }}" rel="stylesheet" />
<style>
	@media (min-width: 768px) {
		.modal-dialog.modal-dialog-centered {
			max-width: 100% !important;
			width: 70% !important;
		}
	}
	.update-task-col {
		border-right: 1px solid #dcdcdc;
	}
	.btn-comment {
		background-color: #f57a79;
		border-color: #f57a79;
		color: #ffffff;
	}
	.task-name {
		cursor: pointer;
	}
	.modal .modal-header {
		padding: 25px;
	}
	.social-card.share.share-other .card-header {
		border-top: none;
		border-bottom: 1px solid #6d5dac38;
	}
	.task-name {
		width: 65%;
		display: inline-block;
	}
	.task-status {
		display: inline-block;
		vertical-align: top;
		margin-top: 4px;
	}
</style>
@endpush

@push('scripts')

<script>
	$('body').on('change', '.task-project-select', function() {
		$(".task-project-status-select").html("<option>Loading ... </option>");

		if($(this).val()) {
			// populate project status
			$.ajax({
				type: 'get',
				url: '/task/'+ $(this).val() + '/project-status',
				success: function (res) {
					$(".task-project-status-select").empty();
					$.each(res, function(key,item){
						// Create and append the new options into the select list
						$(".task-project-status-select").append("<option value="+key+">"+item+"</option>");
					});
				}
			})
		} else {
			$(".task-project-status-select").empty();
			$(".task-project-status-select").append("<option value='' selected='selected'>Select project status</option>");
		}		
	});

	$('.add-kazi-task-btn').on('click', function() {
		$('#kazi-task-quickview').addClass('open');
		var task_user_id = $(this).data('task-user-id');

		$('#create_task_form').empty();
		$('#create_task_form_loading').show();
		$.ajax({
			type: 'get',
			url: '/task/create',
			success: function(res) {
				$('#create_task_form').html(res);
				$('#create_task_form_loading').hide();

				// user select box
				$('.task-assigned-to').val(task_user_id);
				$(".chosen-select").chosen({});
				$(".task-assigned-to").trigger("chosen:updated");
			}
		});
	});

	$('.add-personal-task-btn').on('click', function() {
		$('#personal-task-quickview').addClass('open');
	});

	$('.task_status').on('change', function() {
		var issue_id = $(this).data('issue-id');
		var $this = $(this);
		
		$.ajax({
			type: 'get',
			url: '/task/' + issue_id,
			data: { status: $(this).is(":checked") },
			success: function(res) {
				setTimeout(function() {
					if (res = true && $this.is(":checked") == true) {
						$this.closest('.list-group-item').remove();
					}
				}, 5000);
			}
		})
	})

	$('.task-name').on('click', function() {
		var task_id = $(this).data('task-id');
		
		$('.task-edit-form').html('Loading ...');
		
		$.ajax({
			type: 'get',
			url: '/task/' + task_id + '/edit',
			success: function(res) {
				$('.task-edit-form').html(res);
			},
			error : function(res) {
				$('.task-edit-form').html('Error, Try again.');
			}
		})
	})

	$('.add-more-task-title').on('click', function() {
		$('.form-task-title').last().clone()
		.find("input:text").val("").end()
		.appendTo('.form-task-title-group');
	})
</script>
@endpush