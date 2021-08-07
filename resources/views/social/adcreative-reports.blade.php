@extends('layouts.app')


@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<div class="pull-left">
			<h2 class="ml-4">AdCreative Reports <h2>
			</div>
		</div>
	</div>


	@if ($message = Session::get('message'))
	<div class="alert alert-success">
		<p>{{ $message }}</p>
	</div>
	@endif

	
	
	<div class="container-fluid mt-3">
		<div class="row">
			
			<div class="col-md-12" >
				<h2 class="text-info">Results</h2>
				<div class="content-section">
					<table class="table table-responsive table-hover table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>ACC ID</th>
								<th>ACC Name</th>
								<th>Adcreative Name</th>
								<th>Thumbnail</th>
								<th>Ad Name</th>
								<th>Campaign</th>
								<th>Adset ID</th>
								<th>Adset Name</th>
								<th>CPP</th>
								<th>CPM</th>
								<th>CPC</th>
								<th>CTR</th>
								<th>Clicks</th>
								<th>Unique Clicks</th>
								<th>Spend</th>
								<th>Reach</th>
								<th>Impressions</th>
								<th>Frequency</th>
								<th>Cost P/Result</th>
								<th>Ends</th>
							</tr>
						</thead>
						<tbody>

							@if(isset($resp->data))
							@php $i=1; @endphp
							@foreach($resp->data as $data)
							
							@if(isset($data->ads->data) && !empty($data->ads->data))
							
							@foreach($data->ads->data as $ads)
							<tr>
								<td>{{$i++}}</td>
								<td>{{(isset($ads->insights->data[0]->account_id))?$ads->insights->data[0]->account_id:''}}</td>
								<td>{{(isset($ads->insights->data[0]->account_name))?$ads->insights->data[0]->account_name:''}}</td>
								
								<td>{{(isset($ads->adcreatives->data[0]->name))?$ads->adcreatives->data[0]->name:''}}</td>
								<td><img src="{{(isset($ads->adcreatives->data[0]->thumbnail_url))?$ads->adcreatives->data[0]->thumbnail_url:''}}" alt="not-found"></td>
								
								<td>{{(isset($ads->insights->data[0]->ad_name))?$ads->insights->data[0]->ad_name:''}}</td>
								<td>{{(isset($ads->insights->data[0]->campaign_name))?$ads->insights->data[0]->campaign_name:''}}</td>
								<td>{{(isset($ads->insights->data[0]->adset_id))?$ads->insights->data[0]->adset_id:''}}</td>
								<td>{{(isset($ads->insights->data[0]->adset_name))?$ads->insights->data[0]->adset_name:''}}</td>
								<td>{{(isset($ads->insights->data[0]->cpp))?$ads->insights->data[0]->cpp:''}}</td>
								<td>{{(isset($ads->insights->data[0]->cpm))?$ads->insights->data[0]->cpm:''}}</td>
								<td>{{(isset($ads->insights->data[0]->cpc))?$ads->insights->data[0]->cpc:''}}</td>
								<td>{{(isset($ads->insights->data[0]->ctr))?$ads->insights->data[0]->ctr:''}}</td>
								<td>{{(isset($ads->insights->data[0]->clicks))?$ads->insights->data[0]->clicks:''}}</td>
								<td>{{(isset($ads->insights->data[0]->unique_clicks))?$ads->insights->data[0]->unique_clicks:''}}</td>
								<td>{{(isset($ads->insights->data[0]->spend))?$ads->insights->data[0]->spend:''}}</td>
								<td>{{(isset($ads->insights->data[0]->reach))?$ads->insights->data[0]->reach:''}}</td>
								<td>{{(isset($ads->insights->data[0]->impressions))?$ads->insights->data[0]->impressions:''}}</td>
								<td>{{(isset($ads->insights->data[0]->frequency))?$ads->insights->data[0]->frequency:''}}</td>
								<td>{{(isset($ads->insights->data[0]->cost_per_unique_click))?$ads->insights->data[0]->cost_per_unique_click:''}}</td>
								<td>{{(isset($ads->insights->data[0]->date_stop))?$ads->insights->data[0]->date_stop:''}}</td>
							</tr>
							@endforeach
							@endif
							@endforeach
							@endif
						</tbody>
					</table>

					<div class="container pull-left" style="overflow: hidden;">
						<div class="row">
							<div class="col-md-6 ml-aut mr-auto">
								<nav aria-label="Page navigation example">
									<ul class="pagination">
										<li class="page-item">
											<div class="col-md-4">
												@if(isset($resp->paging->previous))
												<form method="post" action="{{route('social.adCreative.paginate')}}">
													@csrf
													<input type="hidden" value="{{$resp->paging->previous}}" name="previous">
													<input type="submit" value="Previous" name="submit" class="btn btn-info">
												</form>
												@endif
											</div>
										</li>
										<li class="page-item">
											<div class="col-md-4 ml-3">
												@if(isset($resp->paging->next))
												<form method="post" action="{{route('social.adCreative.paginate')}}">
													@csrf
													<input type="hidden" value="{{$resp->paging->next}}" name="next">
													<input type="submit" value="Next" name="submit" class="btn btn-info">
												</form>
												@endif
											</div>
										</li>
										
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>





	@endsection