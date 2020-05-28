@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10">
			<div class="row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							{{ $user->name }}
						</div>
						<div class="card-body pt-2">
							<div class="filter">
								<h6>Filter</h6>
								{{ html()->form('get', route('user.show', $user->id))->open() }}
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Start date</label>
												{{ html()->date('start', date('Y-m-d', strtotime(request()->start)))->class('form-control')->required() }}
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">End date</label>
												{{ html()->date('end', date('Y-m-d', strtotime(request()->end)))->class('form-control')->required() }}
											</div>
										</div>
									</div>
									
									<button type="submit" class="btn btn-danger">Filter</button>
								{{ html()->form()->close() }}
							</div>
							<hr>
							@if($this_week)
								<h5>This weeks logged hours</h5>
							@else
								<h5>Timelog from {{Request::get('start')}} to {{Request::get('end')}}</h5>
							@endif
							
							<table class="table table-bordered">
								@foreach($time_logs as $time_log)
								<tr>
									<td>{{ $time_log['project']['title'] ?: 'No project selected' }}</td>
									<td>
										@php
											$time_log_hour = floor($time_log['total_time']);
									        $time_log_minute =  ($time_log['total_time'] - $time_log_hour) * 60;
									        $time_log_minute =  round($time_log_minute);
											
											echo $time_log_hour.'hr '.$time_log_minute.'min';
										@endphp
									</td>
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection