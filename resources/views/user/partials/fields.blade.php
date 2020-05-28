<div class="row">
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Name</label>
					{{ html()->text('name')->class('form-control')->placeholder('Name')->required() }}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Email</label>
					{{ html()->email('email')->class('form-control')->placeholder('Email')->required() }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Password</label>
					{{ html()->password('password')->class('form-control')->placeholder('Password')->required() }}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Confirm Password</label>
					{{ html()->password('password_confirmation')->class('form-control')->placeholder('Password Confirmation')->required() }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="">Role</label>
					{{ html()->select('role', [ 1=>'Admin', 2=>'Normal'], 2)->class('form-control') }}
				</div>
			</div>
		</div>

	</div>
	<div class="col-md-3">
		<div class="form-group">
			<label>Image (200x200)</label>
			<div>	
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<div class="fileinput-new thumbnail" data-trigger="fileinput">
						<img src="{{ isset($user) ? $user->profilePicture() : 'http://via.placeholder.com/200x200?text=Image' }}" style="max-width: 100%;">
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" data-trigger="fileinput"></div>
					<div>
						<span class="btn btn-default btn-file">
							<span class="fileinput-new">Select</span>
							<span class="fileinput-exists">Change</span>
							<input type="file" name="image">
						</span>
						<a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>