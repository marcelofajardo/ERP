@extends('layouts.app')

@section('favicon' , 'password-manager.png')

@section('title', 'System Size')


@section('content')

<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">System Size</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" id="sizemanagementmodelbtn" data-toggle="modal" data-target="#sizemanagement">System Size Management</button>
                <button type="button" class="btn btn-secondary"  data-toggle="modal" data-target="#sizecountry">System Size </button>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="category-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>ERP Size</th>
                    <th>Sizes</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
             
            <tbody>
                    @include('system-size.partials.data')
            </tbody>
        </table>
    </div>
    {!! $systemSizesManagers->appends(Request::except('page'))->links() !!}
    <div class="modal fade" id="sizecountry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">System size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="editsizeform" style="display: none;">
                        <div class="col-md-12">
                            <input type="text" class="form-control nav-link" id="systemsizenameedit" name="code" placeholder="Enter code">
                            <input type="hidden" id="systemsizeeditid" name="id" >
                        </div>
                        <div class="col-md-6 mt-3">
                            <button type="button" id="sizestorebtnupdate" class="btn btn-primary">Update</button>
                        </div>
                        <div class="col-md-6 text-right mt-3">
                            <button type="button" id="canceledit" class="btn btn-default">cancel</button>
                        </div>
                    </div>
                    <div class="row" id="createsizeform">
                        <div class="col-md-10">
                            <input type="text" class="form-control nav-link" id="systemsizename" name="name" placeholder="Enter code" style="margin-top : 1%;">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="sizestorebtn" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered" id="category-table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($systemSizes))
                                            @foreach($systemSizes as $systemSize)
                                            <tr>
                                                <td>{{$systemSize->name}}</td>
                                                <td>
                                                    <button class="btn btn-default systemsizeedit" data-id="{{$systemSize->id}}" data-name="{{$systemSize->name}}"><i class="fa fa-edit"></i></button>
                                                    <button class="btn btn-default systemsizedelete" data-id="{{$systemSize->id}}"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                               <td colspan="2" class="text-center"> No Result Found</td> 
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sizemanagement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route("system.size.managerstore")}}" method="POST" id="createsizeformmodel">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">System size manager</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-sizemanager" style="display: none;">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control" name="category" id="categorydrp" required="">
                                    @foreach($categories as $cat)
                                        @foreach($cat['subcategories'] as $subcat)
                                        <option value="{{$subcat->id}}">{{$subcat->title}} ({{$cat['parentcategory']}})</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-3 sizevarintinput">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span>ERP Size (IT)</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" placeholder="Enter ERP size" name="erp_size">
                                    </div>
                                </div>
                            </div>
                            @foreach($systemSizes as $systemSize)
                                <div class="col-md-12 mt-3 sizevarintinput">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span>{{$systemSize->name}}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Enter size" name="sizes[{{$systemSize->id}}][size]" required="">
                                            <input type="hidden" name="sizes[{{$systemSize->id}}][system_size_id]" value="{{$systemSize->id}}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sizemanagementedit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route("system.size.managerupdate")}}" method="POST" id="updatesizeformmodel">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">System size manager</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-sizemanageredit" style="display: none;">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="editnmanagerf">
                                <select class="form-control" name="category" id="categorydrpedit" required="">
                                    @foreach($categories as $cat)
                                        @foreach($cat['subcategories'] as $subcat)
                                        <option value="{{$subcat->id}}">{{$subcat->title}} ({{$cat['parentcategory']}})</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function() {
    $(document).on('click','#canceledit',function(){
        $('#editsizeform').hide();
        $('#createsizeform').show();
    });
    $(document).on('click','.systemsizeedit',function(){
        $('#systemsizenameedit').val($(this).data('name'));
        $('#systemsizeeditid').val($(this).data('id'));
        $('#editsizeform').show();
        $('#createsizeform').hide();
    });
    $(document).on('click','.systemsizedelete',function(){
        let id = $(this).data('id');
        $selector = $(this).parent().parent(); 
        if (confirm('Are you sure want to delete!')){
            $.ajax({
                url:'{{route("system.size.delete")}}',
                dataType:'json',
                data:{
                    id: id,
                },
                success:function(result){
                    $selector.remove();   
                     window.location.reload();
                },
                error:function(exx){
                    console.log(exx)
                }
            })
        }
    })
    $(document).on('click','#sizestorebtn',function(){
        $.ajax({
            url:'{{route("system.size.store")}}',
            dataType:'json',
            data:{
                name: $('#systemsizename').val(),
            },
            success:function(result){
                window.location.reload();
            },
            error:function(exx){
                if (exx.status == 422){
                    $.each(exx.responseJSON.errors,function(key,value){
                        $('[name="'+key+'"]').parent().append('<span class="error">'+value[0]+'</span>')
                    });
                }else{
                    alert('Something went wrong!');
                }
            }
        })
    });
    $(document).on('click','#sizestorebtnupdate',function(){
        $.ajax({
            url:'{{route("system.size.update")}}',
            dataType:'json',
            data:{
                code: $('#systemsizenameedit').val(),
                id: $('#systemsizeeditid').val(),
            },
            success:function(result){
                window.location.reload();
            },
            error:function(exx){
                if (exx.status == 422){
                    $.each(exx.responseJSON.errors,function(key,value){
                        $('[name="'+key+'"]').parent().append('<span class="error">'+value[0]+'</span>')
                    });
                }else{
                    alert('Something went wrong!');
                }
            }
        })
    });
    
    $(document).on('submit','#createsizeformmodel',function(e){
        e.preventDefault();
        $.ajax({
            url:'{{route("system.size.managerstore")}}',
            type:'POST',
            data:$('#createsizeformmodel').serialize(),
            success:function(result){
                if (result.success){
                    window.location.reload();
                }else{
                    $('.alert-sizemanager').text(result.message);
                    $('.alert-sizemanager').show();
                    setTimeout(function(){
                        $('.alert-sizemanager').hide();
                    },10000);
                }
            },
            error:function(exx){
                alert('Something went wrong!')
            }
        })
    });
    $(document).on('click','.editmanager',function(){
        $('#loading-image-preview').show()
        let id = $(this).data('id');
        $.ajax({
            url:'{{route("system.size.manageredit")}}',
            dataType:'json',
            data:{
                id:id
            },
            success:function(result){
                $('.sizevarintinput1').remove();
                $('#editnmanagerf').append(result.data);
                $('#categorydrpedit').val(result.category_id);
                $('#sizemanagementedit').modal('show');
                $('#loading-image-preview').hide()
            },
            error:function(exx){
                $('#loading-image-preview').hide()
                alert('Something went wrong!')
            }
        })
    });
    $(document).on('submit','#updatesizeformmodel',function(e){
        e.preventDefault();
        $.ajax({
            url:'{{route("system.size.managerupdate")}}',
            type:'POST',
            data:$('#updatesizeformmodel').serialize(),
            success:function(result){
                if (result.success){
                    window.location.reload();
                }else{
                    $('.alert-sizemanageredit').text(result.message);
                    $('.alert-sizemanageredit').show();
                    setTimeout(function(){
                        $('.alert-sizemanageredit').hide();
                    },10000);
                }
            },
            error:function(exx){
                alert('Something went wrong!')
            }
        });
    });  
    $(document).on('click','.deletemanager',function(){
        let id = $(this).data('id');
        if (confirm('Are you sure want do delete?')){
            $.ajax({
                url:'{{route("system.size.managerdelete")}}',
                dataType:'json',
                data:{
                    id:id,
                },
                success:function(result){
                    window.location.reload();
                },
                error:function(exx){
                    alert('Something went wrong!')
                }
            });
        }
    });  
    // $(document).on('click','#sizemanagementmodelbtn',function(){
    //     checkVariant();
    // });
    $(document).on('change','#categorydrp',function(){
        // checkVariant();
    });
    function checkVariant(){
        console.log('aas');
        let id = $('#categorydrp').val();
        if (id != null && id != ''){
            $('#loading-image-preview').show()
            $.ajax({
                url:'{{route("system.size.managercheckexistvalue")}}',
                dataType:'json',
                data:{
                    id:id,
                },
                success:function(result){
                    $('.sizevarintinput').remove();
                    $('#sizevariant').before(result.data);
                    $('#loading-image-preview').hide()
                },
                error:function(exx){
                    $('#loading-image-preview').hide()
                    alert('Something went wrong1!')
                }
            });
        }else{
            $('.sizevarintinput').remove();

        }
    }
});  
</script>
@endsection
