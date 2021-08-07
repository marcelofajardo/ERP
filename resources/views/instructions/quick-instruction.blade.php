@extends('layouts.modal')

@section('title', 'Quick instruction modal')

@section("styles")
@endsection

@section('content')
    <div class="container">
        <h1 style="display: inline-block;">Quick Instruction</h1> 
        <?php if ($skippedCount) { ?>
            <span>skipped (<a href="?skippedCount=1">{{$skippedCount}}</a>)</span>
        <?php } ?>
        @if ( $instruction != null && isset($instruction->customer->id) )
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Number</th>
                            <th>Category</th>
                            <th>Instructions</th>
                            <th colspan="4" class="text-center">Action</th>
                            <th>Created at</th>
                            <th>Remark</th>
                        </tr>
                        <tr>
                            <td>
                                <span data-twilio-call data-context="customers" data-id="{{ $instruction->customer->id }}">{{ $instruction->customer->phone }}</span>
                            </td>
                            <td>{{ $instruction->category ? $instruction->category->name : 'Non Existing Category' }}</td>
                            <td>{{ $instruction->instruction }}</td>
                            <td>
                                @if ($instruction->completed_at)
                                    {{ Carbon\Carbon::parse($instruction->completed_at)->format('d-m H:i') }}
                                @else
                                    <a href="#" class="btn-link complete-call" data-id="{{ $instruction->id }}" data-assignedfrom="{{ $instruction->assigned_from }}">Complete</a>
                                @endif
                            </td>
                            <td>
                                @if ($instruction->completed_at)
                                    Completed
                                @else
                                    @if ($instruction->pending == 0)
                                        <a href="#" class="btn-link pending-call" data-id="{{ $instruction->id }}">Mark as Pending</a>
                                    @else
                                        Pending
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn-link skipped-call" data-id="{{ $instruction->id }}">skipp ({{$instruction->skipped_count}})</a>
                            </td>
                            <td>
                                @if ($instruction->verified == 1)
                                    <span class="badge">Verified</span>
                                @elseif ($instruction->assigned_from == Auth::id() && $instruction->verified == 0)
                                    <a href="#" class="btn btn-xs btn-secondary verify-btn" data-id="{{ $instruction->id }}">Verify</a>
                                @else
                                    <span class="badge">Not Verified</span>
                                @endif
                            </td>
                            <td>{{ $instruction->created_at->diffForHumans() }}</td>
                            <td>
                                <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $instruction->id }}">Add</a>
                                <span> | </span>
                                <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $instruction->id }}">View</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="row customer-raw-line">
                        <div class="col-12 d-inline form-inline">
                            <h3 style="display: inline" >Customer</h3>
                            <a class="btn btn-secondary send-whatsapp" href="javascript:;">Send WhatsApp</a>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <tr>
                                    <td width="20%">ID</td>
                                    <td>{{ $instruction->customer->id}}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $instruction->customer->name }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $instruction->customer->address }}</td>
                                </tr>
                                <tr>
                                    <td>Shoe size</td>
                                    <td>{{ $instruction->customer->shoe_size }}</td>
                                </tr>
                                <tr>
                                    <td>Clothing size</td>
                                    <td>{{ $instruction->customer->clothing_size }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12 mb-3 ">
                            <table class="table table-bordered" style="border: 1px solid #ddd;">
                                <tr>
                                    <th width="27%">Next Action</th>
                                    <th width="30%">Communication</th>
                                    <th>Shortcuts</th>
                                </tr>
                                <tr>
                                    <td>
                                         <div class="row_next_action">
                                            <div>
                                                <input style="width: 87%;display: inline;" type="text" name="add_next_action" placeholder="Add New Next Action" class="form-control mb-3 add_next_action_txt">
                                                <button class="btn btn-secondary add_next_action" style="position: absolute; margin-left: 6px; padding-right: 5px; padding-left: 5px;">+</button>
                                            </div>
                                            <div>
                                                <div style="float: left; width: 88%">
                                                    <select name="next_action" class="form-control next_action" data-id="{{$customer->id}}">
                                                        <option value="">Select Next Action</option> 
                                                        <?php foreach ($nextActionArr as $value => $option) { ?>
                                                            <option value="{{$value}}" {{$value == $customer->customer_next_action_id ? 'selected' : ''}}>{{$option}}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div style="float: right; width: 12%;">
                                                    <a class="btn btn-image delete_next_action" style="padding-left: 5px;"><img src="/images/delete.png"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div style="padding-bottom: 5px;">
                                                <input style="width: 92%; display: inline;" type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="">
                                                <button style="position: absolute; padding-left: 3px;" class="btn btn-sm btn-image send-message" data-customerid="{{ $customer->id }}"><img src="/images/filled-sent.png"/></button>
                                            </div>
                                            <div style="padding-bottom: 5px;">
                                                <div  class="communication-div-{{$customer->id}}">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                                $whatsApp = $customer->whatsAppAll()->first();
                                                                if ($whatsApp) {
                                                                    $message = trim($whatsApp->message);
                                                                    if($message != '') {
                                                                        echo trim($message);
                                                                    }
                                                                    echo '<button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="' . $customer->id . '" data-load-type="text" data-all="1" data-is_admin="'. Auth::user()->hasRole('Admin') .'" data-is_hod_crm="'.Auth::user()->hasRole('HOD of CRM').'" title="Load messages"><img src="/images/chat.png" alt=""></button>';
                                                                    echo '<button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="' . $customer->id . '" data-attached="1" data-load-type="images" data-all="1" data-is_admin="'. Auth::user()->hasRole('Admin') .'" data-is_hod_crm="'.Auth::user()->hasRole('HOD of CRM').'" title="Load Auto Images attacheds"><img src="/images/archive.png" alt=""></button>';
                                                                     echo '<button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-id="' . $customer->id . '" data-attached="1" data-load-type="pdf" data-all="1" data-is_admin="'. Auth::user()->hasRole('Admin') .'" data-is_hod_crm="'.Auth::user()->hasRole('HOD of CRM').'" title="Load PDF"><img src="/images/icon-pdf.svg" alt=""></button>';
                                                                }
                                                            ?>
                                                            @if ($customer->is_error_flagged == 1)
                                                                <span class="btn btn-image"><img src="/images/flagged.png"/></span>
                                                            @endif
                                                         </div>   
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <select class="multiselect-2" name="group" id="group{{ $customer->id }}" multiple data-placeholder="Select Group" style="width: 92%; display: inline;">
                                                    @foreach($groups as $group)
                                                        <option value="{{ $group->id }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }}@endif</option>
                                                    @endforeach
                                                </select>
                                                <button style="position: absolute; padding-left: 3px;" class="btn btn-sm btn-image send-group " data-customerid="{{ $customer->id }}"><img src="/images/filled-sent.png"></button>
                                            </div>      
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row communication">
                                            <div class="col-6 d-inline form-inline">
                                                <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                                                <button class="btn btn-secondary quick_category_add" style="position: absolute;  margin-left: 8px;">+</button>
                                            </div>
                                            <div class="col-6 d-inline form-inline">
                                                <div style="float: left; width: 86%">
                                                    <select name="quickCategory" class="form-control mb-3 quickCategory">
                                                        <option value="">Select Category</option>
                                                        @foreach($reply_categories as $category)
                                                            <option value="{{ $category->approval_leads }}" data-id="{{$category->id}}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div style="float: right; width: 14%;">
                                                    <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
                                                </div>
                                            </div>
                                            <div class="col-6 d-inline form-inline">
                                                <input style="width: 87%" type="text" name="quick_comment" placeholder="Enter New Quick Comment" class="form-control mb-3 quick_comment">
                                                <button class="btn btn-secondary quick_comment_add" style="position: absolute;  margin-left: 8px;">+</button>
                                            </div>
                                            <div  class="col-6 d-inline form-inline">
                                                <div style="float: left; width: 86%">
                                                    <select name="quickComment" class="form-control quickComment">
                                                        <option value="">Quick Reply</option>
                                                    </select>
                                                </div>
                                                <div style="float: right; width: 14%;">
                                                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Send images">
                                                    <input type="hidden" name="category_id" value="6">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['image_shortcut'] }}">
                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Images"><img src="/images/attach.png"/></button>
                                                </form>
                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Send price">
                                                    <input type="hidden" name="category_id" value="3">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['price_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Send Price"><img src="/images/price.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="{{ $users_array[$settingShortCuts['call_shortcut']] }} call this client">
                                                    <input type="hidden" name="category_id" value="10">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['call_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Call this Client"><img src="/images/call.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Attach image">
                                                    <input type="hidden" name="category_id" value="8">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['screenshot_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Images"><img src="/images/upload.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Attach screenshot">
                                                    <input type="hidden" name="category_id" value="12">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['screenshot_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Attach Screenshot"><img src="/images/screenshot.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Give details">
                                                    <input type="hidden" name="category_id" value="14">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['details_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Give Details"><img src="/images/details.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Check for the Purchase">
                                                    <input type="hidden" name="category_id" value="7">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['purchase_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Check for the Purchase"><img src="/images/purchase.png"/></button>
                                                </form>

                                                <form class="d-inline" action="{{ route('instruction.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="hidden" name="instruction" value="Please Show Client Chat">
                                                    <input type="hidden" name="category_id" value="13">
                                                    <input type="hidden" name="assigned_to" value="{{ $settingShortCuts['purchase_shortcut'] }}">

                                                    <button type="submit" class="btn btn-image quick-shortcut-button" title="Show Client Chat"><img src="/images/chat.png"/></button>
                                                </form>
                                                <div class="d-inline">
                                                    <button type="button" class="btn btn-image btn-broadcast-send" data-id="{{ $customer->id }}">
                                                        <img src="/images/broadcast-icon.png"/>
                                                    </button>
                                                </div>
                                                <div class="d-inline">
                                                    <a href="{{ route('customer.download.contact-pdf',[$customer->id]) }}" target="_blank">
                                                      <button type="button" class="btn btn-image"><img src="/images/download.png" /></button>
                                                    </a>
                                                </div>
                                                <div class="d-inline">
                                                    <button type="button" class="btn btn-image send-instock-shortcut" data-id="{{ $customer->id }}">Send In Stock</button>
                                                </div>
                                                <div class="d-inline">
                                                    <button type="button" class="btn btn-image latest-scraped-shortcut" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#categoryBrandModal" style="padding: 6px 0px !important">Send 20 Scraped</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Instruction</h3>
                    <span style="background-color: #FFFF00; padding: 3px; font-size: 1.5em;">{!! nl2br($instruction->instruction) !!}</span>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachImagesModal">
                        Attach Images
                    </button>
                    <h3>Chat</h3>
                    <div id="chat-history" class="load-communication-modal" data-object="customer" data-all="1" data-attached="1" data-id="{{ $instruction->customer_id }}" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" style="max-height: 80vh; overflow-x: hidden; overflow-y: scroll;">
                    </div>
                </div>
            </div>
        @elseif ( isset($instruction->id) )
            <h2>No customer found (code: {{ $instruction->id }})</h2>
        @else
            <h2>No more instructions</h2>
        @endif
    </div>

    <div class="attachImagesModal modal fade" style="width: 95vw; height: 95vh;" id="attachImagesModal" tabindex="-1" role="dialog" aria-labelledby="attachImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Attach Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="iFrameModal"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div id="confirmPdf" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <p>Choose the format for sending</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-approve-pdf">PDF</button>
            <button type="button" class="btn btn-secondary btn-ignore-pdf">Images</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="sendCustomerMessage" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Customer Message</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row communication customer-raw-line">
                            <div class="col-12 mb-3">
                                <input  type="text" class="form-control quick-message-field customer-raw-line" name="message" placeholder="Message" value="">
                            </div>
                            <div class="col-6 d-inline form-inline">
                                <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                                <a class="btn btn-secondary quick_category_add" style="position: absolute;  margin-left: 8px;">+</a>
                            </div>
                            <div class="col-6 d-inline form-inline">
                                <div style="float: left; width: 86%">
                                    <select name="quickCategory" class="form-control mb-3 quickCategory">
                                        <option value="">Select Category</option>
                                        @foreach($reply_categories as $category)
                                            <option value="{{ $category->approval_leads }}" data-id="{{$category->id}}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="float: right; width: 14%;">
                                    <a class="btn btn-image delete_category"><img src="/images/delete.png"></a>
                                </div>
                            </div>
                            <div class="col-6 d-inline form-inline">
                                <input style="width: 87%" type="text" name="quick_comment" placeholder="Enter New Quick Comment" class="form-control mb-3 quick_comment">
                                <a class="btn btn-secondary quick_comment_add" style="position: absolute;  margin-left: 8px;">+</a>
                            </div>
                            <div class="col-6 d-inline form-inline">
                                <div style="float: left; width: 86%">
                                    <select name="quickComment" class="form-control quickComment">
                                        <option value="">Quick Reply</option>
                                    </select>
                                </div>
                                <div style="float: right; width: 14%;">
                                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                                </div>
                            </div>
                            <div class="col-12 mb-3 form-group">
                                <?php
                                 echo Form::select('brand',$brands->pluck('name', 'id'), ( old('brand') ? old('brand') : '' ), ['placeholder' => 'Select a brand','class' => 'form-control', 'id'  => 'product-brand']);?>
                                  @if ($errors->has('brand'))
                                      <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                                  @endif
                            </div>
                            <div class="col-12 form-group mr-3 mb-3">
                                {{-- {!! $category_search !!} --}}
                                <select class="form-control" id="category" name="category">
                                    @foreach ($category_array as $data)
                                        <option value="{{ $data['id'] }}" {{ in_array($data['id'], []) ? 'selected' : '' }}>{{ $data['title'] }}</option>
                                        @if ($data['title'] == 'Men')
                                            @php
                                                $color = "#D6EAF8";
                                            @endphp
                                        @elseif ($data['title'] == 'Women')
                                            @php
                                                $color = "#FADBD8";
                                            @endphp
                                        @else
                                            @php
                                                $color = "";
                                            @endphp
                                        @endif

                                        @foreach ($data['child'] as $children)
                                            <option style="background-color: {{ $color }};" value="{{ $children['id'] }}" {{ in_array($children['id'], []) ? 'selected' : '' }}>&nbsp;&nbsp;{{ $children['title'] }}</option>

                                            @foreach ($children['child'] as $child)
                                                <option style="background-color: {{ $color }};" value="{{ $child['id'] }}" {{ in_array($child['id'], []) ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child['title'] }}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 d-inline form-inline">
                                <input style="width: 87%" type="text" id="number_of_products" name="number_of_products" placeholder="Enter Number of products" class="form-control mb-3 number_of_products">
                            </div>
                            <div class="col-12 mb-3 form-group">
                                <?php
                                $quick_sell_groups = \App\QuickSellGroup::orderBy('id', 'desc')->pluck('name','id');
                                 echo Form::select('quick_sell_groups[]',$quick_sell_groups, '', ['placeholder' => 'Select a quick sell groups','class' => 'form-control select-multiple', 'id'  => 'product-quick-sell-groups', 'multiple' => 'multiple']);?>
                                  @if ($errors->has('brand'))
                                      <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                                  @endif
                            </div>
                            <div class="col-12 mb-3">
                                Attached Images 
                                <a herf="javascript:;" class="btn btn-image send-message-with-attach-images" title="Send Images"><img src="/images/attach.png"></a>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default send-message-malti-customer" data-id="{{$customer ? $customer->id : ''}}">Send WhatsApp</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('customers.partials.modal-category-brand')
    <style>
        iframe {
            margin: 0px auto;
            border: none;
            width: 100% !important;
            height: 100% !important;
        }

        .attachImagesModal {
            margin: 0px auto;
            width: 95vw;
        }

        .attachImagesModal .modal-dialog {
            width: 100vw;
            height: 95vh;
            margin: 0;
            padding: 0;
        }

        .attachImagesModal .modal-content {
            height: auto;
            min-height: 95vh;
            width: 95vw;
            border-radius: 0;
        }

        .attachImagesModal .modal-body {
            height: 80vh !important;
        }
    </style>

    @include('customers.partials.modal-remark')
@endsection

@section('scripts')
    @if ($instruction != null && isset($instruction->customer->id) )
        <script type="text/javascript" src="/js/common-helper.js"></script>
        <script>
            $(document).on('click', '.skipped-call', function (e) {
                e.preventDefault();

                var thiss = $(this);
                var url = "{{route('instruction.skipped.count')}}";
                var id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    },
                    beforeSend: function () {
                        $(thiss).text('Loading');
                    }
                }).done(function (response) {
                    location.reload();
                }).fail(function (errObj) {
                    console.log(errObj);
                    alert("Could not mark as completed");
                });
            });
            $('.multiselect-2').select2({width:'92%'});

            var customer_id = {{ $instruction->customer->id }};
            var current_user = {{ Auth::id() }};
            var route = [];
            route.instruction_complete = "{{ route('instruction.complete') }}";
            route.instruction_pending = "{{ route('instruction.pending') }}";
            route.leads_store = "{{ route('leads.store') }}";
            route.leads_send_prices = "{{ route('leads.send.prices') }}";
            route.task_add_remark = "{{ route('task.addRemark') }}";
            route.task_get_remark = "{{ route('task.gettaskremark') }}";
            $('#add-remark input[name="id"]').val({{ $instruction->id }});
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('iframe').attr('src', '/attachImages/customer/{{ $instruction->customer->id }}/1')
                // $(this).find('iframe').attr('src', '/attachImages/customer/44/1')
            });
            $(document).ready(function () {
                $('#chat-history').trigger('click');
            });
            $('#iFrameModal').on('load', function () {
                // console.log(window.location.protocol + '//' + document.domain + '/customer/44');
                // console.log(document.getElementById("iFrameModal").contentWindow.location.href);
                if (document.getElementById("iFrameModal").contentWindow.location.href == window.location.protocol + '//' + document.domain + '/customer/{{ $instruction->customer->id }}') {
                    $(function () {
                        $('#attachImagesModal').modal('toggle');
                    });
                }
            });

            $('.multiselect-2').select2({width:'92%'});
            $('.select-multiple').select2({width: '100%'});
            $('.select-multiple').select2({width: '100%'});
            var siteHelpers = {
                addNextAction : function(ele) {
                    var textBox = ele.closest(".row_next_action").find(".add_next_action_txt");

                    if (textBox.val() == "") {
                        alert("Please Enter New Next Action!!");
                        return false;
                    }

                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            name : textBox.val()
                        },
                        doneAjax : function(response) {
                            toastr['success']('Successfully add!');
                            textBox.val('');
                            $(".next_action").append('<option value="'+response.id+'">' + response.name + '</option>');
                        },
                        url: "/erp-customer/add-next-actions"
                    };
                    siteHelpers.sendAjax(params);
                },
                deleteNextAction : function(ele) {
                    var nextAction = ele.closest(".row_next_action").find(".next_action");

                    if (nextAction.val() == "") {
                        alert("Please Select Next Action!!");
                        return false;
                    }

                    var nextActionId = nextAction.val();
                    if (!confirm("Are sure you want to delete Next Action?")) {
                        return false;
                    }

                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            id : nextActionId
                        },
                        url: "/erp-customer/destroy-next-actions"
                    };
                    siteHelpers.sendAjax(params,"pageReload");
                },
                pageReload : function(response) {
                    location.reload();
                },
                changeNextAction :  function(ele) {
                    var params = {
                        method : 'post',
                        data : {
                            customer_next_action_id: ele.val(),
                            _token  : $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/erp-customer/"+ele.data('id')+"/update",
                        doneAjax : function(response) {
                            toastr['success']('Next Action changed successfully!', 'Success');
                        },
                    };
                    siteHelpers.sendAjax(params);
                },
                sendMessage : function(ele){
                    var message = ele.siblings('input').val();
                    var customer_id = ele.data('customerid');
                    if (message.length > 0 && !ele.is(':disabled')) {

                        var data = new FormData();

                        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                        data.append("customer_id", customer_id);
                        data.append("message", message);
                        data.append("status", 1);

                        var params = {
                            method : 'post',
                            data : data,
                            url: '/whatsapp/sendMessage/customer',
                            beforeSend : function() {
                                ele.attr('disabled', true);
                            },
                            doneAjax : function(response) {
                                $('.load-communication-modal').trigger('click');
                                ele.siblings('input').val('');
                                ele.attr('disabled', false);
                            }
                        };
                        siteHelpers.sendFormDataAjax(params);
                    }
                },
                sendGroup : function(ele, send_pdf) {
                    $("#confirmPdf").modal("hide");
                    var customerId = ele.data('customerid');
                    var groupId = $('#group' + customerId).val();
                    var params = {
                        method : 'post',
                        data : {
                            groupId: groupId,
                            customerId: customerId,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            status: 2,
                            send_pdf: send_pdf
                        },
                        url: "/whatsapp/sendMessage/quicksell_group_send"
                    };
                    siteHelpers.sendAjax(params,"afterSendGroup", ele);
                },
                afterSendGroup : function(ele) {
                    $('#group' + ele.data('customerid')).val('').trigger('change');
                    toastr["success"]("Group Message sent successfully!", "Message");
                },
                quickCategoryAdd : function(ele) {
                    var textBox = ele.closest("div").find(".quick_category");

                    if (textBox.val() == "") {
                        alert("Please Enter Category!!");
                        return false;
                    }

                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            name : textBox.val()
                        },
                        url: "/add-reply-category"
                    };
                    siteHelpers.sendAjax(params,"afterQuickCategoryAdd");
                },
                afterQuickCategoryAdd : function(response) {
                    $(".quick_category").val('');
                    $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
                },
                deleteQuickCategory : function(ele) {
                    var quickCategory = ele.closest(".communication").find(".quickCategory");

                    if (quickCategory.val() == "") {
                        alert("Please Select Category!!");
                        return false;
                    }

                    var quickCategoryId = quickCategory.children("option:selected").data('id');
                    if (!confirm("Are sure you want to delete category?")) {
                        return false;
                    }

                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            id : quickCategoryId
                        },
                        url: "/destroy-reply-category"
                    };
                    siteHelpers.sendAjax(params,"pageReload");
                },
                quickCommentAdd : function(ele) {
                    var textBox = ele.closest("div").find(".quick_comment");
                    var quickCategory = ele.closest(".communication").find(".quickCategory");

                    if (textBox.val() == "") {
                        alert("Please Enter New Quick Comment!!");
                        return false;
                    }

                    if (quickCategory.val() == "") {
                        alert("Please Select Category!!");
                        return false;
                    }

                    var quickCategoryId = quickCategory.children("option:selected").data('id');

                    var formData = new FormData();

                    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                    formData.append("reply", textBox.val());
                    formData.append("category_id", quickCategoryId);
                    formData.append("model", 'Approval Lead');

                    var params = {
                        method : 'post',
                        data : formData,
                        url: "/reply"
                    };
                    siteHelpers.sendFormDataAjax(params,"afterQuickCommentAdd");
                },
                afterQuickCommentAdd : function(reply) {
                    $(".quick_comment").val('');
                    $('.quickComment').append($('<option>', {
                        value: reply,
                        text: reply
                    }));
                },
                changeQuickCategory : function (ele) {
                    if (ele.val() != "") {
                        var replies = JSON.parse(ele.val());
                        ele.closest(".communication").find('.quickComment').empty();
                        ele.closest(".communication").find('.quickComment').append($('<option>', {
                            value: '',
                            text: 'Quick Reply'
                        }));

                        replies.forEach(function (reply) {
                            ele.closest(".communication").find('.quickComment').append($('<option>', {
                                value: reply.reply,
                                text: reply.reply,
                                'data-id': reply.id
                            }));
                        });
                    }
                },
                changeQuickComment : function (ele) {
                    ele.closest('.customer-raw-line').find('.quick-message-field').val(ele.val());
                },
                deleteQuickComment : function(ele) {
                    var quickComment = ele.closest(".communication").find(".quickComment");

                    if (quickComment.val() == "") {
                        alert("Please Select Quick Comment!!");
                        return false;
                    }

                    var quickCommentId = quickComment.children("option:selected").data('id');
                    if (!confirm("Are sure you want to delete comment?")) {
                        return false;
                    }

                    var params = {
                        method : 'DELETE',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "/reply/" + quickCommentId,
                    };
                    siteHelpers.sendAjax(params,"pageReload");
                },
                instructionStore : function(ele) {
                    var customer_id = ele.closest('form').find('input[name="customer_id"]').val();
                    var instruction = ele.closest('form').find('input[name="instruction"]').val();
                    var category_id = ele.closest('form').find('input[name="category_id"]').val();
                    var assigned_to = ele.closest('form').find('input[name="assigned_to"]').val();
                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            customer_id: customer_id,
                            instruction: instruction,
                            category_id: category_id,
                            assigned_to: assigned_to,
                        },
                        url: ele.closest('form').attr('action')
                    };
                    siteHelpers.sendAjax(params);
                },
                sendInstock : function(ele) {
                    var customer_id = ele.data('id');
                    var params = {
                        method : 'post',
                        data : {
                            _token : $('meta[name="csrf-token"]').attr('content'),
                            customer_id: customer_id
                        },
                        url: "/customer/send/instock",
                        beforeSend : function() {
                            ele.text('Sending...');
                        },
                        doneAjax : function(response) {
                            ele.text('Send In Stock');
                        },
                    };
                    siteHelpers.sendAjax(params);
                },
                sendScraped : function (ele) {
                    var formData = $('#categoryBrandModal').find('form').serialize();
                    var thiss = ele;

                    if (!ele.is(':disabled')) {
                        var params = {
                            method : 'post',
                            dataType: "html",
                            data : formData,
                            url: "/customer/sendScraped/images",
                            beforeSend : function() {
                                ele.text('Sending...');
                                ele.attr('disabled', true);
                            },
                            doneAjax : function(response) {
                                $('#categoryBrandModal').find('.close').click();
                                ele.text('Send');
                                ele.attr('disabled', false);
                            },
                        };
                        siteHelpers.sendAjax(params);
                    }
                },
                sendMessageMaltiCustomer : function(ele){
                    var form = ele.closest('form');

                    var message = form.find('.quick-message-field').val();

                    if (message.length > 0 && !ele.is(':disabled')) {

                        var data = new FormData();

                        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
                        data.append("customers_id", ele.data('id'));
                        data.append("message", message);
                        data.append("status", 1);
                        data.append("brand", form.find("#product-brand").val());
                        data.append("category", form.find("#category").val());
                        data.append("number_of_products", form.find("#number_of_products").val());
                        

                        var params = {
                            method : 'post',
                            data : data,
                            url: '/selected_customer/sendMessage',
                            beforeSend : function() {
                                ele.attr('disabled', true);
                            },
                            doneAjax : function(response) {
                                ele.attr('disabled', false);
                                $("#sendCustomerMessage").modal("hide");
                            }
                        };
                        siteHelpers.sendFormDataAjax(params);
                    }
                },
            };
            $.extend(siteHelpers, common);
            $('.add_next_action').on('click', function(e) {
                siteHelpers.addNextAction($(this));
            });

            $('.delete_next_action').on('click', function(e) {
                siteHelpers.deleteNextAction($(this));
            });

            $('.next_action').on('change', function(e) {
                siteHelpers.changeNextAction($(this));
            });

            $(document).on('click', '.send-message', function () {
                siteHelpers.sendMessage($(this));
            });

            $(document).on('click', '.send-group', function () {
                $(".btn-approve-pdf").data('customerid', $(this).data('customerid'));
                $(".btn-ignore-pdf").data('customerid', $(this).data('customerid'));
                $("#confirmPdf").modal("show");
            });

            $(".btn-approve-pdf").on("click",function() {
                siteHelpers.sendGroup($(this), 1);
            });

            $(".btn-ignore-pdf").on("click",function() {
                siteHelpers.sendGroup($(this), 0);
            });

             $(document).on('click', '.quick_category_add', function () {
                siteHelpers.quickCategoryAdd($(this));
            });

            $(document).on('click', '.delete_category', function () {
                siteHelpers.deleteQuickCategory($(this));
            });

            $(document).on('click', '.delete_quick_comment', function () {
                siteHelpers.deleteQuickComment($(this));
            });

            $(document).on('click', '.quick_comment_add', function () {
                siteHelpers.quickCommentAdd($(this));
            });

            $(document).on('change', '.quickCategory', function () {
                siteHelpers.changeQuickCategory($(this));
            });

            $(document).on('change', '.quickComment', function () {
                siteHelpers.changeQuickComment($(this));
            });

            $(document).on('click', ".quick-shortcut-button", function (e) {
                e.preventDefault();
                siteHelpers.instructionStore($(this));
            });

            $(document).on('click', '.send-instock-shortcut', function () {
                siteHelpers.sendInstock($(this));
            });

            $(document).on('click', '.latest-scraped-shortcut', function () {
                var id = $(this).data('id');

                $('#categoryBrandModal').find('input[name="customer_id"]').val(id);
            });

            $(document).on('click', "#sendScrapedButton", function (e) {
                e.preventDefault();
                siteHelpers.sendScraped($(this));
            });

            $(document).on('click', '.send-whatsapp', function () {
                $("#sendCustomerMessage").modal("show");
            });
            $(document).on('click', '.send-message-malti-customer', function () {
                siteHelpers.sendMessageMaltiCustomer($(this));
            });
            $(document).on('click', '.send-message-with-attach-images', function () {
                var message = $(this).closest('form').find('.quick-message-field').val();

                window.location.href = "/attachImages/selected_customer/{{$customer ? $customer->id : ''}}/1?return_url=instruction/quick-instruction&message="+message;
            });

            $(window).bind('unload',function(){
                $.ajax({
                    type: 'POST',
                    url: '/instruction/store-instruction-end-time',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: '{{$instructionTime ? $instructionTime->id : ''}}',
                        instructions_id: '{{$instructionTime ? $instructionTime->instructions_id : ''}}',
                    },
                    async:false
                })
            });
            
        </script>
    @endif
@endsection