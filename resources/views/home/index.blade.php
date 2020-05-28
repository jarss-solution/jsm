@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<!-- START PAGE CONTENT -->
	<div class="content sm-gutter">
		<!-- START CONTAINER FLUID -->
		<div class="container-fluid padding-25 sm-padding-10">
			<div class="row">
				<div class="col-md-6">
					<div class="card no-border no-margin widget-loader-circle">
						<div class="card-header ">
							<div class="card-title">
								<i class="pg-map"></i> {{ env('WEATHER_LOCATION') }}
								<span class="caret"></span>
							</div>
							<div class="card-controls">
								<ul>
									<li>
										<a data-toggle="refresh" class="card-refresh text-black" href="#">
											<i class="card-icon card-icon-refresh"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="card-body">
							<a class="weatherwidget-io" href="{{ env('WEATHER_CODE') }}" data-label_1="{{ env('WEATHER_LOCATION') }}" data-label_2="WEATHER" data-theme="original" >{{ env('WEATHER_LOCATION') }}</a>
							<script>
								!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
							</script>
							<!-- <div class="p-t-20">
								<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;src={{ env('CALENDAR_WIDGET') }}" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
							</div> -->
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card no-border no-margin widget-loader-circle">
						<div class="card-header ">
							<div class="card-title">
								<i class="pg-notice"></i> Noticeboard
								<span class="caret"></span>
							</div>
							<div class="card-controls">
								<ul>
									<li>
										<a data-toggle="refresh" class="card-refresh text-black" href="#">
											<i class="card-icon card-icon-refresh"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="card-body">
							{{ html()->modelForm($noticeboard, 'POST', route('home.noticeboard'))->open() }}
							<div class="form-group">
								{{ html()->textarea('content')->class('form-control')->placeholder('Content')->attribute('rows', 40)->required() }}
							</div>
							@if(Auth::user()->role == 1)
								<button class="btn btn-black text-white">Save</button>
							@endif
							{{ html()->closeModelForm() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection