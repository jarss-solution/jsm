@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">			
		<div class="container-fluid padding-25 sm-padding-10" style="padding-top: 3px !important;">
			<h5 style="color: #85858e;">The calendar is generated using your company google calendar. </h5>
			<div class="card">
				<div class="card-body">
				<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;src={{ env('CALENDAR_WIDGET') }}" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
				</div> <!-- container -->
			</div>
		</div>
	</div> <!-- content -->
</div>
@endsection

@push('scripts')
<script>
	$('.add-task-btn').on('click', function() {
		$('#calendar-event').addClass('open');
	})
</script>
@endpush