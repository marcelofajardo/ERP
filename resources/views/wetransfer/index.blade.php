@extends('layouts.app')

@section('title', 'WeTransfer Queues')

@section("styles")
<style>

    #loading-image {
           position: fixed;
           top: 50%;
           left: 50%;
           margin: -50px 0px 0px -50px;
           z-index: 60;
       }

</style>
@endsection
<div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">WeTransfer Queues</h2>
             <div class="pull-right">
                <!-- <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button> -->
            </div>

        </div>
    </div>

    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th width="10%">Type</th>
                <th width="10%">URL</th>
                <th width="10%">Supplier</th>
                <th width="10%">Is Processed</th>
                <th width="10%">Updated At</th>
                <th width="10%">Total files</th>
               
            </tr>
            @foreach($wetransfers as $wetransfer)
             <tr>
                    <td>{{ $wetransfer->type }}</td>
                    <td class="expand-row table-hover-cell"><span class="td-mini-container">
                        {{ strlen( $wetransfer->url ) > 50 ? substr( $wetransfer->url , 0, 50).'...' :  $wetransfer->url }}
                        </span>
                        <span class="td-full-container hidden">
                        {{ $wetransfer->url }}
                        </span>
                    </td>
                    <td>{{ $wetransfer->supplier }}</td>
                    <td>@if($wetransfer->is_processed == 1) Pending @elseif($wetransfer->is_processed == 2) Success @else Failed @endif</td>
                    <td>{{ $wetransfer->updated_at->format('d-m-Y : H:i:s') }}</td>     
                    <td> 
                        <button style="padding:3px;" type="button" class="btn btn-success show-files-list d-inline" data-json="{{ $wetransfer->files_list }}" data-toggle="modal" data-target="#makeRemarkModal" data-id="{{ $wetransfer->id }}"> {{ $wetransfer->files_count ?? 0 }} </button>
                        <button style="padding:3px;" type="button" class="btn btn-info show-files-list d-inline re-download-files" data-id="{{ $wetransfer->id }}"> <i class="fa fa-repeat"></i></button>
                    </td>     
            </tr>
            @endforeach
            {{ $wetransfers->render() }}
        </thead>
    </table>
</div>

<div id="makeRemarkModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Files list</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body files-list-body">
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

@endsection  

@section('scripts')

<script type="text/javascript">
    //Expand Row
         $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });
         
        $(document).on('click', '.show-files-list', function () {
            $('.files-list-body').empty();
            var data = $(this).data();
            var asset = '{{ asset("public/wetransfer/") }}';
            if ( data.json !== null ) {
                $.each(data.json, function (index, valueOfElement) { 
                    $('.files-list-body').append('<div class="form-group"><a href="'+asset+'/'+valueOfElement+'"> '+valueOfElement+' </a></div>');
                });
            }else{
                $('.files-list-body').append('<div class="form-group">No files found</div>');
            }
        });

        $(document).on('click', '.re-download-files', function () {

            var data = $(this).data();
            $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data : {id : data.id },
				url: '/wetransfer/re-downloads-files',
				type: 'post',

				beforeSend: function () {
						$("#loading-image").show();
					},
				}).done( function(response) {
					if (response.status === true) {
						toastr['success'](response.message);
					}else{
                        toastr['error'](response.message);
					}
					$("#loading-image").hide();
				}).fail(function(errObj) {
					$("#loading-image").hide();
					alert('Something went wrong')
				});
        });
</script>

@endsection  