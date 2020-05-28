@extends('layouts.app')
@section('content')
<div class="page-content-wrapper ">
	<div class="content sm-gutter">
		<div class="container-fluid padding-25 sm-padding-10">
			<div class="sideBySide row">
				<div class="timelog-cat col-md-12">
					<div class="card task-card">
						<div class="card-header">
							Users List
							<a class="btn btn-primary btn-xs float-right" href="{{ route('user.create') }}"><i class="fa fa-plus"></i> Add User</a>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-datatable">
									<thead>
										<tr>
											<td>Image</td>
											<td>Name</td>
											<td>Email</td>
											<td>Role</td>
											<td>Action</td>
										</tr>
									</thead>
									<tbody>
										@foreach($users as $user)
										<tr>
											<td><img src="{{ $user->profilePicture() }}" height="50"></td>
											<td>{{ $user->name }}</td>
											<td>{{ $user->email }}</td>
											<td>{{ $user->role() }}</td>
											<td>
												<a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-xs" style="float: left; margin-right: 10px;">Edit</a>

												<form action="{{ route('user.destroy', [$user->id]) }}" method="post" style="float:left;">
													@method('DELETE')
													@csrf
													<button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')" type="submit">Delete</button>
												</form>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
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
	<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"/>
@endpush


@push('scripts')
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
	<script>
		$('.table-datatable').dataTable({
			"aaSorting": []
		});
	</script>
@endpush
