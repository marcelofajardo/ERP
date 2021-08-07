@extends('layouts.app')

@section('title', 'Hs Code')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
     <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
        .wi{
            width: 150px !important;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Hs Code Generator ({{ $pendingCategoryCount }}) ({{ count($compositions) }})</h2>
            <div class="pull-left">
                <form class="form-inline" action="{{ route('simplyduty.hscode.key') }}" method="POST">
                    @csrf
                    <div class="form-group mr-3">
                      <input name="key" type="text" class="form-control"  placeholder="Hscode Key" value="@if(isset($setting) && $setting != '') {{ $setting->key  }}  @endif">
                  </div>
                  <div class="form-group mr-3" style="display: -webkit-inline-box;">
                      <label>Origin Country</label>
                      <select class="form-control selectpicker wi" name="from" data-live-search="true">
                          <option>Please Select Origin Country</option>  
                          @foreach($countries as $country)  
                          <option value="{{ $country->country_code }}" @if(isset($setting->destination_country) && $setting->from_country == $country->country_code) selected @endif>{{ $country->country_name }} </option>
                          @endforeach
                      </select>
                  </div>
                  <div class="form-group mr-3" style="display: -webkit-inline-box;">
                  <label>Destination Country</label>
                  <select class="form-control wi selectpicker" name="destination" data-live-search="true">
                   <option>Please Select Destination Country</option>
                   @foreach($countries as $country)  
                   <option value="{{ $country->country_code }}" @if(isset($setting->destination_country) && $setting->destination_country == $country->country_code) selected @endif>{{ $country->country_name }} </option>
                   @endforeach
                    </select>
                    </div>
                    <small>*getting it from simply duty country </small>
               <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Submit</button>
           </form>
       </div>
             <div class="pull-right">

                <button type="button" class="btn btn-secondary" onclick="createGroup()">Group</button>
            </div>
        </div>
    </div>
    
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Groups</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="pull-right">
                            
                     </div>
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Hscode</th>
                                    <th>Composition</th>
                                    <th>Composition Count</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($groups as $group)    
                                <tr>
                                    <td><span id="na{{ $group->id }}">{!! $group->name !!}</span></td>
                                    <td><span id="hs{{ $group->id }}">@if($group->hsCode) {{ $group->hsCode->code }} @endif</span></td>
                                    <td><span id="com{{ $group->id }}">{!! $group->composition !!}<span></td>
                                    <td>{{ $group->groupComposition->count() }}</td>
                                    <td><button onclick="editGroup({{ $group->id }})" class="btn btn-secondary">Edit</button>
                                    </td>
                                </tr>
                                @include('products.partials.edit-Group')
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    
    @include('partials.flash_messages')
    <form action="/product/hscode" method="get">
     <div class="mt-3 col-md-12">
        <div class="row">
            <div class="col-md-4">
                 {!! $category_selection !!}
            </div>
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" value="@if(isset($keyword)){{ $keyword }}@endif" placeholder="Place Composition To Search here">
            </div>
            <div class="col-md-2">
                <input type="checkbox" name="group" @if($groupSelected == 'on') checked @endif>Include Group
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
            </div>
        </div>    
     </div>

    </form>
    <div class="mt-3 col-md-12">
        <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th style="width: 2%"><input type="checkbox" id="ckbCheckAll">  Select All</th>
                
                <th width="30%">Combinations</th>
            </tr>
            </thead>
            <tbody id="content_data">
            @foreach($compositions as $composition)
            <tr>
                <td><input type="checkbox" class="form-control checkBoxClass" value="{{ $composition }} {{ $childCategory }} {{ $parentCategory }}" name="composition"></td>
               <td>{{ $composition }} [ {{ $childCategory }}  > {{ $parentCategory }} ]</td>
            </tr>
                
            @endforeach
            </tbody>
        </table>
    </div>
 
@include('products.partials.group-hscode-modal')
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript">


    $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
         });


    function createGroup() {
        $('#groupModal').modal('show');
    }

    $(document).ready(function () {
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
    });

    function submitGroup(){
        name = "{{ $childCategory }}";
        composition = $('#composition').val();
        category = $('#category_value').val();
        existing_group = $('#existing_group').val();
        var compositions = [];
            $.each($("input[name='composition']:checked"), function(){
                compositions.push($(this).val());
            });
        if(compositions.length == 0){
            alert('Please Select Combinations');
        }else{
            src = "{{ route('hscode.save.group') }}";
            $.ajax({
                url: src,
                type: "POST",
                dataType: "json",
                data: {
                    name : name,
                    compositions : compositions,
                    composition : composition, 
                    category : category,
                    existing_group : existing_group,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $('#groupModal').modal('hide');
                    $("#loading-image").show();
                },
                success: function(data) {
                    $('#groupModal').modal('hide');
                    console.log(data);
                    location.reload();
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                },
            }); 

        }

        return false;
    }


    function editGroup(id){
        $('#editGroupModal'+id).modal('show');
    }

    function submitGroupChange(id){
        src = "{{ route('hscode.edit.group') }}";
        name = $('#name'+id).val();
        composition = $('#composition'+id).val();
        $.ajax({
                url: src,
                type: "POST",
                dataType: "json",
                data: {
                    id : id,
                    name : name,
                    composition : composition,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $('#editGroupModal'+id).modal('hide');
                    $("#loading-image").show();
                },

            }).done(function (data) {
                $("#loading-image").hide();
                $('#na'+id).text(name);
                $('#hs'+id).text(hscode);
                $('#com'+id).text(composition);
                
                
            });         

    }

     </script>
   
@endsection