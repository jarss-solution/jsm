@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="jumbotron" data-pages="parallax" style="overflow: inherit;">
			<div class="container-fluid sm-p-l-0 sm-p-r-0">
				<div class="inner" style="transform: translateY(0px); opacity: 1;">
					<div class="float-left">
					<h4 style="font-weight: 400; margin-bottom: 0;">{{ $project->title }}</h4>
					<h6 style="font-size: 14px; margin-top: 2px;">{{ $project->client_name }}</h6>
					</div>

					<div class="float-right">
						<div class="breadcrumb-list process-status-button">
							@php
								$project_process = unserialize($project->process) ?: [];
								$last_process = end($project_process) ?: 'New';
							@endphp
							
							<div class="pull-left">
								<div style="font-size: 12px;">Project Status</div>
								<div>{{ ucfirst(str_replace('_', ' ', $last_process)) }}</div>
							</div>
							<i class="fa fa-caret-down pull-right" style="margin-top: 13px;"></i>
						</div>

						<div class="process-status-list">
							{{ html()->modelForm($project, 'PATCH', route('project.update-project-status', [$project->slug]))->open() }}
                            <div class="form-group">
                                @if($project->type == 'tech')
                                <div class="process-prepopulate-checklist-tech">
                                        @foreach($process_tech as $key => $processTech)
                                        <div class="checkbox check-primary">
                                                <input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_tech_{{ $key }}" class="process-checkbox" {{ in_array($key, $project_process) ? 'checked' : '' }}>
                                                <label for="checkbox_tech_{{ $key }}">{{ $processTech }}</label>
                                        </div>
                                        @endforeach
                                </div>
                                @elseif($project->type == 'design')
                                <div class="process-prepopulate-checklist-design">
                                        @foreach($process_design as $key => $processDesign)
                                        <div class="checkbox check-primary">
                                                <input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_design_{{ $key }}" class="process-checkbox" {{ in_array($key, $project_process) ? 'checked' : '' }}>
                                                <label for="checkbox_design_{{ $key }}">{{ $processDesign }}</label>
                                        </div>
                                        @endforeach
                                </div>
                                @endif
                            </div>
                            <button class="btn btn-primary m-t-10" type="submit">Save</button>
                            {{ html()->closeModelForm() }}
						</div>
					</div>
					<div class="float-right">
						<div class="breadcrumb-list total-hours-status" style="width: 150px;">
							<div style="font-size: 12px;">Total hrs</div>
							<div>{{ $total_time_logged }}</div>
						</div>
					</div>
					<div class="float-right">
						<div class="breadcrumb-list total-hours-status" style="width: 150px;">
							<div style="font-size: 12px;">Deadline Date</div>
							<div>{{ $project->deadline ?: '--' }}</div>
						</div>
					</div>
					@if(request()->user()->role == 1)
					<div class="float-right">
						<div class="breadcrumb-list total-hours-status" style="width: 150px;">
							<div style="font-size: 12px;">Budget</div>
							<div>{{ $project->budget ? $project->budget_currency . ' ' . $project->budget : '--' }}</div>
						</div>
					</div>
					@endif
					<div class="clearfix"></div>
				</div>
			</div>
		</div>

		<div class="container-fluid sm-padding-10">
			<div class="sideBySideWrapper">
			<div class="sideBySide sideBySideProject row">
				@foreach($project_statuses as $project_status_key => $project_status)
				<div class="timelog-cat">
					<div class="card">
						<div class="card-header">
							{{ $project_status->title }}
						</div>
						<div class="card-body">
							<ul class="source connected" data-status="{{ $project_status->title }}">
								@foreach($project_status->issues as $issue_key => $issue)
								<li id="{{ $issue->id }}" data-id="{{$issue->id}}" data-position="{{ $issue->position }}" data-toggle="modal" data-target="#taskDetailViewModal" class="task-name">
									{{ $issue->title }}

									@foreach($issue->assignees as $issue_assignee)
									@if($loop->first)
									-
									@endif

									<small class="text-task-assignee">{{ $issue_assignee->name }}</small>
									@endforeach
								</li>
								@endforeach
							</ul>
							<form action="{{ route('issue.store') }}" method="post" class="add-issue-form">
								<div class="add-issue row">
									@csrf
									<div class="col-md-9 no-padding-right" style="padding-left: 5px;">
										<div class="form-group">
											<input name="title" type="text" class="form-control add-issue-form-title" placeholder="Create new issue" required>
										</div>
										<div class="row m-b-10">
											<div class="col-md-6">
												<div class="input-group">
													<input type="number" name="time_log_hour" class="form-control add-issue-form-timelog-hours decimal" placeholder="Hours" min="0" value="0" required>
													<div class="input-group-append">
														<span class="input-group-text default">hrs</span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="input-group">
													<input type="number" name="time_log_minute" class="form-control add-issue-form-timelog-minutes decimal" placeholder="Minutes" max="60" min="0" value="0" required>
													<div class="input-group-append">
														<span class="input-group-text default">mins</span>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-3 no-padding-left" style="padding-right: 5px;">
										<button class="btn btn-black btn-primary add-issue-btn" style="height: 89%;">Add</button>
									</div>
								</div>

								<input type="hidden" name="project_id" value="{{ $project->id }}">
								<input type="hidden" name="project_status_slug" value="{{ $project_status->title }}">
							</form>
						</div>
					</div> <!-- card -->
				</div> <!-- col -->
				@endforeach
			</div>
			</div>
			
			<div class="card" id="additionalinfo">
				<div class="card-body">
					<h5>Additional Info <a href="{{ route('project.gantt-chart', $project->slug) }}" class="btn btn-black text-white pull-right">View Gantt chart</a></h5>
					<div class="row">
						<div class="col-md-4">
							<h6>Project Comments</h6>
							{{ html()->modelForm($project, 'POST', route('project.store-comments', $project->id))->open() }}					
							<div class="form-group">
								{{ html()->textarea('comments')->class('form-control')->style(['height' => '433px'])->placeholder('Project comments') }}
							</div>

							<button type="submit" class="btn btn-success btn-black m-t-10">Save</button>
							{{ html()->closeModelForm() }}
						</div>

						<div class="col-md-4">
							<h6>Project Deliverables</h6>
							<div class="project-processlist-group">
								{{ html()->form('POST', route('project.add-project-deliverable', [$project->slug]))->class('m-t-10')->open() }}
									<div class="row">
										<div class="col-md-9">
											<div class="form-group">
												{{ html()->text('title')->class('form-control')->placeholder('Deliverable')->required() }}
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<button class="btn form-control btn-black text-white" type="submit">Add</button>
											</div>
										</div>
									</div>
								{{ html()->form()->close() }}

								<ul class="list-group">
								@foreach($project->deliverables as $project_deliverable)
									<li class="list-group-item">
										<input type="checkbox" name="status" style="position: relative;top: 2px;margin-right: 5px;" class="deliverable-status" data-deliverable-id="{{ $project_deliverable->id }}" {{ $project_deliverable->status ? ' checked' : '' }}>

										{{ $project_deliverable->title }}
										
										{{ html()->form('DELETE', route('project.delete-project-deliverable', $project_deliverable->id))->class('float-right')->open() }}
											<button class="btn btn-xs btn-black text-white" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
										{{ html()->form()->close() }}
									</li>
								@endforeach
								</ul>
							</div>						
						</div>
						@if(request()->user()->role == 1)
						<div class="col-md-4">
							<h6>Project Files</h6>
							<div class="project-processlist-group">
								{{ html()->form('POST', route('project.store-project-files', $project->id))->acceptsFiles()->class('m-t-10')->open() }}
								<div class="row">
									<div class="col-md-9">
										<div class="form-group">
											<input name="project_files[]" type="file" class="form-control" style="padding: 5px;" multiple>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<button type="submit" class="btn form-control btn-black text-white">Upload</button>
										</div>
									</div>
								</div>
								{{ html()->form()->close() }}
							<div>
								<ul class="list-group">
									@foreach($project->files as $project_file)
									<li class="list-group-item">
										<span class="oneliner" style="width: 70%;display: block; float: left;">{{ substr(basename($project_file->filename), 11) }}</span>

										<span class="float-right">
											<a href="/{{ $project_file->filename }}" class="text-black m-r-10" style="font-size: 12px;" title="View" target="_blank">
												<i class="fa fa-eye"></i>
											</a>
											
											<a href="/{{ $project_file->filename }}" class="text-black m-r-10" style="font-size: 12px;" title="Download" download>
												<i class="fa fa-download"></i>
											</a>
											
											{{ html()->form('DELETE', route('project.delete-project-files', $project_file->id))->style('float: right;')->open() }}
												<button type="submit" style="background: none; border: none; font-size: 12px; cursor: pointer;" onclick="return confirm('Are you sure?')">
												<i class="fa fa-trash"></i>
												</button>
											{{ html()->form()->close() }}

										</span>
									</li>
									@endforeach
								</ul>
							</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="taskDetailViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
