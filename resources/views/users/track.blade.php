@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> User Actions</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ERP Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Mouse Clicks & Keystrokes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Other Tabs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Page</th>
                            <th>Action</th>
                            <th>Data</th>
                            <th>Time</th>
                        </tr>
                        @foreach($tracks as $track)
                            @foreach($track->log()->orderBy('created_at', 'DESC')->get() as $log)
                                @if ($log->routePath()->count())
                                    @if (!in_array($log->routePath->route->name, ['pushNotifications', 'track.store', 'track.show', 'dailyActivity.get', '/whatsapp/pollMessagesCustomer']))
                                        <tr>
                                            <td>
                                                <?php
                                                    $action = $log->routePath->route->action;
                                                    $a = explode("\\", $action);
                                                    $action = $a[count($a)-1];
                                                    $params = $log->routePath->parameters()->get()->pluck('value')->toArray();

                                                ?>
                                                <a href="{{ action($action, count($params) ? $params : '') }}">Visit Page ({{ $log->routePath->route->name }})</a>
                                            </td>
                                            <td>
                                                {{ $routeActions[$log->routePath->route->name] ?? $log->routePath->route->name }}
                                            </td>
                                            <td>
                                                @if(isset($models[$log->routePath->route->name]))
                                                    @php $item = ($models[$log->routePath->route->name])->where('id', $log->routePath->parameters()->first()->value)->first()->toArray() @endphp
                                                    @foreach($item as $k=>$itemx)
                                                        <li>{{$k}} : {{ $itemx }}</li>
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                {{ $log->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>
                                Date
                            </th>
                            <td>
                                Action
                            </td>
                            <td>
                                Page
                            </td>
                            <td>
                                Description
                            </td>
                        </tr>
                        @foreach($actions as $action)
                            <tr>
                                <td>
                                    {{ $action->date }} ({{$action->created_at->diffForHumans()}})
                                </td>
                                <td>
                                    <span class="tag tag-info">
                                        @if ($action->action == 'click_a')
                                            CLICKED LINK
                                        @elseif ($action->action == 'key')
                                            KEYSTROKES
                                        @elseif($action->action == 'click_button')
                                            CLICKED BUTTON
                                        @elseif($action->action == 'click_img')
                                            CLICKED IMAGE
                                        @elseif($action->action == 'click_select')
                                            CLICKED SELECT
                                        @elseif($action->action == 'click_input')
                                            CLICKED Input
                                        @elseif($action->action == 'click_div')
                                            CLICKED A SECTION
                                        @else
                                            {{ $action->action }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ $action->page }}">{{ $action->page }}</a>
                                </td>
                                <td>
                                    @if ($action->action == 'click_a')
                                        <a href="{{ $action->details }}">{{ $action->details }}</a>
                                    @elseif ($action->action == 'key')
                                        {{ substr($action->details, 0 , 500) }}
                                    @elseif($action->action == 'click_button')
                                        {{ substr($action->details, 0 , 500) }}
                                    @elseif($action->action == 'click_img')
                                        <img style="width: 250px;" src="{{ $action->details }}" alt="{{ $action->details }}">
                                    @elseif($action->action == 'click_select')
                                        {{ substr($action->details, 0 , 500) }}
                                    @elseif($action->action == 'click_input')
                                        {{ substr($action->details, 0 , 500) }}
                                    @elseif($action->action == 'click_div')
                                        {{ substr($action->details, 0 , 500) }}
                                    @else
                                        {{ substr($action->details, 0 , 500) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                </div>
            </div>
        </div>
    </div>
@endsection
