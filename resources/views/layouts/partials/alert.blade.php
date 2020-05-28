@if(Session::has('notify_message'))
<div class="pgn-wrapper" data-position="top" style="top: 59px;">
	<div class="pgn push-on-sidebar-open pgn-bar">
		<div class="alert alert-{{ Session::get('notify_type') }}">
			<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span>
			</button>
			{{ Session::get('notify_message') }}
		</div>
	</div>
</div>
@endif

@if ($errors->any())
<div class="pgn-wrapper" data-position="top" style="top: 59px;">
	<div class="pgn push-on-sidebar-open pgn-bar">
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span>
			</button>
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endif