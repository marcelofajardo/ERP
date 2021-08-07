@extends('layouts.app')
@section('title', 'SE Ranking Data')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">SE Ranking - Keywords</h2>
        </div>
        <form action="{{route('filteredSERankKeywords')}}" method="get" class="form-inline float-right">
            <div class="form-group">
                <div class="col-md-4 col-lg-6 col-xl-6">
                    <input name="keyword" type="text" placeholder="Keyword Search" class="form-control" value="{{!empty(request()->keyword) ? request()->keyword : ''}}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4 col-lg-6 col-xl-6">
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="row">
            @include('se-ranking.buttons-area')
            <div class="col-md-12">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Keywords</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Keyword Stats</a>
                        {{-- <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Project Tab 3</a> --}}
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Group ID</th>
                                    <th>Target URL</th>
                                    <th>First Check Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $new_items = App\Helpers::customPaginator(request(), $keywords, 25);
                                @endphp
                                @foreach ($new_items as $key => $keyword)
                                    <tr>
                                        <td>{{$keyword->name}}</td>
                                        <td>{{$keyword->group_id}}</td>
                                        <td><a href="{{!empty($keyword->link) ? url($keyword->link) : 'javascript:void(0)'}}">{{(!empty($keyword->link) ? $keyword->link : 'N/A')}}</a></td>
                                        <td>{{$keyword->first_check_date}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $new_items->links() !!}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Position Date</th>
                                    <th>Position Number</th>
                                    <th>Position Change</th>
                                    <th>Name</th>
                                    <th>Volume</th>
                                    <th>Competition</th>
                                    <th>Suggested Bid</th>
                                    <th>KEI</th>
                                    <th>Results</th>
                                    <th>Total Sum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keyword_stats as $key => $data)
                                @php
                                    $keywords_pos = App\Helpers::customPaginator(request(), $data->keywords, 25);
                                @endphp
                                    @foreach ($keywords_pos as $key => $k_data)
                                        <tr>
                                            <td>{{$k_data->id}}</td>
                                            <td>{{$k_data->positions[0]->date}}</td>
                                            <td>{{$k_data->positions[0]->pos}}</td>
                                            <td>{{$k_data->positions[0]->change}}</td>
                                            <td>{{$k_data->name}}</td>
                                            <td>{{$k_data->volume}}</td>
                                            <td>{{$k_data->competition}}</td>
                                            <td>{{$k_data->suggested_bid}}</td>
                                            <td>{{$k_data->kei}}</td>
                                            <td>{{$k_data->results}}</td>
                                            <td>{{$k_data->total_sum}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $keywords_pos->links() !!}
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Contest Name</th>
                                    <th>Date</th>
                                    <th>Award Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#">Work 1</a></td>
                                    <td>Doe</td>
                                    <td>john@example.com</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Work 2</a></td>
                                    <td>Moe</td>
                                    <td>mary@example.com</td>
                                </tr>
                                <tr>
                                    <td><a href="#">Work 3</a></td>
                                    <td>Dooley</td>
                                    <td>july@example.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection