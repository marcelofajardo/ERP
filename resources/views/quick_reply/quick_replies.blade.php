@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Quick Replies</h2>
        </div>

        <div class="col-md-12">
            <div class="infinite-scroll">
                <div class="table-responsive mt-3">
                    <div class="col-md-4 d-inline form-inline">
                        <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                        <button class="btn btn-secondary quick_category_add">+</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            @if(isset($store_websites))
                                    <tr>
                                        <th>#</th>
                                        @foreach($store_websites as $websites)
                                            <th>{{ $websites->title }}</th>
                                        @endforeach
                                    </tr>
                            @endif
                        </thead>
                        <tbody class="tbody">
                            @if(isset($all_categories))
                                    @foreach($all_categories as $all_category)
                                        <tr>
                                            <td>{{ $all_category->name }}</td>
                                            @if(isset($store_websites))
                                                @foreach($store_websites as $websites)
                                                    <td>
                                                        <div id="show_add_reply_{{ $all_category->id }}_{{ $websites->id }}" class="hide_all_inputs" style="display: none;">
                                                            <input type="text" id="reply_{{ $all_category->id }}_{{ $websites->id }}" class="reply_inputs"/>
                                                            <button class="btn btn-secondary btn-sm save_reply">&#10004;</button>
                                                        </div>

                                                        <ul id="show_reply_list_{{ $all_category->id }}_{{ $websites->id }}">
                                                            <li class="show_add_option" id="show_add_option_{{ $all_category->id }}_{{ $websites->id }}">
                                                                <a href="#" class="add_quick_reply" id="{{ $all_category->id }}" data-attr="{{ $websites->id }}">Add new reply</a>
                                                            </li>
                                                            @foreach($category_wise_reply as $key => $value)
                                                                @if($key == $all_category->id)
                                                                    @foreach($value as $key1 => $item)
                                                                        @if($key1 == $websites->id)
                                                                                @foreach($item as $val)
                                                                                <div id="edit_reply_{{ $val->id }}" class="edit_reply_input" style="display: none;">
                                                                                    <input type="text" value="{{ $val->reply }}" id="edit_reply_{{ $val->id }}" />
                                                                                    <button class="btn btn-secondary btn-sm update_reply">&#10004;</button>
                                                                                </div>
                                                                                <li id="{{ $val->id }}" class="edit_reply">{{ $val->reply }}</li>
                                                                                @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.hide_all_inputs').hide();
            $(document).on('click', '.quick_category_add', function () {
                var textBox = $(this).closest("div").find(".quick_category");
                if (textBox.val() == "") {
                    alert("Please Enter Category!!");
                    return false;
                }

                var category_count = '{{ $website_length }}';

                $.ajax({
                    type: "POST",
                    url: "{{ route('add.reply.category') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'name': textBox.val()
                    }
                }).done(function (response) {
                    textBox.val('');
                    var str = '<tr><td>'+ response.data.name +'</td>';
                    for(var i = 0; i < category_count; i++){
                        str += '<td></td>';
                    }
                    str += '</tr>';
                    $('.tbody').append(str);
                })
            });

            var cat_id, store_id;
            $('.add_quick_reply').on("click", function(){
                $('.hide_all_inputs').hide();
                $('.show_add_option').show();
                $('.reply_inputs').val('');
                cat_id = $(this).attr('id');
                store_id = $(this).data('attr');
                $('#show_add_option_'+cat_id+'_'+store_id).hide();
                $('#show_add_reply_'+cat_id+'_'+store_id).show();
            });

            $(document).on('click','.save_reply',function(){
                var reply = $('#reply_'+cat_id+'_'+store_id).val();
                if(reply == ''){
                    alert('Please enter reply');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('save-store-wise-reply') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'reply': reply,
                        'category_id': cat_id,
                        'store_website_id' : store_id
                    }
                }).done(function (response) {
                    $('#show_add_reply_'+cat_id+'_'+store_id).hide();
                    $('.show_add_option').show();
                    if(response.status == 1){
                       $('#show_reply_list_'+cat_id+'_'+store_id).append('<li>'+response.data+'</li>');
                        toastr['success'](response.message);
                    }else{
                        toastr['error'](response.message);
                    }
                    window.location.reload();
                });
            });

            var reply_id;
            $(document).on('click', '.edit_reply', function(){
                $('.hide_all_inputs').hide();
                $('.edit_reply').show();
                reply_id = $(this).attr('id');
                $('#'+reply_id).hide();
                $('.edit_reply_input').hide();
                $('#edit_reply_'+reply_id).show();
            });

            $(document).on('click','.update_reply',function(){
                console.log('#edit_reply_'+reply_id);
                var edit_reply = $('input[id^="edit_reply_'+reply_id+'"]').val();
                console.log(edit_reply);
                if(edit_reply == ''){
                    alert('Please enter reply');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('save-store-wise-reply') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'reply_id': reply_id,
                        'reply': edit_reply
                    }
                }).done(function (response) {
                    console.log(response);
                    window.location.reload();
                });
            });


        });
    </script>
@endsection
