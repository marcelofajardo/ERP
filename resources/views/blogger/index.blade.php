@extends('layouts.app')

@section('title', 'blogger Info')

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Blogger Info</h2>
            <div id="exTab2" class="container-fluid">
                <ul class="nav nav-tabs">
                    <li class="{{ (!session()->has('active_tab') && session()->get('active_tab') != 'contact_tab' && session()->get('active_tab') != 'blogger_list_tab') ? 'active' : ''}}">
                        <a href="#blogger-tab" data-toggle="tab">Blogger</a>
                    </li>
                    <li class="{{ (session()->has('active_tab') && session()->has('active_tab') == 'blogger_list_tab') ? 'active' : ''}}">
                        <a href="#bloggers-list-tab" data-toggle="tab">Blogger List</a>
                    </li>
                    <li class="{{ session()->has('active_tab') && session()->has('active_tab') == 'contact_tab' ? 'active' : ''}}">
                        <a href="#contact-history-tab" data-toggle="tab" >Contact History</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 border">
                    <div class="tab-content">
                        <div class="tab-pane {{ (!session()->has('active_tab') && session()->get('active_tab') != 'contact_tab' && session()->get('active_tab') != 'blogger_list_tab') ? 'active' : ''}} mt-3" id="blogger-tab">
                            {{--<div class="pull-left">
                                <form class="form-inline" action="{{ route('blogger.index') }}" method="GET">
                                    <div class="form-group">
                                        <input name="term" type="text" class="form-control"
                                               value="{{ isset($term) ? $term : '' }}"
                                               placeholder="Search">
                                    </div>

                                    <div class="form-group">
                                        <input type="checkbox" name="with_archived"
                                               id="with_archived" {{ Request::get('with_archived')=='on'? 'checked' : '' }}>
                                        <label for="with_archived">Archived</label>
                                    </div>

                                    <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filter
                                    </button>
                                </form>
                            </div>--}}
                            <div class="pull-right">
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                        data-target="#contactBloggerModal"
                                        title="Contact Blogger"><i class="fa fa-plus"></i> Contact Blogger
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                        data-target="#createBloggerModal"
                                        title="Add new Blogger"><i class="fa fa-plus"></i> Blogger
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                        data-target="#createBloggerProductModal"
                                        title="Add new Blogger Product"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="5%">Sr. no</th>
                                        <th width="5%">Brand</th>
                                        <th width="5%">Blogger</th>
                                        <th width="5%">Shoot Date</th>
                                        <th width="5%">1st Post</th>
                                        <th width="5%">Stats</th>
                                        <th width="5%">2nd Post</th>
                                        <th width="5%">Stats</th>
                                        <th width="5%">City</th>
                                        <th width="5%">Initial Quote</th>
                                        <th width="5%">Final Quote</th>
                                        <th width="20%">Send</th>
                                        <th width="20%">Communication</th>
                                        <th width="5%">Upload</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($blogger_products as $blogger_product)
                                        <tr>
                                            <td>{{ $blogger_product->id }}</td>
                                            <td>{{ optional($blogger_product->brand)->name }}</td>

                                            <td class="small expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                              {{ strlen(optional($blogger_product->blogger)->name) > 10 ? substr(optional($blogger_product->blogger)->name, 0, 10) : optional($blogger_product->blogger)->name }}
                                            </span>
                                                <span class="td-full-container hidden">
                                                {{ optional($blogger_product->blogger)->name }}
                                                </span>
                                            </td>
                                            <td class="small">{{$blogger_product->shoot_date }}</td>
                                            <td class="small">{{$blogger_product->first_post }}</td>
                                            <td class="small expand-row table-hover-cell">
                                                 <span class="td-mini-container">
                                              Likes : {{ $blogger_product->first_post_likes }}
                                            </span>
                                                <span class="td-full-container hidden">
                                                @if($blogger_product->first_post_likes)<p><i class="fa fa-thumbs-up"></i> {{$blogger_product->first_post_likes }}</p>@endif
                                                @if($blogger_product->first_post_engagement)<p><i class="fa fa-retweet"></i> {{$blogger_product->first_post_engagement }}</p>@endif
                                                @if($blogger_product->first_post_response)<p><i class="fa fa-envelope"></i> {{$blogger_product->first_post_response }}</p>@endif
                                                @if($blogger_product->first_post_sales)<p><i class="fa fa-shopping-cart"></i> {{$blogger_product->first_post_sales }}</p>@endif
                                                </span>
                                            </td>
                                            <td class="small">{{$blogger_product->second_post }}</td>
                                            <td class="small expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                              Likes : {{ $blogger_product->second_post_likes }}
                                            </span>
                                                <span class="td-full-container hidden">
                                                @if($blogger_product->second_post_likes)<p><i class="fa fa-thumbs-up"></i> {{$blogger_product->second_post_likes }}</p>@endif
                                                @if($blogger_product->second_post_engagement)<p><i class="fa fa-retweet"></i> {{$blogger_product->second_post_engagement }}</p>@endif
                                                @if($blogger_product->second_post_response)<p><i class="fa fa-envelope"></i> {{$blogger_product->second_post_response }}</p>@endif
                                                @if($blogger_product->second_post_sales)<p><i class="fa fa-shopping-cart"></i> {{$blogger_product->second_post_sales }}</p>@endif
                                                </span>
                                            </td>
                                            <td>{{$blogger_product->city}}</td>
                                            <td class="expand-row table-hover-cell">{{$blogger_product->initial_quote}}</td>
                                            <td class="expand-row table-hover-cell">{{$blogger_product->final_quote}}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <input type="text" class="form-control quick-message-field" name="message"
                                                           placeholder="Message" value="">
                                                    <button class="btn btn-sm btn-image send-message" data-bloggerId="{{ $blogger_product->blogger_id }}"><img
                                                                src="/images/filled-sent.png"/></button>
                                                </div>
                                            </td>
                                            <td class="expand-row table-hover-cell {{ optional($blogger_product->blogger)->chat_message->count() && optional($blogger_product->blogger)->chat_message->first()->status == 0 ? 'text-danger' : '' }}"
                                                style="word-break: break-all;">
                                                @if(optional($blogger_product->blogger)->chat_message->first())
                                                    <span class="td-mini-container">
                                                  {{ strlen(optional($blogger_product->blogger)->chat_message->first()->message) > 20 ? substr(optional($blogger_product->blogger)->chat_message->first()->message, 0, 20) . '...' : optional($blogger_product->blogger)->chat_message->first()->message }}
                                                </span>
                                                    <span class="td-full-container hidden">
                                                    {{ optional($blogger_product->blogger)->chat_message->first()->message }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                                        data-target="#bloggerImageModal" data-blogger-product-id="{{ $blogger_product->id }}" data-blogger-product-images="{{$blogger_product->images}}"><i class="fa fa-plus"></i></button>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('blogger-product.show', $blogger_product->id) }}"
                                                       class="btn btn-image" href=""><img
                                                                src="/images/view.png"/></a>
                                                    <button type="button" class="btn btn-image make-remark" data-toggle="modal"
                                                            data-target="#makeRemarkModal" data-id="{{ $blogger_product->id }}"><img
                                                                src="/images/remark.png"/></button>
                                                    <button type="button" class="btn btn-image edit-blogger"
                                                            data-toggle="modal"
                                                            data-target="#updateBloggerProductModal"
                                                            data-blogger-product="{{ json_encode($blogger_product) }}"><img
                                                                src="/images/edit.png"/></button>
                                                  {{--  {!! Form::open(['method' => 'DELETE','route' => ['blogger-product.destroy', $blogger_product->id],'style'=>'display:inline']) !!}
                                                    <button type="submit" class="btn btn-image"><img
                                                                src="/images/delete.png"/></button>
                                                    {!! Form::close() !!}--}}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="15" class="text-center text-danger">No Blogger/s Found.</th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane mt-3 {{ (session()->has('active_tab') && session()->has('active_tab') == 'blogger_list_tab') ? 'active' : ''}}" id="bloggers-list-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="10%">Sr. no</th>
                                        <th>Name</th>
                                        <th width="10%">Instagram handle</th>
                                        <th width="10%">Agency</th>
                                        <th width="10%">City/Country</th>
                                        <th>Contact</th>
                                        <th width="10%">Stats</th>
                                        <th>Fake Followers (%)</th>
                                        <th>Industry</th>
                                        <th>Brands</th>
                                        <th width="20%">Send</th>
                                        <th width="20%">Communication</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($bloggers as $blogger_index => $blogger)
                                        <tr>
                                            <td>{{++$blogger_index}}</td>
                                            <td>{{ $blogger->name }}</td>
                                            <td>{{ $blogger->instagram_handle }}</td>
                                            <td class="expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                                     {{ strlen($blogger->agency) > 10 ? substr($blogger->agency, 0, 10) : $blogger->agency }}
                                                </span>
                                                <span class="td-full-container hidden">
                                                    {{ $blogger->agency }}
                                                </span>
                                            </td>
                                            <td class="expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                                    {{$blogger->city}}..
                                                </span>
                                                <span class="td-full-container hidden">
                                                    {{$blogger->city}}/{{$blogger->country}}
                                                </span>
                                            </td>
                                            <td>{{$blogger->phone}}</td>
                                            <td class="expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                                    Foll : {{ $blogger->followers }}..
                                                </span>
                                                <span class="td-full-container hidden">
                                                Followers : {{ $blogger->followers }} | Following: {{ $blogger->followings }} | Engagement :{{ $blogger->avg_engagement }}
                                                </span>
                                            </td>
                                            <td>{{$blogger->fake_followers}}</td>
                                            <td>{{$blogger->industry}}</td>
                                            <td class="expand-row table-hover-cell">
                                                <span class="td-mini-container">
                                                ...
                                                </span>
                                                <span class="td-full-container hidden">
                                                    @if($blogger->brands && count($blogger->brands))
                                                        @foreach($blogger->brands as $brand)
                                                            <?php
                                                            $brand_name = optional(\App\Brand::find($brand))->name
                                                            ?>
                                                            {{ $brand_name }}
                                                        @endforeach
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <input type="text" class="form-control quick-message-field" name="message"
                                                           placeholder="Message" value="">
                                                    <button class="btn btn-sm btn-image send-message" data-bloggerId="{{ $blogger->blogger_id }}"><img
                                                                src="/images/filled-sent.png"/></button>
                                                </div>
                                            </td>
                                            <td class="expand-row table-hover-cell {{ $blogger->chat_message->count() && $blogger->chat_message->first()->status == 0 ? 'text-danger' : '' }}"
                                                style="word-break: break-all;">
                                                @if($blogger->chat_message->first())
                                                    <span class="td-mini-container">
                                                  {{ strlen($blogger->chat_message->first()->message) > 20 ? substr($blogger->chat_message->first()->message, 0, 20) . '...' : $blogger->chat_message->first()->message }}
                                                </span>
                                                    <span class="td-full-container hidden">
                                                    {{ $blogger->chat_message->first()->message }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{route('blogger.payments', $blogger->id)}}" class="btn btn-sm" title="Blogger Payments" target="_blank"><i class="fa fa-money"></i> </a>
                                                    <button type="button" class="btn btn-image edit-blogger"
                                                            data-toggle="modal"
                                                            data-target="#createBloggerModal"
                                                            data-blogger="{{ json_encode($blogger) }}">
                                                        <img
                                                                src="/images/edit.png"/></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="13" class="text-center text-danger">No Blogger listed .
                                            </th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane mt-3 {{ session()->has('active_tab') && session()->has('active_tab') == 'contact_tab' ? 'active' : ''}}" id="contact-history-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="10%">Sr. no</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Instagram handle</th>
                                        <th>Quote</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($contact_histories as $index => $contact_history)
                                        <tr>
                                            <td>{{++$index}}</td>
                                            <td>{{ $contact_history->name }}</td>
                                            <td>{{ $contact_history->email }}</td>
                                            <td>{{ $contact_history->instagram_handle }}</td>
                                            <td>{{ $contact_history->quote }}</td>
                                            <td>{{ $contact_history->status }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <button type="button" class="btn btn-image edit-blogger"
                                                            data-toggle="modal"
                                                            data-target="#contactBloggerUpdateModal"
                                                            data-contact-blogger="{{ json_encode($contact_history) }}">
                                                        <img
                                                                src="/images/edit.png"/></button>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['contact.blogger.destroy', $contact_history->id],'style'=>'display:inline']) !!}
                                                    <button type="submit" class="btn btn-image"><img
                                                                src="/images/delete.png"/></button>
                                                    {!! Form::close() !!}
                                                    <button type="button" class="btn btn-image edit-blogger"
                                                            data-toggle="modal"
                                                            data-target="#createBloggerModal"
                                                            data-contact-blogger="{{ json_encode($contact_history) }}" title="Add Blogger">
                                                        <i class="fa fa-plus"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <th colspan="7" class="text-center text-danger">No Blogger contact
                                                information Found.
                                            </th>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')


    {!! $bloggers->appends(Request::except('page'))->links() !!}
    @include('blogger.partials.contact-blogger-modals')
    @include('blogger.partials.contact-blogger-update-modals')
    @include('blogger.partials.blogger-form-modals')
    @include('blogger.partials.blogger-product-form-modals')
    @include('blogger.partials.blogger-image-modals')
    @include('partials.modals.remarks')
