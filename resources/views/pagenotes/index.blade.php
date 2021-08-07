@extends('layouts.app')

@section('title', 'Page Notes')

@section("styles")
<!-- START - Purpose : Add CSS - DEVTASK-4289 -->
<style type="text/css">
  table tr td{
    overflow-wrap: break-word;
  }
    .page-note{
        font-size: 14px;
    }
    .flex{
        display: flex;
    }
</style>
<!-- END - DEVTASK-4289 -->
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Page Notes</h2>
  </div>

    <!-- START - Purpose : Get Page Note - DEVTASK-4289 -->
    <form method="get" action="{{ route('pageNotes.viewList') }}">
    <div class="flex">
        <div class="col">
            <?php echo Form::text("search", request("search", null), ["class" => "form-control", "placeholder" => "Enter input here.."]); ?>
        </div>
        <button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
            <img src="/images/search.png" style="cursor: default;">
        </button>
        <a href="{{route('pageNotes.viewList')}}" type="button" class="btn btn-image" id=""><img src="/images/resend2.png"></a>    
    </div>
    </form>
    <!-- END - DEVTASK-4289 -->
 
  <div class="col-md-12">
    <div class="pagenote-scroll"><!-- Purpose : Add Div for Scrolling - DEVTASK-4289 -->
      <div class="table-responsive">
        <table cellspacing="0" role="grid" class="page-notes table table-bordered datatable mdl-data-table dataTable page-notes" style="width:100%">
          <thead>
              <tr>
                  <th width="5%">#</th>
                  <th width="8%">Category</th>
                  <th width="60%">Note</th>
                  <th width="7%">User Name</th>
                  <th width="10%">Created at</th>
                  <th width="10%">Action</th>
              </tr>
          </thead>
          <tbody>
          <!-- START - Purpose : Get Data - DEVTASK-4289 -->
          @foreach($records as $key => $value)
              <tr>
                  <td>{{$value->id}}</td>
                  <td>{{$value->category_name}}</td>
                  @if (strlen($value->note) > 200)
                      <td style="word-break: break-word;" data-log_message="{!!$value->note !!}" class="page-note-popup">{{ substr($value->note,0,200) }}...</td>
                  @else
                      <td style="word-break: break-word;">{!!$value->note !!}</td>
                  @endif
{{--                      <p class="m-0">{!!$value->note !!}</p>--}}
                  <td>{{$value->name}}</td>
                  <td>{{ date('m-d  H:i', strtotime($value->created_at)) }}</td>
                  <td><a href="javascript:;" data-note-id = "{{$value->id}}" class="editor_edit btn-xs btn btn-image p-2">
                    <img src="/images/edit.png"></a>
                    <a data-note-id = "{{$value->id}}" href="javascript:;" class="editor_remove btn-xs btn btn-image p-2">
                    <img src="/images/delete.png"></a></td>
              </tr>
          @endforeach
          <!-- END - DEVTASK-4289 -->
          </tbody>
        </table>
      </div> 
      {{ $records->appends(Request::except('page'))->links() }}<!-- Purpose : Set Pagination - DEVTASK-4289 -->
    </div>
  </div>
</div> 
<div id="erp-notes" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>


<!--Log Messages Modal -->
<div id="pageNotesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Note</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p style="word-break: break-word;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')


  <script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
  <script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script><!-- Purpose : Add Js for scroll - DEVTASK-4289 -->
  <script type="text/javascript">
      $(document).on('click','.page-note-popup',function(){
          $('#pageNotesModal').modal('show');
          $('#pageNotesModal p').text($(this).data('log_message'));
      })

    $(document).ready(function() {
      //START - Purpose : Comment Code - DEVTASK-4289

      // $('.datatable').DataTable({
      //       processing: true,
      //       serverSide: true,
      //       order: [[ 0, "desc" ]],
      //       ajax: '{{ route('pageNotesRecords') }}',
      //       columns: [
      //         {data: 'id', name: 'id'},
      //         {data: 'category_name', name: 'category_name'},
      //         {
      //             data: 'note',
      //             render : function ( data, type, row ) {
                      
      //                var data_note =  row.note.replaceAll("&lt;", "<").replaceAll("&gt;",'>');
      //                 return data_note;
      //             },
      //         },
      //         {data: 'name', name: 'name'},
      //         {data: 'created_at', name: 'created_at'},
      //         {
      //             data: null,
      //             render : function ( data, type, row ) {
      //                 // Combine the first and last names into a single table field
      //                 return '<a href="javascript:;" data-note-id = "'+data.id+'" class="editor_edit btn btn-image"><img src="/images/edit.png"></a><a data-note-id = "'+data.id+'" href="javascript:;" class="editor_remove btn btn-image"><img src="/images/delete.png"></a>';
      //             },
      //             className: "center"
      //         }
      //     ]
      //   });

      //END - DEVTASK-4289
  });

  //START - Purpose : Add editor , scroll - DEVTASK-4289
  $('#erp-notes').on('show.bs.modal', function() {
    $('#note').richText();
  });

  $('.pagenote-scroll').jscroll({

    autoTrigger: true,
    debug: true,
    loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
    padding: 20,
    nextSelector: '.pagination li.active + li a',
    contentSelector: 'div.pagenote-scroll',
    callback: function () {
        $('ul.pagination').first().remove();
        $('ul.pagination').hide();
    }
  });
  //END - DEVTASK-4289

  $(document).on('click', '.editor_edit', function () {

       var $this = $(this);
        $.ajax({
            type: "GET",
            data : {
              id : $this.data("note-id")
            },
            url: "{{ route('editPageNote') }}"
        }).done(function (data) {
           $("#erp-notes").find(".modal-body").html(data);
           
           $("#erp-notes").modal("show");
        }).fail(function (response) {
            console.log(response);
        });
    });

    $(document).on('click', '.update-user-notes', function (e) {
      e.preventDefault();
      var $this = $(this);
      var $form  = $this.closest("form");
      $.ajax({
            type: "POST",
            data : $form.serialize(),
            url: "{{ route('updatePageNote') }}"
        }).done(function (data) {
           if(data.code == 1) {
               $("#erp-notes").find(".modal-body").html("");
               $("#erp-notes").modal("hide");
               location.reload(true);
           }else{
              alert(data.message);
           } 
        }).fail(function (response) {
            console.log(response);
        });
    });
     $(document).on('click', '.editor_remove', function () {
      var r = confirm("Are you sure you want to delete this notes?");
      if (r == true) {
        var $this = $(this);
          $.ajax({
              type: "GET",
              data : {
                id : $this.data("note-id")
              },
              url: "{{ route('deletePageNote') }}"
          }).done(function (data) {
             $("#erp-notes").find(".modal-body").html("");
             $("#erp-notes").modal("hide");
             location.reload(true);
          }).fail(function (response) {
              console.log(response);
          });
      }
    });
  </script>
@endsection
