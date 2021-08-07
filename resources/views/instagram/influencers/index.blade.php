@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Instagram Influencers</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
            <form method="post" action="{{ action('InfluencersController@store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Username (without @)</label>
                            <input type="text" name="name" id="name" placeholder="sololuxuryindia (without @)" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand_name">Brand Name</label>
                            <input type="text" name="brand_name" id="brand_name" placeholder="Brand Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="blogger">Blogger</label>
                            <input type="text" name="blogger" id="blogger" placeholder="Blogger Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" placeholder="City" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Add?</label>
                            <button class="btn-block btn btn-success">Add Influencer</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table id="table" class="table-striped table table-sm">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Username</th>
                        <th>Brand Name</th>
                        <th>Blogger</th>
                        <th>First Post</th>
                        <th>Second Post</th>
                        <th>City</th>
                        <th>deals</th>
                        <th>Link first Post</th>
                        <th>Link second Post</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hashtags as $key=>$hashtag)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <a href="https://instagram.com/{{$hashtag->username}}">
                                    {{ $hashtag->username ?? 'N/A' }}
                                </a>
                            </td>
                            <td>{{ $hashtag->brand_name }}</td>
                            <td>{{ $hashtag->blogger }}</td>
                            <td>{{ $hashtag->first_post }}</td>
                            <td>{{ $hashtag->second_post }}</td>
                            <td>{{ $hashtag->city }}</td>
                            <td>{{ $hashtag->deals }}</td>
                            <td>{{ $hashtag->list_first_post }}</td>
                            <td>{{ $hashtag->list_second_post }}</td>
                            {{--                        <td>{{ $hashtag->rating }}</td>--}}
                            <td>
                                <form method="post" action="{{ action('InfluencersController@destroy', $hashtag->id) }}">
                                    {{--                                <a class="btn btn-info" href="{{ action('HashtagController@showGrid', $hashtag->id) }}">--}}
                                    {{--                                    <i class="fa fa-eye"></i>--}}
                                    {{--                                </a>--}}
                                    {{--                                <a class="btn btn-info" href="{{ action('HashtagController@edit', $hashtag->hashtag) }}">--}}
                                    {{--                                    <i class="fa fa-info"></i>--}}
                                    {{--                                </a>--}}
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style>
        thead input {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table thead tr').clone(true).appendTo( '#table thead' );
            $('#table thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
            var table = $('#table').dataTable({
                orderCellsTop: true,
                fixedHeader: true
            });
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection

@section('scripts')
    <script>
        var cid = null;
        $(function(){
            $('.show-details').on('click',function() {
                let id = $(this).attr('data-pid');
                $('.reveal-'+id).slideToggle('slow');
            });

            $('.card-reveal .close').on('click',function(){
                $(this).parent().slideToggle('slow');
            });

        });
    </script>
@endsection