<link href="{{ asset('dist/assets/plugins/summernote/css/summernote.css')}}" rel="stylesheet" type="text/css" media="screen">
<style>
	@media (min-width: 768px) {
		.modal-dialog.modal-dialog-centered {
			max-width: 100% !important;
			width: 70% !important;
		}
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('dist/assets/plugins/summernote/js/summernote.min.js')}}" type="text/javascript"></script>
<script>
	$('.process-status-button').on('click', function() {
		$('.process-status-list').toggle();
	});

	$('#summernote').summernote({
		height:300,
		onfocus:function(e) { $('body').addClass('overlay-disabled'); },
		onblur:function(e){ $('body').removeClass('overlay-disabled'); }
	});

	$('.project-processlist').on('click', function() {
		$('#calendar-event').addClass('open');
	});

	$('.deliverable-status').on('change', function() {
		var deliverable_id = $(this).data('deliverable-id');

		$.ajax({
			type: 'get',
			url: '/project/'+ deliverable_id +'/update-project-deliverable',
			success: function (res) {
				console.log(res);
			}
		})
	});
	
	var token = "{{ csrf_token() }}";
	var project_id = "{{ $project->id }}";
	$(function () {
		$(".source").sortable({
			connectWith: ".connected",
			change: function(event, ui) {
				//Update item id with new status 
				var status = $(this).attr('data-status'); //get new status
				var old_id = ui.item.data("id"); //get old id

				ui.item.attr("id", old_id); //update id
			},
			update: function(event, ui) {
				var order = $(this).sortable('toArray');
				var status = $(this).data('status');

				$.ajax({
					type: 'post',
					url: '/issue/'+project_id+'/sort',
					data: {status:status, order: order, _token: token },
					success: function(res) {
						// alert('gege');
					}
				});
			}
		});
	});

	$('.add-issue-form').on('submit', function(e) {
		e.preventDefault();

		var $this = $(this);
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(res) {
				if(res.status = 200) {
					$this.prev('.source').append('<li id="'+ res.issue.id +'" data-id="'+ res.issue.id +'" data-position="'+ res.position +'" data-toggle="modal" data-target="#taskDetailViewModal" class="task-name">'+ res.issue.title +'</li>');
					$this.find('.add-issue-form-title').val('');
					$this.find('.add-issue-form-timelog-hours').val(0);
					$this.find('.add-issue-form-timelog-minutes').val(0);
					
					var objDiv = $this.prev('.source');
					objDiv.scrollTop(objDiv[0].scrollHeight);
				}
			}
		});

		return false;
	});

	$(document).on('click', '.task-name', function() {
		var task_id = $(this).data('id');
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
	});
</script>
@endpush