@endsection

@section('scripts')
    <script>
        $('#createBloggerModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var contact_blogger = button.data('contact-blogger')
            var blogger = button.data('blogger')
            if(blogger != undefined){
                var url = "{{ url('blogger') }}/" + blogger.id;
                modal.find('form').attr('action', url);
                var method = '<input type="hidden" name="_method" value="PUT">'
                modal.find('form').append(method)
                modal.find('input[name="_method"]').val('PUT');
                modal.find('#name').val(blogger.name)
                modal.find('#phone').val(blogger.phone)
                modal.find('#email').val(blogger.email)
                modal.find('#agency').val(blogger.agency)
                modal.find('#city').val(blogger.city)
                modal.find('#country').val(blogger.country)
                modal.find('#instagram_handle').val(blogger.instagram_handle)
                modal.find('#followers').val(blogger.followers)
                modal.find('#followings').val(blogger.followings)
                modal.find('#fake_followers').val(blogger.fake_followers)
                modal.find('#avg_engagement').val(blogger.avg_engagement)
                modal.find('#industry').val(blogger.industry)
                var options = modal.find('#brands option');
                selectOptions(options, blogger.brands)
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update a Blogger')
            }else{
                var url = "{{ route('blogger.store') }}";
                modal.find('form').attr('action', url);
                modal.find('form').trigger('reset');
                var options = modal.find('#brands option');
                selectOptions(options, [])
                if (contact_blogger != undefined) {
                    modal.find('#name').val(contact_blogger.name)
                    modal.find('#email').val(contact_blogger.email)
                    modal.find('#instagram_handle').val(contact_blogger.instagram_handle)
                }
                modal.find('button[type="submit"]').html('Add')
                modal.find('.modal-title').html('Store a Blogger')
                modal.find('input[name="_method"]').remove()
            }
        })

        function selectOptions(options, toselect){
               for(i=0; i < options.length; i++){
                   if(toselect.indexOf(options[i].value) != -1){
                       $(options[i]).attr('selected', 'true')
                   }else{
                       $(options[i]).removeAttr('selected')
                   }
               }
        }

        $('#updateBloggerProductModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var blogger_product = button.data('blogger-product')
            if(blogger_product != undefined){
                var url = "{{ url('blogger-product') }}/" + blogger_product.id;
                modal.find('form').attr('action', url);
                modal.find('#blogger_id option[value="' + blogger_product.blogger_id + '"]').attr('selected', 'true')
                modal.find('#brand_id option[value="' + blogger_product.brand_id + '"]').attr('selected', 'true')
                modal.find('#shoot_date').val(blogger_product.shoot_date)
                modal.find('#first_post').val(blogger_product.first_post)
                modal.find('#second_post').val(blogger_product.second_post)
                modal.find('#first_post_likes').val(blogger_product.first_post_likes)
                modal.find('#first_post_engagement').val(blogger_product.first_post_engagement)
                modal.find('#first_post_response').val(blogger_product.first_post_response)
                modal.find('#first_post_sales').val(blogger_product.first_post_sales)
                modal.find('#second_post_likes').val(blogger_product.second_post_likes)
                modal.find('#second_post_engagement').val(blogger_product.second_post_engagement)
                modal.find('#second_post_response').val(blogger_product.second_post_response)
                modal.find('#second_post_sales').val(blogger_product.second_post_sales)
                modal.find('#city').val(blogger_product.city)
                modal.find('#initial_quote').val(blogger_product.initial_quote)
                modal.find('#final_quote').val(blogger_product.final_quote)
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update a Blogger Detail')
            }
        })

        $('#contactBloggerUpdateModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var contact_blogger = button.data('contact-blogger')
            if (contact_blogger != undefined) {
                var url = "{{ url('blogger-contact') }}/" + contact_blogger.id;
                modal.find('form').attr('action', url);
                modal.find('#name').val(contact_blogger.name)
                modal.find('#email').val(contact_blogger.email)
                modal.find('#instagram_handle').val(contact_blogger.instagram_handle)
                modal.find('#quote').val(contact_blogger.quote)
                modal.find('#status option[value="' + contact_blogger.status + '"]').attr('selected', 'true')
                modal.find('button[type="submit"]').html('Update')
                modal.find('.modal-title').html('Update a Contact History')
            }
        })

        $('#bloggerImageModal').on('hidden.bs.modal', function (event) {
            $(".blogger_images").html(' ')
        });
        $('#bloggerImageModal').on('show.bs.modal', function (event) {
            var modal = $(this)
            var button = $(event.relatedTarget)
            var blogger_images = button.data('blogger-product-images')
            var blogger_product_id = button.data('blogger-product-id')
            //upload image url
            var url = "{{ url('blogger-product-image') }}/" + blogger_product_id;
            modal.find('form').attr('action', url);

            //render images
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('blogger-product-get-image') }}/" + blogger_product_id,
                type: "GET",
            }).done(response => {
                var html = '';
                $.each(response, function (index, value) {
                    html += " <div class='col-lg-4 col-md-4'>  " +
                        "<img src='/uploads/"+value.directory+"/"+value.filename+"."+value.extension+"' width='100%' class='img img-responsive img-thumbnail'/>"+
                        "</div>";

                })
                $(".blogger_images").html(html);
            }).fail(function (response) {
                console.log(response);
                alert('Could not fetch images');
            });
        })
        //post images
        $('#bloggerImageModal form').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: $(this).attr('action'),
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
            }).done(response => {
                //work on response
                $.each(response, function(index,value){
                    var html = '';
                    $.each(response, function (index, value) {
                        html += " <div class='col-lg-4 col-md-4'>  " +
                            "<img src='/uploads/"+value.directory+"/"+value.filename+"."+value.extension+"' width='100%' class='img img-responsive img-thumbnail'/>"+
                            "</div>";

                    })
                    $(".blogger_images").append(html);
                })
                this.reset();
            }).fail(function (response) {
                console.log(response);
                alert('Could not store images');
            });
        })

        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        $(document).on('click', '.send-message', function () {
            var thiss = $(this);
            var data = new FormData();
            var blogger_id = $(this).data('bloggerId');
            var message = $(this).siblings('input').val();

            data.append("blogger_id", blogger_id);
            data.append("message", message);
            data.append("status", 1);

            if (message.length > 0) {
                if (!$(thiss).is(':disabled')) {
                    $.ajax({
                        url: '/whatsapp/sendMessage/blogger',
                        type: 'POST',
                        "dataType": 'json',           // what to expect back from the PHP script, if anything
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data": data,
                        beforeSend: function () {
                            $(thiss).attr('disabled', true);
                        }
                    }).done(function (response) {
                        $(thiss).siblings('input').val('');

                        $(thiss).attr('disabled', false);
                    }).fail(function (errObj) {
                        $(thiss).attr('disabled', false);

                        alert("Could not send message");
                        console.log(errObj);
                    });
                }
            } else {
                alert('Please enter a message first');
            }
        });
        $('#show-form').on('click', function () {
            $('#add_payment').css('display', 'block')
        })


        $(document).on('click', '.make-remark', function (e) {
            e.preventDefault();

            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "blogger_product"
                },
            }).done(response => {
                var html = '';

                $.each(response, function (index, value) {
                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#makeRemarkModal").find('#remark-list').html(html);
            });
        });
        $('#addRemarkButton').on('click', function () {
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark').find('textarea[name="remark"]').val();

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'blogger_product'
                },
            }).done(response => {
                $('#add-remark').find('textarea[name="remark"]').val('');

                var html = ' <p> ' + remark + ' <br> <small>By You updated on ' + moment().format('DD-M H:mm') + ' </small></p>';

                $("#makeRemarkModal").find('#remark-list').append(html);
            }).fail(function (response) {
                console.log(response);

                alert('Could not fetch remarks');
            });
        });

    </script>
@endsection
