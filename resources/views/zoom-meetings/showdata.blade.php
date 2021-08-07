@extends('layouts.app')

@section('title', 'Meetings')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">

    {{-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

    <style>
        #message-wrapper {
            height: 450px;
            overflow-y: scroll;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 text-center">
            <h2 class="page-heading">{{ ucfirst($type) }} Meetings</h2>
        </div>
        <div class="container">
            <form action="{{ action('Meeting\ZoomMeetingController@showData') }}" method="GET" class="form-inline">
                <div class="form-group ml-3">
                    <select class="form-control" name="type" required>
                        <option value="">Select User Type</option>
                        <option value="vendor" {{ isset($type) && $type == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="supplier" {{ isset($type) && $type == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="customer" {{ isset($type) && $type == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-image"><img src="{{ asset('/images/filter.png')}}"/></button>
            </form>
        </div>
    </div>
    <div class="clearboth"></div>
    <div id="exTab2" class="container" style="overflow: auto">
        <ul class="nav nav-tabs">
            <li class="active">
                <a  href="#1" data-toggle="tab">Upcoming Meetings</a>
            </li>
            <li><a href="#2" data-toggle="tab">Past Meetings</a>
            </li>
        </ul>
        <div class="tab-content ">
            <!-- Upcoming Meetings div start -->
            <div class="tab-pane active" id="1">
                <div class="row">
                    <!-- <h4>List Of Upcoming Meetings</h4> -->
                    <div class="infinite-scroll">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Start Date Time</th>
                                <th width="10%">Meeting Id</th>
                                <th width="10%" class="category">Meeting Topic</th>
                                <th width="15%">Meeting Agenda</th>
                                <th width="25%">Join Meeting URL</th>
                                <th width="20%">Meeting Duration</th>
                                <th width="10%">Vendor Name</th>
                                <th width="10%">Vendor Email</th>
                                <th width="10%">Vendor Phone</th>
                                <th width="10%">Vendor Whatsapp Number</th>
                                <th width="25%">Start Meeting URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( $pastMeetings )
                                @foreach($upcomingMeetings as $meetings)
                                    <tr>
                                        <td class="p-2">{{ $meetings->id }}</td>
                                        <td class="p-2">{{ Carbon\Carbon::parse($meetings->start_date_time)->format('M, d-Y H:i') }}</td>
                                        <td class="p-2">{{ $meetings->meeting_id }}</td>
                                        <td class="p-2">{{ $meetings->meeting_topic }}</td>
                                        <td class="p-2">{{ $meetings->meeting_agenda }}</td>
                                        <td class="p-2"><a href="{{ $meetings->join_meeting_url }}" target="_blank">{{ $meetings->join_meeting_url }}</a></td>
                                        <td class="p-2">{{ $meetings->meeting_duration }} mins</td>
                                        <td class="p-2">{{ $meetings->name }}</td>
                                        <td class="p-2">{{ $meetings->email }}</td>
                                        <td class="p-2">{{ $meetings->phone }}</td>
                                        <td class="p-2">{{ $meetings->whatsapp_number }}</td>
                                        <td class="p-2" width="20%"><a href="{{ $meetings->start_meeting_url }}" target="_blank">{{ 'Link' }}</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- Upcoming Meetings div end -->
            <!-- Past Meetings div start -->
            <div class="tab-pane" id="2">
                <div class="row">
                    <div class="col-12">
                        <!-- <h4>Statutory Activity Completed</h4> -->
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Start Date Time</th>
                                <th width="10%">Meeting Id</th>
                                <th width="10%" class="category">Meeting Topic</th>
                                <th width="15%">Meeting Agenda</th>
                                <th width="25%">Join Meeting URL</th>
                                <th width="20%">Meeting Duration</th>
                                <th width="10%">Recording</th>
                                <th width="10%">Vendor Name</th>
                                <th width="10%">Vendor Email</th>
                                <th width="10%">Vendor Phone</th>
                                <th width="10%">Vendor Whatsapp Number</th>
                                <th width="25%">Start Meeting URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( $pastMeetings )
                                @foreach($pastMeetings as $pastmeetings)
                                    <tr>
                                        <td class="p-2">{{ $pastmeetings->id }}</td>
                                        <td class="p-2">{{ Carbon\Carbon::parse($pastmeetings->start_date_time)->format('M, d-Y H:i') }}</td>
                                        <td class="p-2">{{ $pastmeetings->meeting_id }}</td>
                                        <td class="p-2">{{ $pastmeetings->meeting_topic }}</td>
                                        <td class="p-2">{{ $pastmeetings->meeting_agenda }}</td>
                                        <td class="p-2"><a href="{{ $pastmeetings->join_meeting_url }}" target="_blank">{{ $pastmeetings->join_meeting_url }}</a></td>
                                        <td class="p-2">{{ $pastmeetings->meeting_duration }} mins</td>
                                        <th width="10%">
                                            <video width="220" height="220" controls>
                                                <source src="{{ asset('zoom/0/'.$pastmeetings->id.'/'.$pastmeetings->zoom_recording) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video><br><br>
                                            <audio src="{{ asset('zoom/0/'.$pastmeetings->id.'/'.$pastmeetings->meeting_id.'-audio.mp4') }}" controls preload="metadata">
                                                <p>Alas, your browser doesn't support html5 audio.</p>
                                            </audio>
                                        </th>
                                        <td class="p-2">{{ $pastmeetings->name }}</td>
                                        <td class="p-2">{{ $pastmeetings->email }}</td>
                                        <td class="p-2">{{ $pastmeetings->phone }}</td>
                                        <td class="p-2">{{ $pastmeetings->whatsapp_number }}</td>
                                        <td class="p-2" width="20%"><a href="{{ $pastmeetings->start_meeting_url }}" target="_blank">Link</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-12">
                        <h4>All Statutory Activity List</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th class="category">Category</th>
                                    <th>Task Details</th>
                                    <th>Assigned From</th>
                                    <th>Assigned To</th>
                                    <th>Recurring Type</th>
                                    <th>Remarks</th>
                                    <th>Completed</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach(  $data['task']['statutory'] as $task)
                                    <tr>
                                        <td>{{ $task['id'] }}</td>
                                        <td> {{ Carbon\Carbon::parse($task['created_at'])->format('d-m H:i') }}</td>
                                        <td> {{ isset( $categories[$task['category']] ) ? $categories[$task['category']] : '' }}</td>
                                        <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">{{ $task['task_subject'] ? $task['task_subject'] : 'Task Details' }}</td>
                                        <td>{{ $users[$task['assign_from']]}}</td>
                                        <td>
                                          {{ $task['assign_to'] ?? ($users[$task['assign_to']] ? $users[$task['assign_to']] : 'Nil') }}
                                        </td>
                                        <td>{{ $task['recurring_type'] }}</td>
                                        <td> @include('task-module.partials.remark',$task) </td>
                                        <td>
                                          @if( Auth::id() == $task['assign_to'] )
                                            @if ($task['completion_date'])
                                              {{ Carbon\Carbon::parse($task['completion_date'])->format('d-m H:i') }}
                                            @else
                                              <a href="/statutory-task/complete/{{$task['id']}}">Complete</a>
                                            @endif
                                          @endif
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> --}}
            </div>
            <!-- Past Meetings div end -->
        </div>
    </div>



@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script> --}}
@endsection