@foreach ($logs as $log)
		@php
			$str = $log;
			$temp1 = explode(".",$str);
			$temp2 = explode(" ",$temp1[0]);
			$type = $temp2[2];

			$file_name = explode('===',$log);
			$log = str_replace("===".$file_name[1],"",$log);
		@endphp
	
		<tr>
			<td>{{ $file_name[1] }}</td>
			<td>{{ $type }}</td>
			<td class="expand-row table-hover-cell">
				<span class="td-mini-container">
				{{ strlen( $log ) > 110 ? substr( $log , 0, 110).'...' :  $log }}
				</span>
				<span class="td-full-container hidden">
				{{ $log }}
				</span>
			</td>
			<td>
				<button type="button" class="btn btn-default assign_task" data-toggle="modal" data-target="#assign_task_model">Assign Task</button>
				<button type="button" class="btn btn-image view_error" data-toggle="modal"> <img src="/images/view.png"> </button>
			</td>
		</tr>
@endforeach