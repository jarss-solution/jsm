<form action="{{ route('task.store') }}" id="addKaziTask"  method="post">
	@csrf
	<input type="hidden" name="type" value="company">
	<div class="p-l-20 p-r-20 p-t-30">
		<div class="form-group form-task-title-wrapper">
			<label>Task</label>
			<span class="text-danger">*</span>
			<div class="form-task-title-group">
				<div class="form-group form-task-title">
					<input type="text" name="title[]" class="form-control" placeholder="Task" required>
				</div>
			</div>
			<button type="button" class="btn btn-default btn-xs add-more-task-title" style="font-size: 12px;float: right;"><i class="fa fa-plus"></i> Add more</button>
		</div>
		<div class="clearfix"></div>
		<div class="form-group">
			<label>Category</label>
			<span class="text-danger">*</span>
			<select name="category" class="form-control" required>
				<option value="">Select category</option>
				<option value="now">Get it done now</option>
				<option value="need">Needs to be completed</option>
				<option value="free">Whenever free</option>
				<option value="rockstar">You are a Rockstar</option>
			</select>
		</div>
		<div class="form-group">
			<label>Assigned to</label>
			<span class="text-danger">*</span>
			<div>
				{{
				html()
				->select('assigned_to[]', $users)
				->class('task-assigned-to chosen-select')
				->placeholder('Select member')
				->multiple()
				->required()
				}}
			</div>
		</div>
		<div class="form-group">
			<label>Project</label>
			{{
			html()
			->select('project_id', $projects)
			->class('form-control task-project-select')
			->placeholder('Select project')
			}}
		</div>
		<div class="form-group">
			<label>Project status</label>
			{{
			html()
			->select('project_status_id', [])
			->class('form-control task-project-status-select')
			->placeholder('Select project status')
			}}
		</div>
		<button id="eventSave" class="btn btn-warning btn-cons btn-black"><i class="fa fa-plus"></i> Add</button>
	</div>
</form>