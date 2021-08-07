@extends('layouts.app')

@section('styles')
@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
@endsection

@section('content')
<div class="row">
	<div class="col-lg-12 margin-tb">
		<h2 class="page-heading">Broadcast List</h2>
		<div class="pull-left">
			<form action="{{ route('document.index') }}" method="GET">
				<div class="form-group">
					<div class="row">
						<div class="col-md-8">
							<input name="term" type="text" class="form-control"
							value="{{ isset($term) ? $term : '' }}"
							placeholder="user,department,filename">
						</div>

						<div class="col-md-1">
							<button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="pull-right">
			<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#documentCreateModal">+</a>

			</div>
		</div>
	</div>
	 @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Broadcast Id</th>
                <th>Datetime</th>
                <th>Delivered</th>
                <th>Lead Create</th>
                <th>Comments</th>
            
            </tr>
            </thead>

            <tbody>
           		<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
         	</tbody>
        </table>
    </div>

@endsection
