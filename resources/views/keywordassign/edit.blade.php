@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Update Keyword Assign</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('keywordassign.index') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="<?php echo URL::to('/');?>/keywordassign/<?php echo $keywordassign[0]->id;?>/update/" method="POST">
    @csrf
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="keyword" required="" class="form-control" id="keyword" value="<?php echo $keywordassign[0]->keyword;?>" />
        </div>
        <div class="form-group">
            <strong>Task Category:</strong>
            <select name="task_category" required="" class="form-control" id="task_category">
                <option value="">Select Task Category</option>
                <?php
                foreach ($task_category as $category_row) { ?>
                    <option <?php if($category_row->id == $keywordassign[0]->task_category) echo 'SELECTED';?> value="<?php echo $category_row->id;?>"><?php echo $category_row->title;?></option>
                <?php } 
                ?>
            </select>
             <a class="btn btn-secondary" id="addNewTaskCategory" href="javascript:;">+</a>
        </div>
        <div class="form-group">
            <strong>Task Description:</strong>
            <textarea class="form-control" required="" name="task_description" id="task_description"><?php echo $keywordassign[0]->task_description;?></textarea>
        </div>
        <div class="form-group">
            <strong>Assign To:</strong>
            <select name="assign_to" required="" class="form-control" id="assign_to">
                <option value="">Select Users</option>
                <?php
                foreach ($userslist as $row_users) { ?>
                    <option <?php if($row_users->id == $keywordassign[0]->assign_to) echo 'SELECTED';?> value="<?php echo $row_users->id;?>"><?php echo $row_users->name;?></option>
                <?php } 
                ?>
            </select>
        </div>
        <div class="cls_btn_box">
            <button type="submit" class="btn btn-secondary">Submit</button>
        </div>
    </div>
    
</div>
</form>

<!-- COUPON DETAIL MODAL -->
<div class="modal fade" id="taskCategoryModal" tabindex="-1" role="dialog" aria-labelledby="taskCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="taskcategory-form" method="POST" action="{{ route('keywordassign.taskcategory') }}" onsubmit="return executeCouponOperation();">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="taskCategoryModalLabel">New Task Cateegory</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group row">
                        <label for="code" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" required="" name="task_category_name" placeholder="Task Category Name" value="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    /* beautify preserve:end */
    $(document).ready(function() {
        $("#addNewTaskCategory").click(function(){
            $("#taskCategoryModal").modal("show");
        });
    });

    function executeCouponOperation() {

        const formActionUrl = $('#taskcategory-form').attr('action');
        $.ajax({
                method: "POST",
                url: formActionUrl,
                dataType: "json",
                data: {
                    _token: $('#taskcategory-form input[name="_token"]').val(),
                    task_category_name: $('#taskcategory-form input[name="task_category_name"]').val(),
                }
            })
            .done(function(scdata) {
                $('#task_category').append($("<option></option>").attr("value", scdata.data.id).text(scdata.data.Category)); 
                $("#taskCategoryModal").modal("hide");
            })
            .fail(function(response) {
                
            });
        return false;
    }
</script>
@endsection