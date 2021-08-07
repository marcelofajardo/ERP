@extends('layouts.app')
@section('title', 'SE Ranking Data')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">SE Ranking - Audit Report</h2>
        </div>
        {{-- <form action="{{route('filteredAnalyticsResults')}}" method="get" class="form-inline float-right">
            <div class="form-group">
                <div class="col-md-4 col-lg-6 col-xl-6">
                    <input name="location" type="text" placeholder="City/Country" class="form-control" value="{{!empty(request()->location) ? request()->location : ''}}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4 col-lg-6 col-xl-6">
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </div>
            </div>
        </form> --}}
    </div>
    <div class="container">
        <div class="row">
            @include('se-ranking.buttons-area')
            <div class="col-md-12">
                <table class="table table-responsive" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Finished</th>
                            <th>AlexaRank</th>
                            <th>ArchiveOrg</th>
                            <th>Backlinks</th>
                            <th>Expdate</th>
                            <th>Index Bing</th>
                            <th>Index Google</th>
                            <th>Index Yahoo</th>
                            <th>Index Yandex</th>
                            <th>IP</th>
                            <th>Ip Country</th>
                            <th>MOZ DomainAuthority</th>
                            <th>AvgLoadSpeed</th>
                            <th>Score Percent</th>
                            <th>Total Pages</th>
                            <th>Total Warnings</th>
                            <th>Total Errors</th>
                            <th>Total Passed</th>
                            <th>Screenshot</th>
                            <th>Audit Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{($audit->is_finished = 1 ? 'True' : 'False')}}</td>
                            <td>{{$audit->domain_props->AlexaRank}}</td>
                            <td>{{$audit->domain_props->archiveOrg}}</td>
                            <td>{{$audit->domain_props->backlinks}}</td>
                            <td>{{$audit->domain_props->expdate}}</td>
                            <td>{{$audit->domain_props->index_bing}}</td>
                            <td>{{$audit->domain_props->index_google}}</td>
                            <td>{{$audit->domain_props->index_yahoo}}</td>
                            <td>{{$audit->domain_props->index_yandex}}</td>
                            <td>{{$audit->domain_props->ip}}</td>
                            <td>{{$audit->domain_props->IpCountry}}</td>
                            <td>{{$audit->domain_props->mozDomainAuthority}}</td>
                            <td>{{$audit->domain_props->avgLoadSpeed}}</td>
                            <td>{{$audit->score_percent}}%</td>
                            <td>{{$audit->total_pages}}</td>
                            <td>{{$audit->total_warnings}}</td>
                            <td>{{$audit->total_errors}}</td>
                            <td>{{$audit->total_passed}}</td>
                            <td>{{$audit->screenshot}}</td>
                            <td>{{$audit->audit_time}}</td>
                        </tr>
                    </tbody>
                </table>
                {{-- <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Project Tab 1</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Project Tab 2</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Project Tab 3</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Employer</th>
                                    <th>Time</th>
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
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
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
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection