@extends('layouts.app')


@section('large_content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">
			<h2>Ad Reports/Schedules</h2>
		</div>
	</div>
</div>


@if ($message = Session::get('message'))
<div class="alert alert-success">
	<p>{{ $message }}</p>
</div>
@endif

	

<div class="mt-4">
	<div id="exTab2">
		<ul class="nav nav-tabs">
			<li class="active">
				<a  href="#1" data-toggle="tab">Ads List</a>
			</li>
			<li><a href="#2" data-toggle="tab">Calandar</a>
			</li>
			<li><a href="#3" data-toggle="tab">Schedules</a>
			</li>
		</ul>

		<div class="tab-content">
			<br>
			<form method="post" action="{{ action('SocialController@getSchedules') }}">
				@csrf
				From <input type="text" value="{{ $request->date_from ?? '' }}" name="date_from" id="min">
				To <input type="text" value="{{ $request->date_to ?? '' }}" name="date_to" id="max"> &nbsp; <button class="btn btn-sm btn-primary">Go</button>
				<div class="text-center">
					@if($previous)
						<input type="hidden" value="{{ $previous }}" name="prev">
						<input value="Previous" name="previous" class="btn btn-primary" type="submit">
					@endif
					@if($next)
						<input type="hidden" value="{{ $next }}" name="nxt">
						<input value="Next" name="next" class="btn btn-primary" type="submit" >
					@endif
				</div>
			</form>
			<div class="tab-pane active" id="1">
				<table class="table mt-1 table-striped" id="myTable">
					<thead>
						<tr>
							<th>S.N</th>
							<th>Ad Set #</th>
							<th>Ad Set Name</th>
							<th>Images</th>
							<th>Name</th>
							<th>Target Audience</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Spend</th>
							<th>Number Of Clicks</th>
							<th>Reach</th>
							<th>Audience/Impressions</th>
							<th>Status</th>
							<th>Created At</th>
						</tr>
					</thead>
					<tbody>
					<?php $sn = 1; ?>
						@foreach($ads as $key=>$ad)
								<tr data-adId="{{$ad['id']}}">
									<td>{{ $sn }}</td>
									<td>{{ $ad['adset_id'] }}</td>
									<td>{{ $ad['adset_name'] }}</td>
									<td>
										@foreach($ad['ad_creatives'] as $ad_cr)
											<img src="{{ $ad_cr }}" />
										@endforeach
									</td>
									<td>{{ $ad['name'] }}</td>
									<td>
										@foreach($ad['targeting'] as $key=>$value)
											@if(!is_object($value))
												<span style="display:block"><strong>{{ucfirst($key)}}:</strong> {{($value ?? 'N/A')}}</span>
											@endif
										@endforeach
									</td>
									<td>{{ $ad['ad_insights']['date_start'] ?? 'N/A' }}</td>
									<td>{{ $ad['ad_insights']['date_start'] ?? 'N/A' }}</td>
									<td>{{ $ad['ad_insights']['spend'] ?? 'N/A' }}</td>
									<td>{{ $ad['ad_insights']['clicks'] ?? 'N/A' }}</td>
									<td>{{ $ad['ad_insights']['reach'] ?? 'N/A' }}</td>
									<td>{{ $ad['ad_insights']['impressions'] ?? 'N/A' }}</td>
									<td>{{ $ad['status'] }}</td>
									<td>{{ \Carbon\Carbon::createFromTimeString($ad['created_time'])->format('Y/m/d') }}</td>
								</tr>
								<?php $sn++; ?>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="2">
				<div id="calendar"></div>
			</div>
			<div class="tab-pane" id="3">
				<h1>Add/Edit Ads Schedules</h1>
				<p>
					<a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
						Create New Ad
					</a>
				</p>
				<div class="collapse" id="collapseExample">
					<div class="card card-body">
						<form method="post" action="{{action('SocialController@createAdSchedule')}}">
							@csrf
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Ad Name">
							</div>
							<div class="form-group">
								<label for="date">Scheduled For</label>
								<input type="date" class="form-control" name="date" id="date">
							</div>
							<div class="form-group">
								<button class="btn btn-info">Create Ad</button>
							</div>
						</form>
					</div>
				</div>
				@if (count($schedules))
					<table class="table table-striped">
						<tr>
							<th>SN</th>
							<th>Name</th>
							<th>Scheduled For</th>
							<th>Edit</th>
						</tr>
						@foreach($schedules as $key=>$schedule)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $schedule->name }}</td>
								<td>{{ $schedule->scheduled_for }}</td>
								<td>
									<a href="{{ action('SocialController@showSchedule', $schedule->id) }}">Edit</a>
								</td>
							</tr>
						@endforeach
					</table>
				@else
					<h2 class="text-center">
						There are no schedules at the moment!
					</h2>
				@endif
			</div>
		</div>
	</div>
</div>
<br>
<br>
<br>
<br>
<br>

@endsection

@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#calendar').fullCalendar({
				header: {
					right: "month,agendaWeek,agendaDay, today prev,next",
				},
				events: '{{ action('SocialController@getAdSchedules') }}'
			});

		});

		$(document).ready(function(){
			// $.fn.dataTable.ext.search.push(
			// 	function (settings, data, dataIndex) {
			// 		var min = $('#min').datepicker("getDate");
			// 		var max = $('#max').datepicker("getDate");
			// 		var startDate = new Date(data[13]);
			// 		if (min == null && max == null) { return true; }
			// 		if (min == null && startDate <= max) { return true;}
			// 		if(max == null && startDate >= min) {return true;}
			// 		if (startDate <= max && startDate >= min) { return true; }
			// 		return false;
			// 	}
			// );


			$("#min").datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#max").datepicker({
				dateFormat: 'yy-mm-dd'
			});
			var table = $('#myTable').DataTable();

			// Event listener to the two range filtering inputs to redraw on input
			// $('#min, #max').change(function () {
			// 	table.draw();
			// });
		});
	</script>
@endsection