@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Instagram Automated Messages</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form enctype="multipart/form-data" action="{{ action('BrandTaggedPostsController@store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="account_id">Account</label>
                    <select class="form-control" name="account_id" id="account_id">
                        <?php $accounts = \App\Account::where('platform', 'instagram')->get(); ?>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="receipts">Receipts</label>
                    <select class="form-control" style="height: 300px;" multiple name="receipts[]" id="receipts">
                        @foreach($posts as $post)
                            <option value="{{ $post->username }}">{{ $post->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" name="message" id="message" cols="30" rows="10" placeholder="Type message..."></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-info">Send Message</button>
                </div>
            </form>
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