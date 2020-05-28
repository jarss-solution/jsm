<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Task assigned</title>
	<style>
		#task-table {
			width: 100%;
		    border-collapse: collapse;
		}

		#task-table td {
			padding: 10px;
		}
	</style>
</head>
<body>
	<table id="task-table" border="1">
		<tr>
			<td>Task title</td>
			<td>{{ $issue->title }}</td>
		</tr>
		<tr>
			<td>Category</td>
			<td>
				@if($issue->category == 'now')
					Get it done now
				@elseif($issue->category == 'need')
					Needs to be completed
				@elseif($issue->category == 'free')
					Whenever free
				@elseif($issue->category == 'rockstar')
					You are a rockstar
				@endif
			</td>
		</tr>
		<tr>
			<td>Assigned by</td>
			<td>{{ $assigned_by->name }}</td>
		</tr>
	</table>
</body>
</html>