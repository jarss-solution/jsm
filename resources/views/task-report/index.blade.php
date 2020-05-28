@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10">
			<div class="sideBySide row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							Task Report
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									<div class="padding-10">
										<h4>Filter</h4>
										{{ html()->form('GET', route('task-reports.search'))->acceptsFiles()->open() }}
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="">Project</label>
													{{ 
														html()->select('project_id', $projects)->class('form-control')->placeholder('Select project')
														->value(request()->get('project_id'))
													}}
												</div>	
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="">Assigned To</label>
													{{ 
														html()->select('assigned_to', $users)->class('form-control')->placeholder('Select assigned to')
														->value(request()->get('assigned_to'))
													}}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="">Assigned By</label>
													{{ 
														html()->select('assigned_by', $users)->class('form-control')->placeholder('Select assigned by')
														->value(request()->get('assigned_by'))
													}}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="">From Date</label>
													{{ html()->date('from_date')->class('form-control')->value(request()->get('from_date')) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="">To Date</label>
													{{ html()->date('to_date')->class('form-control')->value(request()->get('to_date')) }}
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="">Task Status</label>
													{{ 
														html()->select('status', [
															'1' => 'Completed',
															'0' => 'Not completed',
														])->class('form-control')->placeholder('Select status')
														->value(request()->get('status'))
													}}
												</div>
											</div>
										</div>
										<button class="btn btn-danger"><i class="fa fa-search"></i> Filter</button>
										{{ html()->form()->close() }}
									</div>
								</div>
								<div class="col-md-4">
									<div class="row">
										<div class="col-md-12">
											<div class="padding-20">
												<h4>Task Details</h4>
												<table class="table table-bordered">
													<tbody>
														<tr>
															<td>Total Hours</td>
															<td>{{ $total_hours }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table table-bordered table-hover table-datatable">
									<thead>
										<tr>
											<td>#ID</td>
											<td>Task</td>
											<td>Project</td>
											<td>Total Hours</td>
											<td>Assigned Users</td>
											<td>Assigned By</td>
											<td>Complete Status</td>
											<td>Updated Date</td>
										</tr>
									</thead>
									<tbody>
										@foreach($issues as $issue)
										<tr class="task-name" data-task-id="{{ $issue->id }}" data-toggle="modal" data-target="#exampleModalCenter">
											<td>{{ $issue->id }}</td>
											<td>
												<div title="{{ $issue->title }}">{{ str_limit($issue->title, 60, '...') }}</div>
											</td>
											<td>{{ $issue->project ? $issue->project->title : '-' }}</td>
											<td>{{ $issue->timeConvert($issue->time_log) }}</td>
											<td>
												@foreach($issue->assignees as $assigne)
												<div>{{ $assigne->name }}</div>
												@endforeach
											</td>
											<td>{{ $issue->assignedBy->name }}</td>
											<td>{!! $issue->status ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-danger">Not completed</span>' !!}</td>
											<td>
												<div>{{ $issue->updated_at }}</div>
												<div>{{ $issue->updated_at->diffForHumans() }}</div>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>

								<div class="pull-right p-t-10 kms-navigation">
									{!! $issues->appends(request()->input())->links() !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
	.task-name {
		cursor: pointer;
	}
	.modal .modal-header {
		padding: 25px;
	}
</style>
@endpush

@push('scripts')
<script>
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
</script>
@endpush
