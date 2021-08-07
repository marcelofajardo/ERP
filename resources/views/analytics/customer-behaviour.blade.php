@extends('layouts.app')
@section('title', 'Analytics Data')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Customer Behaviour By Page</h2>
    </div>
    <div class="col-lg-12 margin-tb">
        <div class="col-md-4 col-lg-4 col-xl-4">
            <form action="{{route('filteredcustomerBehaviourByPage')}}" method="get" class="form-inline">
                <div class="form-group">
                    <select name="page" aria-placeholder="Select Page">
                        @foreach ($pages as $key => $name)
                            <option value="{{$name}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="container-fluid">
        @php
            $new_data = App\Helpers::customPaginator(request(), $data, 100);
            @endphp
        <div class="col-md-12 text-center">
            {!! $new_data->links() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Page</th>
                        <th scope="col" class="text-center">Page Views</th>
                        <th scope="col" class="text-center">Unique Page Views</th>
                        <th scope="col" class="text-center">Avg. Time on Page</th>
                        <th scope="col" class="text-center">Entrances</th>
                        <th scope="col" class="text-center">Bounce Rate</th>
                        <th scope="col" class="text-center">%Exit</th>
                        <th scope="col" class="text-center">Page Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($new_data as $key => $item)
                    @php
                        if (!empty(request()->page)) {
                            $page = $item['pages'];
                        } else {
                            $page = $item['page_path'];
                        }
                        // DB::table('analytics_customer_behaviours')->insert(
                        // [
                        //     "pages" => $item['page_path'], 
                        //     "pageviews" => $item['pageviews'],
                        //     "uniquePageviews" => $item['uniquePageviews'],
                        //     "avgTimeOnPage" => $item['avgTimeOnPage'],
                        //     "entrances" => $item['entrances'],
                        //     "bounceRate" => $item['bounceRate'],
                        //     "exitRate" => $item['exitRate'],
                        //     "pageValue" => $item['pageValue']
                        // ]
                    // );
                    @endphp
                   
                    <tr>
                        <td>{{$page}}</td>
                        <td>{{$item['pageviews']}}</td>
                        <td>{{$item['uniquePageviews']}}</td>
                        <td>{{$item['avgTimeOnPage']}}</td>
                        <td>{{$item['entrances']}}</td>
                        <td>{{$item['bounceRate']}}</td>
                        <td>{{$item['exitRate']}}%</td>
                        <td>{{$item['pageValue']}}</td>
                    </tr>
                    @endforeach
                    {{-- @php
                        die;
                    @endphp --}}
                </tbody>
            </table>
            <div class="col-md-12 text-center">
                {!! $new_data->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection