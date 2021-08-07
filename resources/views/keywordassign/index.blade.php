@extends('layouts.app')

@section('link-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')



<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Keyword Assign Task</h2>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('keywordassign.create') }}">+</a>
        </div>
    </div>
    <div class="col-lg-12">
        &nbsp;
    </div>
</div>

<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="width: 99%" id="keywordassign_table">
            <thead>
                <tr>
                    <th width="15%">Keyword</th>
                    <th width="20%">Task Category</th>
                    <th>Task Description</th>
                    <th>Assign to Defualt</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($keywordassign as $row_keyword) { ?>
                    <tr>
                        <td>
                            <?php
                            echo $row_keyword->keyword;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row_keyword->title;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row_keyword->task_description;
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $row_keyword->name;
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo URL::to('/');?>/keywordassign/<?php echo $row_keyword->id;?>"><i class="fa fa-edit"></i></a>&nbsp;
                            <a href="<?php echo URL::to('/');?>/keywordassign/<?php echo $row_keyword->id;?>/destroy"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>  
                <?php } 
                ?>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
    /* beautify preserve:start */
    @if($errors->any())
    $('#couponModal').modal('show');
    @endif
    /* beautify preserve:end */
    $(document).ready(function() {
        $('#start').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#expiration').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#keywordassign_table').DataTable({});
        $('.dataTables_length').addClass('bs-select');

        $('input#report-date').daterangepicker();

        $('input#report-date').on('apply.daterangepicker', function(ev, picker) {
            const couponId = $('#couponReportModal').attr('data-coupon-id');
            getReport(couponId);
        });
    });

</script>
@endsection