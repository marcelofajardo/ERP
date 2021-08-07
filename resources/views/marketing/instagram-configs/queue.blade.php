@extends('layouts.app')

@section('styles')

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h2 class="page-heading">WhatsApp Queue <?php echo $provider === 'py-whatsapp' ? "(".count($data).")" : "(".count($data["first100"]).")" ?></h2>
                    <div class="pull-left">
                        <form action="{{ route('whatsapp.config.queue', $id) }}" method="GET"
                              class="form-inline align-items-start">
                            <div class="form-group mr-3 mb-3">
                                <input name="term" type="text" class="form-control global" id="term"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="number , text, priority">
                            </div>
                            <div class="form-group ml-3">
                                <div class='input-group date' id='filter-date'>
                                    <input type='text' class="form-control global" name="date"
                                           value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date"/>

                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>

                        </form>
                        @if($provider === 'py-whatsapp')
                            @if(isset($data[0]))
                                <button onclick="deleteAllQueues('{{$data[0]['number_from']}}')" class="btn btn-danger">
                                    Delete All
                                </button>
                            @endif
                        @else
                            <a name="del_queues" href="{{route("whatsapp.config.delete_all_queues", $id)}}"
                               class="btn btn-danger">Delete All Queues</a>
                        @endif
                    </div>
                </div>
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
        <table class="table table-bordered" id="passwords-table">
            <thead>
            <tr>
                <!-- <th style="">ID</th> -->
                <th style="">Name</th>
                <th style="">Number to</th>
                <th style="">Number from</th>
                <th style="">Text</th>
                <th style="">Priority</th>
                <th style="">Marketing message type</th>
                <th style="">Send after</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if($provider === 'py-whatsapp')
                @foreach($data as $value)
                    <tr>
                        <td>{{ get_field_by_number($value->number_to,'name') }}</td>
                        <td>{{$value->number_to}}</td>
                        <td>{{$value->number_from}}</td>
                        <td>{{$value->text}}</td>
                        <td>{{$value->priority}}</td>
                        <td>{{$value->marketingMessageTypes->name}}</td>
                        <td>{{$value->send_after}}</td>
                        <td>
                            <button onclick="deleteQueue({{ $value->id }})" class="btn btn-sm">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach($data["first100"] as $value)
                    <tr>
                        <td>{{ get_field_by_number($value["chatId"],'name') }}</td>
                        <td>{{$value["chatId"]}}</td>
                        <td>{{$number}}</td>
                        <td>{{$value["body"]}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
@endsection


@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        function deleteQueue(config_id) {
            event.preventDefault();
            if (confirm("Are you sure?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('whatsapp.config.delete_queue') }}",
                    data: {"_token": "{{ csrf_token() }}", "id": config_id},
                    dataType: "json",
                    success: function (message) {
                        alert('Deleted Queue');
                        location.reload(true);
                    }, error: function () {
                        alert('Something went wrong');
                    }

                });
            }
            return false;

        }

        function deleteAllQueues(config_id) {
            event.preventDefault();
            if (confirm("Are you sure?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('whatsapp.config.delete_all') }}",
                    data: {"_token": "{{ csrf_token() }}", "id": config_id},
                    dataType: "json",
                    success: function (message) {
                        alert('Deleted All Queues');
                        window.location.href = "/marketing/whatsapp-config";
                    }, error: function () {
                        alert('Something went wrong');
                    }

                });
            }
            return false;

        }

    </script>
@endsection