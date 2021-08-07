@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'List | Message Counter')

@section('styles')

{{--    data tables--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">--}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
{{--end datatables--}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('content')



    <div class="row" id="message-queue-page">

        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Message Counter</h2>
        </div>



        <br>


        <div class="container-fluid">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Number</th>
                    <th>Counts</th>
                    <th>Date-Time</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($messageCounter))

                    @foreach($messageCounter as $list)

                        <tr>
                            <td>{{$list->id}}</td>
                            <td>{{$list->number}}</td>
                            <td>{{$list->counter}}</td>
                            <td>{{$list->time}}</td>
                        </tr>
                    @endforeach
                @endif

                </tbody>
            </table>
        </div>


    </div>




@endsection

@section('scripts')

{{--    data tables--}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
{{--datatable ends--}}



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="/js/jsrender.min.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script type="text/javascript" src="/js/common-helper.js"></script>
    <script type="text/javascript" src="/js/message-queue.js"></script>
    <script type="text/javascript">
        msQueue.init({
            bodyView : $("#message-queue-page"),
            baseUrl : "<?php echo url("/"); ?>"
        });
    </script>
@endsection


