@extends('layouts.app')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2>Instagram Processed Comments</h2>
            <p>Processed comments that have passed keyword, hashtag and location test.</p>
        </div>
        <div class="col-md-12">
            <table class="table table-striped" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>Media</th>
                        <th>Comment</th>
                        <th>Commenter Name</th>
                        <th>Commenter Username</th>
                        <th>Commenter Gender</th>
                        <th>Commenter Nationality</th>
                        <th>Post Caption</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($posts as $key=>$post)
                        @foreach($post->comments as $comment)
                            <tr>
                                <td>
                                    @if ($post->media_type == 1)
                                        <a href="{{$post->media_url[0]}}"><img src="{{ $post->media_url[0] }}" style="width: 200px;"></a>
                                    @elseif ($post['media_type'] === 2)
                                        <video controls src="{{ $post->media_url[0] }}" style="width: 200px"></video>
                                    @elseif ($post->media_type == 8)
                                        @foreach($post->media_url as $m)
                                            @if ($m['media_type'] == 1)
                                                <a href="{{$m['url']}}"><img src="{{ $m['url'] }}" style="width: 80px; margin-bottom: 2px;"></a>
                                            @elseif($m['media_type'] == 2)
                                                <video controls src="{{ $m['url'] }}" style="width: 200px"></video>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    {{ $comment->comment }}
                                </td>
                                <td>{{ $comment->name }}</td>
                                <td>
                                    <a href="https://instagram.com/{{ $comment->username }}">{{ $comment->username }}</a>
                                </td>
                                <td>
                                    {{ $comment->nationality ? $comment->nationality->gender : 'N/A' }}
                                </td>
                                <td>
                                    {{ $comment->nationality ? $comment->nationality->race : 'N/A' }}
                                </td>
                                <td> <div style="width: 200px;">
                                        {{$post->caption}}
                                    </div> </td>
                                <td>
                                    <a href="" class="btn btn-success">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a class="btn btn-info">
                                        <i class="fa fa-comment"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
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
@endsection