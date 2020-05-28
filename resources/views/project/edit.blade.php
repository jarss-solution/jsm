@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="jumbotron" data-pages="parallax">
			<div class="container-fluid sm-p-l-0 sm-p-r-0">
				<div class="inner" style="transform: translateY(0px); opacity: 1;">
					<!-- START BREADCRUMB -->
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Project</a></li>
						<li class="breadcrumb-item active">{{ $project->title }}</li>
					</ol>
					<!-- END BREADCRUMB -->
				</div>
			</div>
		</div>

		<div class="container-fluid sm-padding-10">
			<div class="sideBySide row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							Update Project
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									<div class="padding-10">
										{{ html()->modelForm($project, 'PATCH', route('project.update', [$project->slug]))->open() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Title</label>
													<span class="text-danger">*</span>
													{{ html()->text('title')->class('form-control')->placeholder('Title')->required() }}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Client Name</label>
													{{ html()->text('client_name')->class('form-control')->placeholder('Title') }}
												</div>
											</div>
										</div>
													
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Deadline</label>
													{{ html()->date('deadline')->class('form-control')->placeholder('Deadline') }}
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label>Currency</label>
															{{ html()->select('budget_currency', ['NRS' => 'NRS', 'USD' => 'USD'])->class('form-control')->required() }}
														</div>
													</div>
													<div class="col-md-8">
														<div class="form-group">
															<label>Budget</label>
															{{ html()->text('budget')->class('form-control')->placeholder('Budget') }}
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Assigned users</label>
													<span class="text-danger">*</span>
													{{ html()->select('assigned_users', $users, $project->assignedUsers->pluck('id'))
														->class('form-control multiselect')
														->placeholder('Select users')
														->multiple()
														->required() }}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Type</label>
													<span class="text-danger">*</span>
													{{ 
														html()->select('type', [
															'tech' => 'Tech',
															'design' => 'Design',
															'product' => 'Product',
															'potential_lead' => 'Potential lead',
															'web_hosting' => 'Web hosting',
															'amc' => 'AMC',
														])->class('form-control form-control-type')
														->placeholder('Select type')
														->required()
													}}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Estimated Hours</label>
													{{ html()->input('number', 'estimated_hours')->class('form-control')->placeholder('Estimated Hours') }}
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Active Status</label>
													<span class="text-danger"> *</span>
													{{ html()->select('status', [1 => 'Active', 0 => 'Archive'])
														->class('form-control')
														->placeholder('Select project status')
														->required() }}
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label>Description</label>
											{{ html()->textarea('description')->class('form-control')->placeholder('Project Description') }}
										</div>										

										

										<div class="form-group">
											<label>Process prepopulate checklist</label>
											<div class="process-prepopulate-checklist-tech">
												@foreach($process_tech as $key => $processTech)
												<div class="checkbox check-primary">
													<input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_tech_{{ $key }}" class="process-checkbox" {{ in_array($key, $project_process) ? 'checked' : '' }}>
													<label for="checkbox_tech_{{ $key }}">{{ $processTech }}</label>
												</div>
												@endforeach
											</div>
											<div class="process-prepopulate-checklist-design">
												@foreach($process_design as $key => $processDesign)
												<div class="checkbox check-primary">
													<input name="process[]" type="checkbox" value="{{ $key }}" id="checkbox_design_{{ $key }}" class="process-checkbox" {{ in_array($key, $project_process) ? 'checked' : '' }}>
													<label for="checkbox_design_{{ $key }}">{{ $processDesign }}</label>
												</div>
												@endforeach
											</div>
										</div>
										
										<button class="btn btn-success" type="submit">Save</button>
										{{ html()->closeModelForm() }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('dist/assets/custom/multiselect/css/jquery.multiselect.css')}}">
<style>
	.process-prepopulate-checklist-tech, .process-prepopulate-checklist-design {
		display: none;
	}
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('dist/assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('dist/assets/custom/multiselect/js/jquery.multiselect.js')}}"></script>
<script>
	$(function(){
		$(".multiselect").multiselect({
			columns: 1,
    		placeholder: 'Select employee',
    		search: true
		});
	});

	$('.custom-tag-input').tagsinput({});

	showHideProcessChecklist();
	$('.add-project').on('click', function() {
		$('#calendar-event').addClass('open');
	});
	
	$('.form-control-type').on('change', function() {
		$('.process-checkbox').prop("checked", false);
		showHideProcessChecklist();
	});

	function showHideProcessChecklist() {
		if($('.form-control-type').val() == 'tech') {
			$('.process-prepopulate-checklist-tech').show();
			$('.process-prepopulate-checklist-design').hide();
		} else if($('.form-control-type').val() == 'design') {
			$('.process-prepopulate-checklist-design').show();
			$('.process-prepopulate-checklist-tech').hide();
		} else if($('.form-control-type').val() == 'product') {
			$('.process-prepopulate-checklist-design').hide();
			$('.process-prepopulate-checklist-tech').hide();
		}
	}
</script>
@endpush