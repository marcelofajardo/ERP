@extends('layouts.app')

@section('styles')

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection


@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <h2 class="page-heading">Services</h2>
                    <button style="display: none" type="button" class="btn btn-primary editModal" data-toggle="modal" data-target="#ModalCenter">
                        Edit
                    </button>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                            data-target="#exampleModalCenter">
                        Create Service
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body edit-modal-body" id="edit-modal">
                    <form action="post" id="">
                        @csrf
                        <input type="hidden" id="serviceId" name="serviceId" value="">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input required name="name" type="text" class="form-control" id="name"
                                   aria-describedby="emailHelp" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <textarea required name="description" class="form-control" id="textarea"
                                      rows="5"></textarea>
                        </div>
                        <button id="btnedit" type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="post" id="">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input required name="name" type="text" class="form-control name"
                                   aria-describedby="emailHelp" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <textarea required name="description" class="form-control textarea" id=""
                                      rows="5"></textarea>
                        </div>
                        <button id="btn" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{--    @if ($message = Session::get('success'))
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
        @endif--}}

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="passwords-table">
            <thead>
            <tr>
                <!-- <th style="">ID</th> -->
                <th style="">ID</th>
                <th style="">Name</th>
                <th style="">Description</th>
                <th style="">Action</th>
            </thead>
            <tbody>
            @foreach($data as $value)
                <tr class="{{$value->id}}">
                    <td id="id">{{$value->id}}</td>
                    <td id="name">{{$value->name}}</td>
                    <td id="description">{{$value->description}}</td>
                    <td><i id="{{$value->id}}" class="fa fa-pencil-square-o change" title="Edit" aria-hidden="true"></i>
                        <i id="{{$value->id}}" class="fa fa-trash remove" aria-hidden="true"></i>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if(isset($data))
            {{ $data->links() }}
        @endif
    </div>
@endsection


@section('scripts')
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>

    <script>
        $('.change').on('click', function () {
            var id = $(this).attr("id");

            $(".edit-modal-body #serviceId").val(id);

           var name = $(this).closest('tr').find('#name').text();
           $(".edit-modal-body #name").val(name);

            var desc = $(this).closest('tr').find('#description').text();
            $(".edit-modal-body #textarea").html(desc);
            $('.editModal').click();
        });

        $('.remove').on('click', function (e) {
            e.preventDefault();
            var id = e.target.id
            if (confirm("Are you sure?")) {

                $.ajax({
                    url: '/marketing/services/destroy',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function (data) {
                        $('.' + data).remove();
                    }
                });
            }
        });

        $('#btnedit').on('click', function (e) {
            e.preventDefault();
            var id = $('#serviceId').val();
            var name = $('#name').val();
            var text = $('#textarea').val();


            $.ajax({
                url: '/marketing/services/update',
                type: 'POST',
                data: {
                    id: id,
                    name: name,
                    description: text
                },
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function (data) {
                    alert(data[0]["description"]);
                    $('tr[class="' + data[0]["id"] + '"]').find('#name').text(data[0]["name"]);
                    $('tr[class="' + data[0]["id"] + '"]').find('#description').html(data[0]["description"]);

                    $('.close').click();
                    $('#name').val('');
                    $('#textarea').val('');
                }
            });
        });

        $('#btn').on('click', function (e) {
            e.preventDefault();
            var name = $('.name').val();
            var text = $('.textarea').val();
            $.ajax({
                url: '/marketing/services/store',
                type: 'POST',
                data: {
                    name: name,
                    text: text
                },
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function (data) {
                    console.log(data);
                    $('tbody').prepend(`
                    <tr>
                    <td>` + data[0]['id'] + ` </td>
                    <td>` + data[0]['name'] + `</td>
                    <td>` + data[0]['description'] + `</td>
                    <td><a href=""><i class="fa fa-pencil-square-o" title="Edit" aria-hidden="true"></i></a></td>
                    </tr>
                    `)
                    $('.close').click();
                    $('#name').val('');
                    $('#textarea').val('');
                }
            });
        })
    </script>
@endsection