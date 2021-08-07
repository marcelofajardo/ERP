@extends('layouts.app')



@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">{{ $title }} (<span id="total-count">{{ $missingBrands->total() }}</span>)</h2>
            <div class="pull-left">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="Search" id="term">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control select2" id="select">
                                    <option value="">Select Scrapper</option>
                                    @foreach($scrapers as $scraper)
                                        <option value="{{ $scraper['supplier'] }}">{{ $scraper['supplier'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <button type="button" class="form-control" onclick="filterResults()">Search</button>
                        </div>
                    </div>
            </div>
            <div class="pull-right">
                <button class="btn btn-secondary" onclick="automaticMerge()">Automatic Merge</button>
                <a href="javascript:;" class="create-multi-reference">Reference</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

   <div class="table-responsive mt-3">
        <table class="table table-bordered" id="brand-table">
            <thead>
            <tr>
                <th><input type="checkbox" class="select_all" name="select_all">&nbsp;ID</th>
                <th>Brand</th>
                <th>Supplier</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @include('missingbrand.partial.data')
            </tbody>
            {!! $missingBrands->render() !!}
        </table>
    </div>

<div id="create-brand-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Brand</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo route('missing-brands.store'); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Name</strong>
                            <input type="hidden" class="form-control brand-name-id" name="id">
                            <input type="text" class="form-control brand-name-field" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary save-brand-btn">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="reference-brand-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Assign Reference</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo route('missing-brands.reference'); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" class="form-control brand-name-id" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Brand</strong>
                            <input type="text" class="form-control brand-name-field" name="name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Brand</strong>
                            <?php echo Form::select("brand",\App\Brand::pluck('name','id')->toArray(),null,['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary save-brand-btn">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="multi-reference-brand-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Assign Reference</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo route('missing-brands.multi-reference'); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <strong>Brand</strong>
                            <?php echo Form::select("brand",\App\Brand::pluck('name','id')->toArray(),null,['class' => 'form-control mul-brand-field']); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary save-multi-brand-refer-btn">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    
    $('#select').select2({
        width: "100%"
    });

    $(document).on("click",".create-brand",function() {
        $(".brand-name-field").val($(this).data('name'));
        $(".brand-name-id").val($(this).data('id'));
        $("#create-brand-modal").modal("show");
    });

    $(document).on("click",".create-reference",function() {
        $(".brand-name-field").val($(this).data('name'));
        $(".brand-name-id").val($(this).data('id'));
        $("#reference-brand-modal").modal("show");
    });

    function filterResults() {
         term = $('#term').val();
         select = $('#select').val();
         $.ajax({
            type: 'GET',
            url: "/missing-brands",
            data: {
                term : term,
                select : select,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            $("#brand-table tbody").empty().html(data.tbody);
            $("#total-count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(response) {
            alert('No response from server');
        });
    }  

    $(document).on("click",".select_all",function() {
        $(".multi-brand-ref").click();
    });

    $(document).on("click",".create-multi-reference",function() {
        $("#multi-reference-brand-modal").modal("show");
    });

    $(document).on("click" , ".save-multi-brand-refer-btn", function(e) {
        e.preventDefault();
        var ids = [];
        var checkedFields = $(".multi-brand-ref:checked");
        if(checkedFields.length > 0) {
            $.each(checkedFields,function(k,v) {
                ids.push($(v).val());
            });
        }else{
            alert("Please select brands");
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "/missing-brands/multi-reference",
            data: {
                brand  : $(".mul-brand-field").val(),
                ids : ids,
                _token : "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            toastr["success"]("Request sent successfully");
            location.reload();
        }).fail(function(response) {
            alert('No response from server');
        });
    });

    function automaticMerge() {
        $.ajax({
            type: 'POST',
            url: "/missing-brands/automatic-merge",
            data: {
                _token : "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            toastr["success"](data);
            //location.reload();
        }).fail(function(data) {
            toastr["error"](data);
        });
    }

</script>




@endsection


