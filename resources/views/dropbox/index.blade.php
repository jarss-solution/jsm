@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10" style="padding-top: 3px !important;">
			<h5 style="color: #85858e;">The assets are synced with your company dropbox account. You can add files here for all employees to view eg. company logos, policies, process etc.</h5>
			<div class="sideBySide row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							Assets
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-8">
									@if(env('DROPBOX_AUTH_TOKEN'))
										<div class="padding-10">
											{{ html()->form('POST', route('dropbox.upload'))->acceptsFiles()->open() }}
											<div class="row">
												<div class="col-md-11">
													<div class="form-group">
														<input type="file" name="file" class="form-control">
													</div>
												</div>
												<div class="col-md-1">
													<button type="submit" class="btn btn-success">Upload</button>
												</div>
											</div>
											{{ html()->form()->close() }}

											<ul class="list-group">
												@foreach($all_files as $one_file)
												<li class="list-group-item">
													@php
														$file_url = Storage::disk('dropbox')->url($one_file);
													@endphp

													{{ $one_file }}
													<a href="{{ $file_url }}"><i class="fa fa-download download-icon"></i></a>
													<a href="{{ route('dropbox.delete', [$one_file]) }}" onclick="return confirm('Are you sure?')" class="pull-right">Delete</a>
												</li>
												@endforeach
											</ul>
										</div>
									@else
										<div class="padding-10">
											<h6>Provide your company's dropbox account</h6>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
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