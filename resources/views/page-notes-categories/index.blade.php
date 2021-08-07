@extends('layouts.app')

@section('title', 'Page Notes Categories')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Page Notes Categories</h2>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 border">
                    <div class="pull-right">
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                data-target="#pageNotesCategoriesModal"
                                title="Add new page notes category"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                     @include('partials.flash_messages')
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Sr. no</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($pageNotesCategories as $pageNotesCategory)
                                <tr>
                                    <td>{{ $pageNotesCategory->id }}</td>
                                    <td>{{$pageNotesCategory->name}}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-image"
                                                    data-toggle="modal"
                                                    data-target="#pageNotesCategoriesModal"
                                                    data-page-notes-category="{{ json_encode($pageNotesCategory) }}"><img
                                                        src="/images/edit.png"/></button>
                                            {!! Form::open(['method' => 'DELETE','route' => ['page-notes-categories.destroy', $pageNotesCategory->id],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-image"><img
                                                        src="/images/delete.png"/></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="15" class="text-center text-danger">No Page Notes Category Found.</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    {!! $pageNotesCategories->appends(Request::except('page'))->links() !!}

    <div id="pageNotesCategoriesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('page-notes-categories.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Add >Page Notes Category</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- short_note -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('short_note')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('name', 'Name', ['class' => 'form-control-label']) !!}
                                    {!! Form::text('name', null, ['class'=>'form-control '.($errors->has('name')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'rows'=>3, 'id' => 'name']) !!}
                                    @if($errors->has('name'))
                                        <div class="form-control-feedback">{{$errors->first('name')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Add</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#pageNotesCategoriesModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var pageNotesCategory = button.data('page-notes-category')
            if (pageNotesCategory != undefined) {
                var url = "{{ url('page-notes-categories') }}/" + pageNotesCategory.id;
                modal.find('form').attr('action', url);
                var method = '<input type="hidden" name="_method" value="PUT">'
                modal.find('form').append(method)
                modal.find('input[name="_method"]').val('PUT');
                modal.find('#name').val(pageNotesCategory.name)
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update Page Notes Categories')
            } else {
                var url = "{{ route('page-notes-categories.store') }}";
                modal.find('form').attr('action', url);
                modal.find('form').trigger('reset');
                modal.find('button[type="submit"]').html('Add')
                modal.find('.modal-title').html('Store Page Notes Categories')
                modal.find('input[name="_method"]').remove()
            }
        })
    </script>
@endsection