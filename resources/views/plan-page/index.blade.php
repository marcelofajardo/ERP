@extends('layouts.app')


@section('title', 'Plans')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
@section('content')

<style>

.edit-plan{background: transparent;color: #000;border:0;padding:10px 5px 5px 10px;font-size:15px;}
.delete-btn{padding:7px 5px 5px;border:0;}
.edit-plan:hover ,.add-sub-plan:hover { background: transparent; color: #000 !important; border: 0; }
.add-sub-plan {background: transparent;color: #000;border:0;font-size:20px;padding:10px 5px 5px;}
.edit-plan:focus, .add-sub-plan:focus,.delete-btn:focus { background: transparent; color: #000 !important; border: none; box-shadow: none; outline: 0; }
.edit-plan:active, .add-sub-plan:active, .delete-btn:active { background-color: transparent !important; color: #000 !important; border: none; box-shadow: none !important; outline: 0 !important; }
.add-sub-plan:focus-visible, .edit-sub-plan:focus-visible{ outline: 0; }
.expand-2 > td:first-child { border-bottom: 0 !important; border-top: 0; }
table#store_website-analytics-table tr td:last-child { width: 150px; }
.r-date{width:95px;}
.no-border {border-bottom: 0 !important; border-top: 0 !important;}
h1{font-size:30px;font-weight:600;padding: 20px 0;}
h2{font-size:24px;font-weight:600;}
h3 {font-size:20px;font-weight:600;}
table{border: 1px;border-radius: 4px;}
table th{font-weight: normal;font-size: 15px;color: #000;}
table td{font-weight: normal;font-size: 14px;color: #757575;}
td button.btn {padding: 0;}
div#plan-action textarea {height: 200px;}
</style>
<div class="row mb-5">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Plans page</h2>
        <div class="row">
          <form action="{{ url()->current() }}" method="GET" id="searchForm" class="form-inline align-items-start">
              <div class="form-group col-md-2 mr-3s mb-3 no-pd">
                  <input name="term" type="text" class="form-control" value="{{ request('term') }}" placeholder="Search.." style="width:100%;">
              </div>
              <div class="form-group col-md-2 mr-3s mb-3 no-pd">
                  <input name="date" type="date" class="form-control" value="{{ request('date') }}" placeholder="Search.." style="width:100%;">
              </div>
              <div class="form-group col-md-2 mr-3s no-pd">
                <select class="form-control" name="typefilter">
                    <option value="">Select Type</option>
                    @foreach($typeList as $value )
                        <option value="{{$value->type}}">{{$value->type}}</option>
                    @endforeach;
                </select>
              </div>
              <div class="form-group col-md-2 mr-3s no-pd">
                <select class="form-control" name="categoryfilter">
                    <option value="">Select Type</option>
                    @foreach($categoryList as $value )
                        <option value="{{$value->category}}">{{$value->category}}</option>
                    @endforeach;
                </select>
              </div>
              <div class="form-group col-md-3 mr-3s no-pd">
                  <select class="form-control" name="priority">
                      <option value="">Select priority</option>
                      <option value="high">High</option>
                      <option value="medium">Medium</option>
                      <option value="low">Low</option>
                  </select>
              </div>
              <div class="form-group col-md-2 mr-3s no-pd">
                  <select class="form-control" name="status">
                      <option value="">Select status</option>
                      <option value="complete">complete</option>
                      <option value="pending">pending</option>
                  </select>
              </div>
              <div class="col-md-* no-pd">
              <button type="submit" class="btn btn-image image-filter-btn"><img src="/images/filter.png"/></button>
              </div>
          </form>
        </div>
        <div class="row float-right">
          <div class="col-md-6"></div>
          <div class="align-right mb-">
              <button type="button" class="btn btn-secondary new-plan" data-toggle="modal" data-target="#myModal">New plan</button>
              <button type="button" class="btn btn-secondary new-plan" data-toggle="modal" data-target="#myBasis">New basis</button>
              <button type="button" class="btn btn-secondary new-type" data-toggle="modal" data-target="#newtype">New Type</button>
              <button type="button" class="btn btn-secondary new-category" data-toggle="modal" data-target="#newcategory">New Category</button>
          </div>
        </div>
    </div>
</div>

@include('partials.flash_messages')

<div class="table-responsive">
    <table class="table table-bordered" id="store_website-analytics-table">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Type</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Sub subject</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Budget</th>
                <th>Basis</th>
                <th>Implications</th>
                <th width="15%">Solutions</th>
                <th>DeadLine</th>
                <th>status</th>
                <th>Date</th>
                <th width="15%" style="width: 20%;">Action</th>
            </tr>
        </thead>
        <tbody class="searchable">
            @foreach($planList as $key => $record)
            <tr>
                <td>{{$record->id}}</td>
                <td>{{$record->type}}</td>
                <td>{{$record->category}}</td>
                <td>{{$record->subject}}</td>
                <td>{{$record->sub_subject}}</td>
                <td width="15%">
                    <span class="toggle-title-box has-small" data-small-title="<?php echo substr($record->description, 0, 10).'..' ?>" data-full-title="<?php echo ($record->description) ? $record->description : '' ?>">
                        <?php
                            if($record->description) {
                                echo (strlen($record->description) > 12) ? substr($record->description, 0, 10).".." : $record->description;
                            }
                         ?>
                     </span>
                </td>
                <td>{{$record->priority}}</td>
                <td>{{$record->budget}}</td>
                <td>{{$record->basis}}</td>
                <td>{{$record->implications}}</td>
                <td><input type="text" class="form-control solutions" name="solutions" data-id="{{$record->id}}"><button type="button" class="btn btn-image show-solutions" data-id="{{$record->id}}"><i class="fa fa-info-circle"></i></button></td>
                <td class="r-date">{{$record->deadline}}</td>
                <td>{{$record->status}}</td>
                <td class="r-date">{{$record->date}}</td>
                <td class="actions-main">
                    <button type="button" class="btn btn-secondary edit-plan" data-id="{{$record->id}}"><i class="fa fa-edit"></i></button>
                    <a href="{{route('plan.delete',$record->id)}}" class="btn btn-image delete-btn" title="Delete Record"><img src="/images/delete.png"></a>
                    <button title="Add step" type="button" class="btn btn-secondary btn-sm add-sub-plan" data-id="{{$record->id}}" data-toggle="modal" data-target="#myModal">+</button>
                    <button title="Open step" type="button" class="btn preview-attached-img-btn btn-image no-pd" data-id="{{$record->id}}">
                        <img src="/images/forward.png" style="cursor: default;">
                    </button>
                    <button title="Open Action" type="button" class="btn plan-action btn-image no-pd" data-id="{{$record->id}}">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
            <tr class="expand-{{$record->id}} hidden">
                <th colspan="6"></th>
                <th>Remark</th>
                <th>description</th>
                <th>priority</th>
                <th>status</th>
                <th>date</th>
                <th>Action</th>
                @foreach( $record->subList( $record->id ) as $sublist)
                    <tr class="expand-{{$record->id}} hidden" >
                        <td colspan="6" class="no-border"></td>
                        <td width="10%">
                            <span class="toggle-title-box has-small" data-small-title="<?php echo substr($sublist->remark, 0, 10).'..' ?>" data-full-title="<?php echo ($sublist->remark) ? $sublist->remark : '' ?>">
                                <?php
                                    if($sublist->remark) {
                                        echo (strlen($sublist->remark) > 12) ? substr($sublist->remark, 0, 10).".." : $sublist->remark;
                                    }
                                 ?>
                             </span>
                        </td>
                        <td width="15%">
                            <span class="toggle-title-box has-small" data-small-title="<?php echo substr($sublist->description, 0, 10).'..' ?>" data-full-title="<?php echo ($sublist->description) ? $sublist->description : '' ?>">
                                <?php
                                    if($sublist->description) {
                                        echo (strlen($sublist->description) > 12) ? substr($sublist->description, 0, 10).".." : $sublist->description;
                                    }
                                 ?>
                             </span>
                        </td>
                        <td>{{$sublist->priority}}</td>
                        <td>{{$sublist->status}}</td>
                        <td>{{$sublist->date}}</td>
                        <td>
                            <button type="button" class="btn btn-secondary edit-plan" data-id="{{$sublist->id}}"><i class="fa fa-edit"></i></button>
                            <a href="{{route('plan.delete',$sublist->id)}}" class="btn btn-image" title="Delete Record"><img src="/images/delete.png"></a>
                        </td>
                    </tr>
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td colspan="14">{{$planList->appends(request()->except("page"))->links()}}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div class="modal fade" id="myBasis" tabindex="-1" role="dialog" aria-labelledby="myBasis" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myBasis">New basis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="planadd" action="{{ route('plan.create.basis') }}">
          <div class="modal-body">
            <div class="container-fluid">
                  @csrf
                  <div class="row subject-field">
                      <div class="col-md-12">
                          <div class="form-group">
                            <label  class="col-form-label">Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required="">
                          </div>
                      </div>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="newtype" tabindex="-1" role="dialog" aria-labelledby="newtype" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newtype">New Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="typeadd" action="{{ route('plan.create.type') }}">
          <div class="modal-body">
            <div class="container-fluid">
                  @csrf
                  <div class="row subject-field">
                      <div class="col-md-12">
                          <div class="form-group">
                            <label  class="col-form-label">Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required="">
                          </div>
                      </div>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="plan-action" tabindex="-1" role="dialog" aria-labelledby="plan-action" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plan-action">Plan Action</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="planactionadd" action="#">
          <input type="hidden" name="id" value="">
          <div class="modal-body">
            <div class="container-fluid">
                  @csrf
                  <table class="table table-bordered">
                    <tr>
                      <td>
                        <label  class="col-form-label">Strength</label>
                        <textarea name="strength" class="form-control"></textarea>
                      </td>
                      <td>
                        <label  class="col-form-label">Weakness</label>
                        <textarea name="weakness" class="form-control"></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label  class="col-form-label">Opportunity</label>
                        <textarea name="opportunity" class="form-control"></textarea>
                      </td>
                      <td>
                        <label  class="col-form-label">Threat</label>
                        <textarea name="threat" class="form-control"></textarea>
                      </td>
                    </tr>
                  </table>
                  <div class="row subject-field">
                      <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Strength</label>
                            <textarea name="strength"></textarea>
                          </div>
                      </div> -->
                      <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Weakness</label>
                            <textarea name="weakness"></textarea>
                          </div>
                      </div> -->
                  </div>
                  <div class="row subject-field">
                      <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Opportunity</label>
                            <textarea name="opportunity"></textarea>
                          </div>
                      </div> -->
                      <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Threat</label>
                            <textarea name="threat"></textarea>
                          </div>
                      </div> -->
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="plan-solutions" tabindex="-1" role="dialog" aria-labelledby="plan-solutions" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plan-action">Plan Solutions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="planactionadd" action="#">
          <input type="hidden" name="id" value="">
          <div class="modal-body">
            <div class="container-fluid">
              <table class="table table-bordered">
                <tr>
                  <th>Plans</th>
                </tr>
                <tbody class="show-plans-here"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="newcategory" tabindex="-1" role="dialog" aria-labelledby="newcategory" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newcategory">New Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="typeadd" action="{{ route('plan.create.category') }}">
          <div class="modal-body">
            <div class="container-fluid">
                  @csrf
                  <div class="row subject-field">
                      <div class="col-md-12">
                          <div class="form-group">
                            <label  class="col-form-label">Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required="">
                          </div>
                      </div>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModal">Plans</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" id="planadd" action="{{ route('plan.store') }}">
          <div class="modal-body">
            <div class="container-fluid">
                  @csrf
                  <div class="row subject-field">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="col-form-label">Type</label>
                          <input type="text" class="form-control" name="type" list="type" />
                            <datalist id="type">
                              @foreach($typeList as $value )
                                    <option value="{{$value->type}}">{{$value->type}}</option>
                                @endforeach;
                            </datalist>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="col-form-label">Category</label>
                          <input type="text" class="form-control" name="category" list="category" />
                          <datalist id="category">
                            @foreach($categoryList as $value )
                                  <option value="{{$value->category}}">{{$value->category}}</option>
                              @endforeach;
                          </datalist>
                        </div>
                      </div>
                    </div>
                    <div class="row subject-field">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Subject:</label>
                            <input type="text" name="subject" class="form-control">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Sub subject:</label>
                            <input type="text" name="sub_subject" class="form-control" >
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label  class="col-form-label">Priority:</label>
                            <select class="form-control" name="priority" required>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                          </div>
                      </div>
                      <input type="hidden" id="edit_id" name="id">
                      <input type="hidden" id="parent_id" name="parent_id">
                      <div class="col-md-6">
                         <div class="form-group">
                            <label  class="col-form-label">Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="complete">complete</option>
                                <option value="pending">pending</option>
                            </select>
                          </div>
                      </div>
                  </div>
                  <div class="row subject-field">
                      <div class="col-md-6">
                         <div class="form-group">
                            <label  class="col-form-label">Budget:</label>
                            <input type="number" name="budget" class="form-control">
                          </div>
                      </div>
                      <div class="col-md-6">
                         <div class="form-group">
                            <label class="col-form-label">Deadline:</label>
                            <input type="date" name="deadline" class="form-control">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                  <div class="col-md-6">
                         <div class="form-group">
                            <label class="col-form-label">Basis:</label>
                            <input type="text" class="form-control" name="basis" list="basis" />
                            <datalist id="basis">
                              @foreach($basisList as $value )
                                    <option value="{{$value->status}}">{{$value->status}}</option>
                                @endforeach;
                            </datalist>
                          </div>
                      </div>
                      <div class="col-md-6">
                         <div class="form-group">
                            <label class="col-form-label">Implications</label>
                            <input type="text" name="implications" class="form-control">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                         <div class="form-group">
                            <label  class="col-form-label">Date:</label>
                            <input type="date" name="date" class="form-control">
                          </div>
                      </div>
                      <div class="col-md-6">
                      <div class="form-group">
                            <label class="col-form-label">Description:</label>
                            <textarea class="form-control" name="description"></textarea>
                          </div>
                    </div>
                  </div>
                  <div class="row remark-field hidden" >
                      <div class="col-md-12">
                         <div class="form-group">
                            <label  class="col-form-label">Remark:</label>
                            <textarea class="form-control" name="remark"></textarea>
                          </div>
                      </div>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection

<script>

$(document).on('click','.new-plan', function (event) {
    $('#parent_id').val('');
    $('#edit_id').val('');
    $('.remark-field').addClass('hidden');
    $('.subject-field').removeClass('hidden')
    $('#planadd')[0].reset();
});

$('#myModal').on('hidden.bs.modal', function () {
  
})

$(document).on('click', '.preview-attached-img-btn', function (e) {     
    e.preventDefault();
    var planId = $(this).data('id');
    var expand = $('.expand-'+planId);
    $(expand).toggleClass('hidden');

});
$(document).on('click','.add-sub-plan', function (event) {
    var id = $(this).data('id');
    $('#edit_id').val('');
    $('#parent_id').val(id);
    $('#planadd')[0].reset();
    $('.subject-field').addClass('hidden')
    $('.remark-field').removeClass('hidden');
});

    $(document).on('click','.edit-plan', function (event) {
        $('.remark-field').addClass('hidden');
        $('.subject-field').removeClass('hidden')
        $('#planadd')[0].reset();
        var id = $(this).data('id');
        $('#parent_id').val('');
        $('#edit_id').val('');

        $('#edit_id').val(id)

        $.ajax({
            url: "{{ route('plan.edit') }}",
            data: { id : id },
            beforeSend: function () {
                $("#loading-image").show();
            }
        }).done(function (data) {
            console.log(data);
            if(data.code == 200){
                $('input[name="subject"]').val(data.object.subject);
                $('input[name="sub_subject"]').val(data.object.sub_subject);
                $('select[name="priority"]').val(data.object.priority).change();
                $('select[name="status"]').val(data.object.status).change();
                $('select[name="basis"]').val(data.object.basis).change();
                $('input[name="date"]').val(data.object.date);
                $('input[name="budget"]').val(data.object.budget);
                $('input[name="deadline"]').val(data.object.deadline);
                $('textarea[name="description"]').val(data.object.description);
                $('textarea[name="remark"]').val(data.object.remark);
                $('#parent_id').val(data.object.parent_id);
                if( data.object.parent_id != null ){
                    $('.remark-field').removeClass('hidden');
                    $('.subject-field').addClass('hidden')
                }
                $('#myModal').modal('toggle');
            }else{
                alert('error');
            }
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
      
    });

$(document).ready(function () {
    (function ($) {
        $('#filter').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();
        })

        $(document).on("click",".find-records",function(e){
            e.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                url: "/store-website-analytics/report/"+id,
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function (data) {
                $("#loading-image").hide();
                $(".bd-report-modal-lg .modal-body").empty().html(data);
                $(".bd-report-modal-lg").modal("show");
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        });

    }(jQuery));
});

$(document).on("click",".toggle-title-box",function(ele) {
    var $this = $(this);
    if($this.hasClass("has-small")){
        $this.html($this.data("full-title"));
        $this.removeClass("has-small")
    }else{
        $this.addClass("has-small")
        $this.html($this.data("small-title"));
    }
});
$(document).on("click",".plan-action",function(ele) {
  var id = $(this).data('id');
  $("#plan-action").find('input[name="id"]').attr('value',id);
  $.ajax({
      url: "/plan/"+id+"/plan-action",
      beforeSend: function () {
          $("#loading-image").show();
      }
  }).done(function (data) {
    console.log(data.strength);
      $("#loading-image").hide();
      $("#plan-action").find('textarea[name="strength"]').text(data.strength);
      $("#plan-action").find('textarea[name="weakness"]').text(data.weakness);
      $("#plan-action").find('textarea[name="opportunity"]').text(data.opportunity);
      $("#plan-action").find('textarea[name="threat"]').text(data.threat);
      $("#plan-action").find('input[name="id"]').attr('value',data.id);
      $("#plan-action").modal("show");
  }).fail(function (jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
  });
});
$(document).on("click",".show-solutions",function(ele) {
  var id = $(this).data('id');
  $.ajax({
      url: "/plan/plan-action/solutions-get/"+id,
      beforeSend: function () {
          $("#loading-image").show();
      }
  }).done(function (data) {
    console.log(data);
      $("#loading-image").hide();
      //show-plans-here
      var $html='';
      $.each(data, function(i, item) {
          $html+="<tr>";
          $html+="<td>"+item.solution+"</td>";
          $html+="</tr>";
      });
      $('.show-plans-here').html($html)
      $("#plan-solutions").modal("show");
  }).fail(function (jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
  });
});
$(document).on("keyup",".solutions",function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
      if($(this).val().length > 0){
        $.ajax({
            type: 'POST',
            url: "/plan/plan-action/solutions-store",
            data: { 
              _token: "{{ csrf_token() }}",
              solution: $(this).val(),
              id: $(this).data('id')
            },
            beforeSend: function () {
                $("#loading-image").show();
            }
        }).done(function (data) {
          console.log(data.strength);
            $("#plan-action").modal("hide");
            toastr["success"]('Data save successfully.');
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            $("#plan-action").modal("hide");
            toastr["error"]('An error occured!');
        });
      }
  }
});
$(document).on("submit","#planactionadd",function(event) {
  event.preventDefault();
  $.ajax({
      type: 'POST',
      url: "/plan/plan-action/store",
      data: { 
        _token: "{{ csrf_token() }}",
        form : $(this).serialize(),
        id: $(this).find('input[name="id"]').val(),
        strength: $(this).find('textarea[name="strength"]').val(),
        weakness: $(this).find('textarea[name="weakness"]').val(),
        opportunity: $(this).find('textarea[name="opportunity"]').val(),
        threat: $(this).find('textarea[name="threat"]').val(),
      },
      beforeSend: function () {
          $("#loading-image").show();
      }
  }).done(function (data) {
    console.log(data.strength);
      $("#plan-action").modal("hide");
      toastr["success"]('Data save successfully.');
  }).fail(function (jqXHR, ajaxOptions, thrownError) {
      $("#plan-action").modal("hide");
      toastr["error"]('No record found!');
  });
});
</script>
