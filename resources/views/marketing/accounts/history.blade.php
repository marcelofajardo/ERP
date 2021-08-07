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
                    <h2 class="page-heading">WhatsApp History</h2>
                    <div class="pull-left">
                        <form action="{{ route('whatsapp.config.history', $id) }}" method="GET"
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
                <th style="">Number to</th>
                <th style="">Number from</th>
                <th style="">Text</th>
                <th style="">Priority</th>
                <th style="">Marketing message type</th>
                <th style="">Send after</th>
                <th>Sent at</th>
            </tr>
            </thead>
            <tbody>
            @if($provider === 'py-whatsapp')
                @foreach($data as $value)
                    <tr>
                        <td>{{$value->number_to}}</td>
                        <td>{{$value->number_from}}</td>
                        <td>{{$value->provider}}</td>
                        <td>{{$value->freq}}</td>
                        <td>{{$value->text}}<br><img src="{{$value->image}}" alt="" width="100px"></td>
                        <td>{{$value->priority}}</td>
                        <td>{{$value->marketing_message_type_id}}</td>
                        <td>{{$value->send_after}}</td>
                        <td>{{$value->sent_at}}</td>
                        <td></td>
                    </tr>
                @endforeach
            @else
                @foreach($data['messages'] as $value)
                    <tr>
                        <td>{{$value["chatId"]}}</td>
                        <td>{{$number}}</td>
                        <td>{{$value["body"]}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

            @endif
            </tbody>
        </table>
    </div>
@endsection


@section('scripts')

@endsection