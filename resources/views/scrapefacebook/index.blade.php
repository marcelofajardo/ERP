@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Facebook Influencers</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
        </div>
        <div class="col-md-12">
            <table id="table" class="table-striped table-bordered table table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Bio</th>
                        <th>Keyword</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scrapeFacebookUsers as $key=>$users)
                        <tr>
                            <td>
                                <a href="{{ $users->url }}">
                                    {{ $users->owner ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $users->bio }}</td>
                            <td>{{ $users->keyword }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
