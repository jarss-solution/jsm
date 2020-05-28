@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10">
			<div class="row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							User Create
							<button class="add-task-btn"><i class="fa fa-plus"></i></button>
						</div>
						<div class="card-body pt-2">
							{{ html()->form('POST', route('user.store'))->acceptsFiles()->open() }}
								@include('user.partials.fields')
								<div class="row"><div class="row">
									<button class="btn btn-success" type="submit">Save</button>
								</div></div>
							{{ html()->form()->close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection