<div class="row">
	<div class="col-md-8 update-task-col p-t-20">
		<small><span class="">Task created at {{ date('Y-m-d', strtotime($issue->created_at)) }}</span></small>
		{{ html()->modelForm($issue, 'PATCH', route('task.update', [$issue->id]))->acceptsFiles()->open() }}
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Title</label>
					<span class="text-danger">*</span>
					{{ html()->text('title')->class('form-control')->placeholder('Title')->required() }}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Category</label>
					<span class="text-danger">*</span>
					{{ 
						html()->select('category', [
							'now' => 'Get it done now',
							'need' => 'Needs to be completed',
							'free' => 'Whenever free',
							'rockstar' => 'You are a Rockstar',
						])->class('form-control')->placeholder('Select category')->required()
					}}
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Project</label>
					{{ 
						html()->select('project_id', $projects)
						->class('form-control')
						->placeholder('Select project') 
					}}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Assigned to</label>
					<span class="text-danger">*</span>
					{{ 
						html()
						->select('assigned_to[]', $users, $issue->assignees->pluck('id'))
						->class('task-assigned-to chosen-select')
						->multiple()
						->required() 
					}}
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label>Estimated Time Hours</label>
					{{ html()->input('text', 'estimated_time_hours')->class('form-control decimal')->placeholder('Estimated Time Hours') }}
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6 no-padding-left">
						<div class="form-group">
							<label>Start Date</label>
							{{ html()->date('start_date')->class('form-control') }}
						</div>
					</div>
					<div class="col-md-6 no-padding-right">
						<div class="form-group">
							<label>End Date</label>
							{{ html()->date('end_date')->class('form-control') }}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Description</label>
					{{ html()->textarea('description')->class('form-control')->placeholder('Description') }}
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary">Save changes</button>	
		{{ html()->closeModelForm() }}
		
		<hr>

		<div class="task-comments m-t-10">
			{{ html()->form('PATCH', route('task-comment.update', [$issue->id]))->open() }}
			<div class="row">
				<div class="col-md-9 no-padding-left">
					<div class="form-group">
						<label>Comments related to this task.</label>
						{{ html()->textarea('comment')->class('form-control')->placeholder('Enter your comment.')->required() }}
					</div>
				</div>
				<div class="col-md-3 no-padding-right">
					<div class="form-group">
						<button class="btn btn-primary m-t-30" style="width: 100%; height: 50px;" type="submit">Comment</button>
					</div>
				</div>
			</div>
			{{ html()->form()->close() }}

			<h5>Comments</h5>
			<div class="card" style="width: 100%;height: 200px; overflow: scroll; overflow-x: hidden;">
				@foreach($issue->comments as $comment)
				<div class="card-header clearfix" style="padding: 10px; border-bottom: 1px solid #eee;">
					<div class="user-pic">
						<img alt="Profile Image" width="33" height="33" data-src-retina="{{ $comment->user->profilePicture() }}" data-src="{{ $comment->user->profilePicture() }}" src="{{ $comment->user->profilePicture() }}">
					</div>
					<div class="user-comment">
						<h6 style="margin: 0;">{{ $comment->user->name }} <small style="float: right;font-size: 9px;">{{ $comment->created_at->diffForHumans() }}</small></h6>
						<div>{{ $comment->comment }}</div>
					</div>
				</div>
				@endforeach
			</div>

			{{ html()->form('DELETE', route('task.destroy', $issue->id))->open() }}
				<div class="form-group">
					<button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Task</button>
				</div>
			{{ html()->form()->close() }}
		</div>
	</div>
	<div class="col-md-4" style="background-color: #eeeeee">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>

		<h5>TIME LOG</h5>

		<div class="form-group">
			<label>Add time to task</label>
			{{ html()->form('POST', route('issue.store-timelog', $issue->id))->open() }}
			<div class="row">
				<div class="col-md-6 no-padding-left">
					<div class="input-group">
						<input type="number" name="time_log_hour" class="form-control decimal" placeholder="Hours" min="0" value="0" required>
						<div class="input-group-append">
							<span class="input-group-text default">hrs</span>
						</div>
					</div>
				</div>
				<div class="col-md-6 no-padding-right">
					<div class="input-group">
						<input type="number" name="time_log_minute" class="form-control decimal" placeholder="Minutes" max="60" min="0" value="0" required>
						<div class="input-group-append">
							<span class="input-group-text default">mins</span>
						</div>
					</div>
				</div>
			</div>
			<button type="submit" class="btn btn-primary m-t-10">Add Time</button>
			{{ html()->form()->close() }}
			<hr>
			
			<h6>Logs</h6>
			<table class="table table-bordered">
				@foreach($issue->timelogs as $issue_time_log)
				<tr>
					<td style="font-size: 11px;">
						{{ $issue_time_log->user->name }} logged {{$issue_time_log->timelog()}} 
						<small class="pull-right">{{ $issue_time_log->created_at->diffForHumans() }}</small>
					</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>


<script>
	$(".chosen-select").chosen({max_selected_options: 5});
	$('.decimal').keyup(function(){
	    var val = $(this).val();
	    if(isNaN(val)){
	         val = val.replace(/[^0-9\.]/g,'');
	         if(val.split('.').length>2) 
	             val =val.replace(/\.+$/,"");
	    }
	    $(this).val(val); 
	})
</script>