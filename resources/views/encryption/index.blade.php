@extends('layouts.app')

@section('title', 'Public Key For Encrption')


@section('content')

  <div class="row mb-5">
      <div class="col-lg-12 margin-tb">
          <h2 class="page-heading">Key For Data Encrption</h2>
			@if(session()->has('message'))
			    <div class="alert alert-success">
			        {{ session()->get('message') }}
			    </div>
			@endif
          <div class="pull-left">
			
          	<form class="form-inline" action="{{ route('encryption.save.key') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Public Key : </label>
					<div class="form-group mr-3">
					  <input name="public" type="file" class="form-control">
					</div>
               <button type="submit" class="btn btn-secondary"><i class="fa fa-filter"></i> Submit</button>

               @if($publicKey != null)
           			<button type="button" class="btn btn-success" style="margin-left: 10px;">Public Key Exist</button>
           			<button type="button" class="btn btn-danger" style="margin-left: 10px;" onclick="removePublicKey()">Remove Key</button>
           		@endif

           		
           </form>
           
			<br>
           <form class="form-inline" action="{{ route('encryption.save.key') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label>Private Key : </label>
					<div class="form-group mr-3">
					  <input name="private" type="file" class="form-control">
					</div>
               <button type="submit" class="btn btn-secondary"><i class="fa fa-filter"></i> Submit</button>
               @if(session()->has('encrpyt'))
					<button type="button" class="btn btn-success" style="margin-left: 10px;">Private Key Exist</button>
					<button type="button" class="btn btn-danger" style="margin-left: 10px;" onclick="removePrivateKey()">Remove Key</button>
               @endif

               
           </form>

          </div>

          <div class="pull-right mt-4">
          </div>
      </div>
  </div>


@endsection

@section('scripts')

<script type="text/javascript">
	
	function removePublicKey(){
		$.ajax({
			url: "{{ route('encryption.forget.key') }}",
			type: 'POST',
			dataType: 'json',
			data: {
				public: '1',
				"_token": "{{ csrf_token() }}",
			},
		})
		.done(function() {
			location.reload();
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		
		
	}

	function removePrivateKey(){
		$.ajax({
			url: "{{ route('encryption.forget.key') }}",
			type: 'POST',
			dataType: 'json',
			data: {
				private: '1',
				"_token": "{{ csrf_token() }}",
			},
		})
		.done(function() {
			location.reload();
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})	
	}
</script>


@endsection

