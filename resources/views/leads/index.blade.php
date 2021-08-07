@extends('layouts.app')

@section('favicon' , 'lead.png')

@section('title', 'Leads List')


@section("styles")

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">

@endsection

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <h2 class="page-heading">Leads</h2>

            <div class="row">

                <div class="col-md-10 col-sm-12">



                <form action="/leads/" method="GET" class="form-inline align-items-start" id="searchForm">

                    <div class="row full-width" style="width: 100%;">

                        <div class="col-md-3 col-sm-12">

                            <div class="form-group mr-3">

                                {{-- <div class="row"> --}}

                                {{-- <div class="col-md-8 pr-0"> --}}

                                <input type="hidden" name="type" value="{{ $type ? 'multiple' : '' }}">

                                <input name="term" type="text" class="form-control"

                                       value="{{ isset($term) ? $term : '' }}"

                                       placeholder="Search">

                                {{-- </div>

                                <div class="col-md-4 pl-0"> --}}

                                {{-- </div> --}}

                                {{-- </div> --}}

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group mr-3">

                                @php $brands = \App\Brand::getAll(); @endphp

                                {!! Form::select('brand[]',$brands, (isset($brand) ? $brand : ''), ['data-placeholder' => 'Select a Brand','class' => 'form-control select-multiple', 'multiple' => true]) !!}

                            </div>

                        </div>

                        <div class="col-md-3 col-sm-12">

                          <div class="form-group">

                            <select name="rating[]" class="form-control select-multiple" multiple data-placeholder="Select Rating">

                              <option value="1" {{ isset($rating) && in_array(1, $rating) ? 'selected' : '' }}>1</option>

                              <option value="2" {{ isset($rating) && in_array(2, $rating) ? 'selected' : '' }}>2</option>

                              <option value="3" {{ isset($rating) && in_array(3, $rating) ? 'selected' : '' }}>3</option>

                              <option value="4" {{ isset($rating) && in_array(4, $rating) ? 'selected' : '' }}>4</option>

                              <option value="5" {{ isset($rating) && in_array(5, $rating) ? 'selected' : '' }}>5</option>

                              <option value="6" {{ isset($rating) && in_array(6, $rating) ? 'selected' : '' }}>6</option>

                              <option value="7" {{ isset($rating) && in_array(7, $rating) ? 'selected' : '' }}>7</option>

                              <option value="8" {{ isset($rating) && in_array(8, $rating) ? 'selected' : '' }}>8</option>

                              <option value="9" {{ isset($rating) && in_array(9, $rating) ? 'selected' : '' }}>9</option>

                              <option value="10" {{ isset($rating) && in_array(10, $rating) ? 'selected' : '' }}>10</option>

                            </select>

                          </div>

                        </div>
                    </div>
                    <div class="row full-width" style="width: 100%; padding-top: 5px; ">
                        <div class="col-md-3">
                          <div class="form-group">
                            <?php echo $category_select; ?>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <?php echo Form::select("status",["" => "-- Select status --"] + $status,request()->get("status"),["class" => "form-control"]); ?>
                          </div>
                        </div>
                        <div class="col-md-2"><button type="submit" class="btn btn-image"><img src="/images/search.png" /></button></div>
                    </div>  

                </form>

            </div>

                <div class="col-md-2 col-sm-12 text-right">

                <a class="btn btn-secondary" href="{{ route('leads.create') }}">+</a>

            </div>

            </div>

        </div>

    </div>



    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif



    <div class="productGrid" id="productGrid">

      @include('leads.lead-item')

    </div>



    @if ($type)

      <div class="row">

        <div class="col-xs-12 text-center">

          <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#messageModal">Send Messages</button>



          <div id="messageModal" class="modal fade" role="dialog">

            <div class="modal-dialog">



              <!-- Modal content-->

              <div class="modal-content">

                <div class="modal-header">

                  <h4 class="modal-title">Create Messages</h4>

                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>



                <form action="{{ url('whatsapp/sendMultipleMessages') }}" method="POST" enctype="multipart/form-data" id="messageForm">

                  @csrf



                  <input type="hidden" name="selected_leads" value="" id="leads-array">

                  <div class="modal-body">

                    <div class="form-group">

                        <strong>Message:</strong>

                         <textarea class="form-control" name="message" placeholder="Task Subject" required></textarea>

                         @if ($errors->has('message'))

                             <div class="alert alert-danger">{{$errors->first('message')}}</div>

                         @endif

                    </div>

                  </div>

                  <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-secondary">Create</button>

                  </div>

                </form>

              </div>



            </div>

          </div>

        </div>

      </div>

    @endif



    {{--{!! $leads->links() !!}--}}



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

    <script type="text/javascript">

      $(document).on('click', '.change_message_status', function(e) {

        e.preventDefault();

        var url = $(this).data('url');

        var thiss = $(this);

        var type = 'GET';



        if ($(this).hasClass('approve-whatsapp')) {

          type = 'POST';

        }



          $.ajax({

            url: url,

            type: type,

            beforeSend: function() {

              $(thiss).text('Loading');

            }

          }).done( function(response) {

            $(thiss).closest('tr').removeClass('row-highlight');

            $(thiss).prev('span').text('Approved');

            $(thiss).remove();

          }).fail(function(errObj) {

            alert("Could not change status");

          });

      });



      $(document).on('click', '.pagination a, th a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');



        getProducts(url);

      });



      function getProducts(url) {

        $.ajax({

          url: url

        }).done(function(data) {

          $('#productGrid').html(data.html);

        }).fail(function(response) {

          console.log(response);

          alert('Error loading more products');

        });

      }



      $('#searchForm').on('submit', function(e) {

        e.preventDefault();



        var url = "{{ route('leads.index') }}";

        var formData = $('#searchForm').serialize();



        $.ajax({

          url: url,

          data: formData

        }).done(function(data) {

          $('#productGrid').html(data.html);

          // $('.pagination-container').empty();

          // $('.pagination-container').html(data.pagination);

          console.log(data);

        }).fail(function(data) {

          console.log(data);

          alert('Error searching for products');

        });

      });



      var attached_leads = [];



      $(document).on('click', '.check-lead', function() {

        var id = $(this).data('leadid');



        if ($(this).prop('checked') == true) {

          // $(this).data('attached', 1);

          attached_leads.push(id);

        } else {

          var index = attached_leads.indexOf(id);



          // $(this).data('attached', 0);

          attached_leads.splice(index, 1);

        }



        console.log(attached_leads);

      });



      $('#messageForm').on('submit', function(e) {

        e.preventDefault();



        if (attached_leads.length == 0) {

          alert('Please select some leads');

        } else {

          $('#leads-array').val(JSON.stringify(attached_leads));

          $('#messageForm')[0].submit();

        }

      });



      $(document).ready(function() {



         $(".select-multiple").select2();

      });

    </script>



@endsection

