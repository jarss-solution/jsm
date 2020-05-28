@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10">
		</div> <!-- container -->
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