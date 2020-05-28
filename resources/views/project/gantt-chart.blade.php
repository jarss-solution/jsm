@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="jumbotron" data-pages="parallax">
			<div class="container-fluid sm-p-l-0 sm-p-r-0">
				<div class="inner" style="transform: translateY(0px); opacity: 1;">
					<!-- START BREADCRUMB -->
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('project.index') }}">Project</a></li>
						<li class="breadcrumb-item"><a href="{{ route('project.show', $project->slug) }}">{{ $project->title }}</a></li>
						<li class="breadcrumb-item active">Gantt chart</li>
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
							{{ $project->title }} Gantt chart
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div id="gantt_here" style="width: 100%; height: 500px;"></div>
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
	<link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
@endpush

@push('scripts')
<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
<script>
	var project_id = "{{ $project->id }}";

	gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
	gantt.init("gantt_here");
	gantt.load('/project/'+ project_id +'/gantt-chart-data');

	var dp = new gantt.dataProcessor("/api");
	dp.init(gantt);
	dp.setTransactionMode("REST");
	
	gantt.attachEvent("onTaskCreated", function(task){
    	task.project_id = project_id;
    	return true;
	});
</script>
@endpush