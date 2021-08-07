 @extends('layouts.app')

@section('title', 'Case Info')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('large_content')

    <div class="row">
      <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Receivable History - <a href="{{route('case.show',$case->id)}}" title="Case Details">{{ $case->case_number }} </a>(Lawyer :<a
                        href="{{route('lawyer.show',$case->lawyer_id)}}" target="_blank" title="View Lawyer Detail"> {{ optional($case->lawyer)->name }})</a></h2>
        <div class="pull-left">
         {{-- <form class="form-inline" action="{{ route('case.index') }}" method="GET">
            <div class="form-group">
              <input name="term" type="text" class="form-control"
                     value="{{ isset($term) ? $term : '' }}"
                     placeholder="Search">
            </div>

            --}}{{-- <div class="form-group ml-3">
              <select class="form-control" name="type">
                <option value="">Select Type</option>
                ndr<option value="has_error" {{ isset($type) && $type == 'has_error' ? 'selected' : '' }}>Has Error</option>
              </select>
            </div> --}}{{--

              <div class="form-group">
                  <input type="checkbox" name="with_archived" id="with_archived" {{ Request::get('with_archived')=='on'? 'checked' : '' }}>
                  <label for="with_archived">Archived</label>
              </div>

            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
          </form>--}}
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#receivableFormModal">+</button>
        </div>
      </div>
    </div>

    @include('partials.flash_messages')
    
    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="5%">ID</th>
            <th width="5%">Currency</th>
            <th width="10%">Receivable Date</th>
            <th width="10%">Amount</th>
            <th width="10%">Status</th>
            <th width="10%">Received Date</th>
            <th width="10%">Received Amount</th>
            <th width="10%">Detail</th>
            <th width="10%">Action</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($receivables as $receivable)
            <tr>
              <td>{{ $receivable->id }}</td>
                <td>{{$currencies[$receivable->currency]??'N/A'}}</td>
                <td>{{$receivable->receivable_date}}</td>
                <td>{{$receivable->receivable_amount}}</td>
                <td>{{$receivable->status ? 'Received' : 'Pending'}}</td>
                <td>{{$receivable->received_amount}}</td>
                <td>{{$receivable->received_amount}}</td>
                <td class="expand-row table-hover-cell" style="word-break: break-all;">
                <span class="td-mini-container">
                  {{ strlen($receivable->module) > 10 ? substr($receivable->module, 0, 10) : $receivable->module }}
                </span>

                    <span class="td-full-container hidden">
                  {{ $receivable->module }}
                </span>
                </td>
                <td>{{$receivable->work_hour}}</td>
              <td class="expand-row table-hover-cell" style="word-break: break-all;">
                <span class="td-mini-container">
                  {{ strlen($receivable->description) > 10 ? substr($receivable->description, 0, 10) : $receivable->description }}
                </span>

                <span class="td-full-container hidden">
                  {{ $receivable->description }}
                </span>
              </td>
              <td>
                <div class="d-flex">
                  <button type="button" class="btn btn-image edit-case" data-toggle="modal" data-target="#receivableShowModal" data-receivable="{{ json_encode($receivable) }}" title="View Receivable Detail" data-currency="{{ $currencies[$receivable->currency]??'N/A' }}"><img src="/images/view.png" /></button>
                    <button type="button" class="btn btn-image edit-case" data-toggle="modal" data-target="#receivableFormModal" data-receivable="{{ json_encode($receivable) }}" title="Edit Receivable Detail"><img src="/images/edit.png" /></button>
                  {!! Form::open(['method' => 'DELETE','route' => ['case.receivable.destroy', $case->id,$receivable->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image" title="Delete Receivable detail"><img src="/images/delete.png" /></button>
                  {!! Form::close() !!}
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $receivables->appends(Request::except('page'))->links() !!}

    <div id="receivableFormModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('case.receivable.store', $case->id) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Add Receivable</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- currency -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('currency')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                     {!! Form::label('currency', 'Currency', ['class' => 'form-control-label']) !!}
                                    {!! Form::select('currency', $currencies, null, ['class'=>'form-control  '.($errors->has('currency')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'placeholder'=>'Choose Currency','required']) !!}
                                        @if($errors->has('currency'))
                                <div class="form-control-feedback">{{$errors->first('currency')}}</div>
                                            @endif
                                </div>
                            </div>
                            <!-- receivable_date -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('receivable_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('receivable_date', 'Receivable date', ['class' => 'form-control-label']) !!}
                                    {!! Form::date('receivable_date', null, ['class'=>'form-control '.($errors->has('receivable_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'required']) !!}
                                    @if($errors->has('receivable_date'))
                                        <div class="form-control-feedback">{{$errors->first('receivable_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- receivable_amount -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('receivable_amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('receivable_amount', 'Payable Amount', ['class' => 'form-control-label']) !!}
                                    {!! Form::number('receivable_amount', null, ['class'=>'form-control '.($errors->has('receivable_amount')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'required']) !!}
                                    @if($errors->has('receivable_amount'))
                                        <div class="form-control-feedback">{{$errors->first('receivable_amount')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- received_date -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('received_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('received_date', 'Received Date', ['class' => 'form-control-label']) !!}
                                    {!! Form::date('received_date', null, ['class'=>'form-control '.($errors->has('received_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('received_date'))
                                        <div class="form-control-feedback">{{$errors->first('received_date')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- received_amount -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('received_amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('received_amount', 'Received Amount', ['class' => 'form-control-label']) !!}
                                    {!! Form::number('received_amount', null, ['class'=>'form-control '.($errors->has('received_amount')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                    @if($errors->has('received_amount'))
                                        <div class="form-control-feedback">{{$errors->first('received_amount')}}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- description -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('description')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                                <div class="form-group">
                                    {!! Form::label('description', 'Description', ['class' => 'form-control-label']) !!}
                                    {!! Form::textarea('description', null, ['class'=>'form-control '.($errors->has('description')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'rows'=>4]) !!}
                                    @if($errors->has('description'))
                                        <div class="form-control-feedback">{{$errors->first('description')}}</div>
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

    <div id="receivableShowModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Receivable Detail</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

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
      $('#receivableShowModal').on('show.bs.modal', function (event) {
          var modal = $(this)
          var button = $(event.relatedTarget)
          var receivable = button.data('receivable')
          var status = receivable.status ? 'Received' : 'Pending';
          var currency = button.data('currency');
          var html = '<div class="row">' +
              '<div class="col-12">Currency : '+currency+'</div>' +
              '<div class="col-6">Receivable Date : '+receivable.receivable_date+'</div>' +
              '<div class="col-6">Amount : '+receivable.receivable_amount+'</div>'+
              '<div class="col-6">Status: '+status+'</div>' +
              '<div class="col-6">Received Date: '+receivable.received_date+'</div>' +
              '<div class="col-6">Received Amount: '+receivable.received_amount+'</div>' +
              '<div class="col-6">Description: <p>'+receivable.description+'</p> </div>' +
              '</div>'
          modal.find('.modal-body').html(html);
      })

      $('#receivableFormModal').on('show.bs.modal', function (event) {
          var modal = $(this)
          var button = $(event.relatedTarget)
          var receivable = button.data('receivable')
          if (receivable != undefined) {
              var url = "{{ url('case') }}/" + receivable.case_id+'/receivable/'+receivable.id;
              modal.find('form').attr('action', url);
              var method = '<input type="hidden" name="_method" value="PUT">'
              modal.find('form').append(method)
              modal.find('input[name="_method"]').val('PUT');
              modal.find('#receivable_date').val(receivable.receivable_date)
              modal.find('#receivable_amount').val(receivable.receivable_amount)
              modal.find('#received_date').val(receivable.received_date)
              modal.find('#received_amount').val(receivable.received_amount)
              modal.find('#description').val(receivable.description)
              modal.find('#currency option[value="' + receivable.currency + '"]').attr('selected', 'true')
              modal.find('button[type="submit"]').html('Update')
              modal.find('.modal-title').html('Update Case Receivable')
          } else {
              var url = "{{ route('case.receivable.store', $case->id) }}";
              modal.find('form').attr('action', url);
              modal.find('form').trigger('reset');
              modal.find('button[type="submit"]').html('Add')
              modal.find('.modal-title').html('Store Case Receivable')
              modal.find('input[name="_method"]').remove()
          }
      })
  </script>
@endsection
