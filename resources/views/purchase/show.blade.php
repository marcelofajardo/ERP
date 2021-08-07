@extends('layouts.app')

@section('title', 'Purchase Bulk Order')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
@endsection

@section('content')

<div class="row">
  <div class="col-xs-12">
    <h2 class="page-heading">Purchase Bulk Order</h2>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="pull-right form-inline">
      <form action="{{ route('purchase.export') }}" id="purchaseExportForm" method="POST">
        @csrf

        <input type="hidden" name="selected_purchases" id="selected_purchases" value="">
        <button type="submit" class="btn btn-secondary" id="purchaseExportButton">Export</button>
      </form>

      <button type="button" class="btn btn-secondary ml-1" data-toggle="modal" data-target="#sendExportModal">Email</button>
      <a class="btn btn-secondary ml-1" href="{{ route('purchase.index') }}">Back</a>
    </div>
  </div>
</div>

@include('partials.flash_messages')
@include('purchase.partials.modal-purchase')

@php $users_array = \App\Helpers::getUserArray(\App\User::all()); @endphp

<div class="row">
  <div class="col-xs-12 col-md-4">
    <div class="form-group">
      <strong>ID:</strong> {{ $order->id }}
    </div>

    <div class="form-group">
      <strong>Date:</strong> {{ Carbon\Carbon::parse($order->created_at)->format('d-m H:i') }}
    </div>

    <div class="form-group">
      <select class="form-control input-sm" name="supplier">
        <option value="">Select Supplier</option>
        @foreach ($suppliers as $supplier)
          <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
        @endforeach
      </select>
    </div>

    @if ($order->purchase_supplier)
      <div class="form-group">
        <select class="form-control input-sm" name="agent_id">
          <option value="">Select an Agent</option>
          @foreach ($order->purchase_supplier->agents as $agent)
            <option value="{{ $agent->id }}" {{ $order->agent_id == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
          @endforeach
        </select>
      </div>
    @endif

    <div class="form-group">
      @if (count($order->status_changes) > 0)
        <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

        <div class="change-history-container hidden">
          <ul>
            @foreach ($order->status_changes as $status_history)
              <li>
                {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      <Select name="status" class="form-control input-sm" id="change_status">
           @foreach($purchase_status as $key => $value)
            <option value="{{$value}}" {{$value == $order->status ? 'Selected=Selected':''}}>{{$key}}</option>
            @endforeach
      </Select>
      <span id="change_status_message" class="text-success" style="display: none;">Successfully changed status</span>
    </div>

    <div class="form-group">
      <input type="text" class="form-control input-sm" name="transaction_id" placeholder="Transaction ID" value="{{ $order->transaction_id }}">
    </div>

    <div class="form-group">
      <div class='input-group date' id='transaction-datetime'>
        <input type='text' class="form-control input-sm" name="transaction_date" placeholder="Transaction Date" value="{{ $order->transaction_date }}" />

        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
    </div>

    <div class="form-group">
      <input type="number" class="form-control input-sm" placeholder="Transaction Amount" name="transaction_amount" value="{{ $order->transaction_amount }}">
    </div>

    {{-- style="display: {{ (isset($order->status) && $order->status != 'Ordered') ? 'block' : 'none' }}" --}}
    <div class="form-group" id="bill-wrapper">
      <input type="text" name="bill_number" class="form-control input-sm" placeholder="AWB Number" value="{{ $order->bill_number }}">
    </div>

    <div class="form-group">
      <input type="text" class="form-control input-sm" placeholder="Shipper" name="shipper" value="{{ $order->shipper }}">
    </div>

    <div class="form-group">
      <input type="number" class="form-control input-sm" placeholder="Shipment Cost" name="shipment_cost" value="{{ $order->shipment_cost }}">
    </div>

    <div class="form-group">
      <div class='input-group date' id='shipment-datetime'>
        <input type='text' class="form-control input-sm" name="shipment_date" placeholder="Shipment Date" value="{{ $order->shipment_date }}" />

        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
    </div>

    <div class="form-group">
      <input type="text" class="form-control input-sm" placeholder="Shipment Status" name="shipment_status" value="{{ $order->shipment_status }}">
    </div>

    @if ($order->files)
      <div class="form-group">
        <strong>Uploaded Files:</strong>
        <ul>
          @foreach ($order->files as $file)
            <li>
              <form action="{{ route('purchase.file.download', $file->id) }}" method="POST">
                @csrf

                <button type="submit" class="btn-link">{{ $file->filename }}</button>
              </form>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-group">
      <strong>Upload Files:</strong>
      <input type="file" name="files[]" id="uploaded_files" multiple>
    </div>

    <div class="form-group">
      <a href="#" class="btn btn-secondary save-bill">Save</a>
      <span id="save_status" class="text-success" style="display: none;">Successfully saved!</span>
    </div>

    <div class="form-group">
      <strong>Customers List</strong>

      <form action="{{ route('purchase.assign.batch', $order->id) }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-xs btn-secondary">Assign Batch Number All</button>
      </form>

      <form class="mt-3" action="{{ route('purchase.assign.split.batch', $order->id) }}" method="POST">
        @csrf
        <input type="hidden" name="order_products" id="selected_order_products" value="">

        <button type="submit" class="btn btn-xs btn-secondary">Assign Split Batch</button>
      </form>

      @php
        $letters_array = [
          '1' => 'A',
          '2' => 'B',
          '3' => 'C',
          '4' => 'D',
          '5' => 'E',
          '6' => 'F',
          '7' => 'G',
        ];
      @endphp

      <ul>
        @foreach ($order->products as $product)
          @php
            $duplicates_array = [];
          @endphp
          @foreach ($product->orderproducts as $key => $order_product)
            <li>
              @if ($order_product->order && $order_product->order->customer)
                @php
                  $duplicates_array[] = $order_product->order->customer->id;
                  $dups = array();
                  foreach(array_count_values($duplicates_array) as $val => $c) {
                    if($c > 1) {
                      $duplicate = $dups[] = $val;
                    }
                  }

                @endphp

                <input type="checkbox" class="select-order-product" name="order_product" value="{{ $order_product->id }}">

                <a href="{{ route('customer.post.show', $order_product->order->customer->id) }}" target="_blank">{{ $order_product->order->customer->name }}</a>
                 - ({{ $order_product->purchase_status }})

                 @if (in_array($order_product->order->customer->id, $dups))
                   <span class="badge">Duplicate</span>
                 @endif

                 @if ($order_product->purchase_id != '')

                   <span class="badge">#{{ $order_product->purchase_id }}{{ array_key_exists($order_product->batch_number, $letters_array) ? $letters_array[$order_product->batch_number] : '' }}</span>
                 @endif
               @else
                 No Customer
               @endif
            </li>
          @endforeach
        @endforeach
      </ul>
    </div>
  </div>

  <div class="col-xs-12 col-md-4">
    <div class="row">
      <div class="col">
        @php $purchase_price = 0;
          foreach ($order->products as $product) {
            $purchase_price += round(($product->price - ($product->price * $product->percentage / 100)) / 1.22);
          }
        @endphp
        <div class="form-group">
          <strong>Purchase Price:</strong> <span id="purchase_price">{{ $purchase_price }}</span>
        </div>
      </div>
    </div>

    <div class="row">
      @foreach ($order->products as $product)
        <div class="col-md-6">
          <a href="{{ route('purchase.product.show', $product->id) }}" data-toggle='tooltip' data-html='true' data-placement='top' title="<strong>Price: </strong>{{ $product->price }}">
            <img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive" alt="">
          </a>

          <div class="form-group mt-3">
            <select class="form-control input-sm change-product-status" name="purchase_status" data-id="{{ $product->id }}">
              <option value="">Product Status</option>
              <option value="Not Available with Supplier" {{ "Not Available with Supplier" == $product->purchase_status ? 'selected' : '' }}>Not Available with Supplier</option>
            </select>

            <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>
          </div>

          <div class="form-inline mt-0">
            <a href="{{ route('attachImages', ['purchase-replace', $product->id]) }}" class="btn btn-xs btn-secondary mr-1">Rep</a>
            <a href="#" class="btn btn-xs btn-secondary mr-1 replace-product-button" data-id="{{ $product->id }}" data-toggle="modal" data-target="#createProductModal">C & R</a>

            <form action="{{ route('purchase.product.remove', $product->id) }}" method="POST">
              @csrf
              <input type="hidden" name="purchase_id" value="{{ $order->id }}">

              <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
            </form>
          </div>

          <div class="form-group">
            <strong>Purchase price:</strong> <span class="purchase-price">{{ isset($product->percentage) || isset($product->factor) ? round(($product->price - ($product->price * $product->percentage / 100)) / 1.22) : ($product->price) }}</span>
          </div>

          <div class="form-group">
            <strong>Sold Price:</strong> {{ count($product->orderproducts) > 0 ? $product->orderproducts[0]->product_price : 'No Order Product' }}
          </div>

          <div class="form-group">
            <strong>Percentage %:</strong>
            <input type="number" name="percentage" class="form-control input-sm" placeholder="10%" value="{{ $product->percentage }}" min="0" max="100" data-price="{{ $product->price }}" data-productid="{{ $product->id }}">
          </div>

        </div>
      @endforeach
    </div>

    <div class="form-group">
      {{-- <strong>Amount:</strong>
      <input type="number" name="factor" class="form-control input-sm" placeholder="1.22" value="{{ $product->factor }}" min="0" step="0.01" data-price="{{ $product->price }}"> --}}
      <a href="#" class="btn btn-secondary save-purchase-price" data-id="{{ $product->id }}">Save</a>
    </div>

    <h4>Remarks</h4>

    <div class="row">
      <div class="col-xs-12">
        <div class="form-group">
          <textarea class="form-control" name="remark" rows="3" cols="10" placeholder="Remark"></textarea>
        </div>

        <div class="form-inline">
          <button type="button" class="btn btn-xs btn-secondary" id="sendRemarkButton">Send</button>
          <button type="button" class="btn btn-xs btn-secondary ml-1" id="hideRemarksButton">Show</button>
        </div>
      </div>

      <div class="col-xs-12">

        <div id="remarks-container" class="hidden">
          <ul>

          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xs-12 col-md-4">
    <div class="row">
      <div class="col-xs-12">
        <div class="table-responsive">
          <table class="table table-bordered mb-0" id="purchaseDiscounts">
            <tbody>
              @foreach ($purchase_discounts as $date => $items)
                @php
                  if ($loop->first) {
                    $last_index = $date;
                  }
                @endphp
                <tr>
                  <td>{{ $date }}</td>

                  @foreach ($items as $id => $discounts)
                    <td>{{ $id }} - {{ $discounts[0]->percentage }} %</td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div id="discountAccordion">
          <div class="card">
            <div class="card-header" id="headingDiscount">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#discountAcc" aria-expanded="false" aria-controls="">
                  Rest of History
                </button>
              </h5>
            </div>
            <div id="discountAcc" class="collapse collapse-element" aria-labelledby="headingDiscount" data-parent="#discountAccordion">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <tbody>
                      @foreach ($purchase_discounts_rest as $date => $items)
                        <tr>
                          <td>{{ $date }}</td>

                          @foreach ($items as $id => $discounts)
                            <td>{{ $id }} - {{ $discounts[0]->percentage }} %</td>
                          @endforeach
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        @if (isset($last_index))
          <h4>Proforma</h4>

          <div class="form-group">
            <input type="text" name="proforma_id" class="form-control input-sm" placeholder="Proforma Number" value="{{ $order->proforma_id }}">
          </div>

          <div class="form-group">
            <div class='input-group date' id='proforma-datetime'>
              <input type='text' class="form-control input-sm" name="proforma_date" placeholder="Proforma Date" value="{{ $order->proforma_date }}" />

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th>Proforma</th>
                  @foreach ($purchase_discounts[$last_index] as $id => $discounts)
                    <td>
                      <input type="number" name="proforma" class="form-control input-sm" placeholder="amount" value="" data-productid="{{ $discounts[0]->product_id }}">
                    </td>
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>

          <div class="form-group">
            @if ($order->proforma_confirmed == 1)
              <span class="badge">Proforma Confirmed</span>
            @else
              <button type="button" class="btn btn-xs btn-secondary" id="confirmProformaButton">Confirm Proforma</button>
              <span class="text-success change_status_message" style="display: none;">Successfully changed status</span>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</div>


@include('purchase.partials.modal-product')
@include('purchase.partials.modal-task')



<div class="row">
  <div class="col-xs-12 col-sm-12 mb-3">
    <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#taskModal" id="addTaskButton">Add Task</button>

    @if (count($tasks) > 0)
      <table class="table">
        <thead>
          <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th class="category">Category</th>
            <th>Task Subject</th>
            <th>Est Completion Date</th>
            <th>Assigned From</th>
            <th>&nbsp;</th>
            {{-- <th>Remarks</th> --}}
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; $categories = \App\Http\Controllers\TaskCategoryController::getAllTaskCategory(); ?>
          @foreach($tasks as $task)
          <tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }}" id="task_{{ $task['id'] }}">
            <td>{{$i++}}</td>
            <td>{{ Carbon\Carbon::parse($task['created_at'])->format('d-m H:i') }}</td>
            <td> {{ isset( $categories[$task['category']] ) ? $categories[$task['category']] : '' }}</td>
            <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">{{ $task['task_subject'] ? $task['task_subject'] : 'Task Details' }}</td>
            <td> {{ Carbon\Carbon::parse($task['completion_date'])->format('d-m H:i')  }}</td>
            <td>{{ $users_array[$task['assign_from']] }}</td>
            @if( $task['assign_to'] == Auth::user()->id )
              @if ($task['is_completed'])
                <td>{{ Carbon\Carbon::parse($task['is_completed'])->format('d-m H:i') }}</td>
              @else
                <td><a href="/task/complete/{{$task['id']}}">Complete</a></td>
              @endif
            @else
              @if ($task['is_completed'])
                <td>{{ Carbon\Carbon::parse($task['is_completed'])->format('d-m H:i') }}</td>
              @else
                <td>Assigned to  {{ $task['assign_to'] ? $users_array[$task['assign_to']] : 'Nil'}}</td>
              @endif
            @endif
            <td>
              <a href id="add-new-remark-btn" class="add-task" data-toggle="modal" data-target="#add-new-remark_{{$task['id']}}" data-id="{{$task['id']}}">Add</a>
              <span> | </span>
              <a href id="view-remark-list-btn" class="view-remark" data-toggle="modal" data-target="#view-remark-list" data-id="{{$task['id']}}">View</a>
              <!--<button class="delete-task" data-id="{{$task['id']}}">Delete</button>-->
            </td>
          </tr>

          <!-- Modal -->
          <div id="add-new-remark_{{$task['id']}}" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Add New Remark</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                  <form id="add-remark">
                    <input type="hidden" name="id" value="">
                    <textarea id="remark-text_{{$task['id']}}" rows="1" name="remark" class="form-control"></textarea>
                    <button type="button" class="mt-2 " onclick="addNewRemark({{$task['id']}})">Add Remark</button>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>

          <!-- Modal -->
          <div id="view-remark-list" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View Remark</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                  <div id="remark-list">

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>



  @include('purchase.partials.modal-email')
  @include('purchase.partials.modal-recipient')





  {{-- <div class="col-xs-12">
    <div class="row">
      <div class="col-xs-12 col-sm-6">
        <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
            @csrf

              <div class="form-group">
                <div class="upload-btn-wrapper btn-group">
                  <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                  <input type="file" name="image" />
                  <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
                </div>
              </div>

                <div class="form-group flex-fill">
                  <textarea  class="form-control" name="body" placeholder="Received from Customer"></textarea>

                  <input type="hidden" name="moduletype" value="purchase" />
                  <input type="hidden" name="moduleid" value="{{$order['id']}}" />
                  <input type="hidden" name="assigned_user" value="{{$order['purchase_handler']}}" />
                  <input type="hidden" name="status" value="0" />
                </div>


         </form>
       </div>

       <div class="col-xs-12 col-sm-6">
         <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
            @csrf

              <div class="form-group">
                <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                  <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                  <input type="file" name="image" />

                  <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
                </div>
              </div>

                <div class="form-group flex-fill">
                  <textarea id="message-body" class="form-control mb-3" name="body" placeholder="Send for approval"></textarea>

                  <input type="hidden" name="moduletype" value="purchase" />
                  <input type="hidden" name="moduleid" value="{{$order['id']}}" />
                  <input type="hidden" name="assigned_user" value="{{$order['purchase_handler']}}" />
                  <input type="hidden" name="status" value="1" />

                  <p class="pb-4" style="display: block;">
                      <select name="quickCategory" id="quickCategory" class="form-control mb-3">
                        <option value="">Select Category</option>
                        @foreach($reply_categories as $category)
                            <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                        @endforeach
                      </select>

                      <select name="quickComment" id="quickComment" class="form-control">
                        <option value="">Quick Reply</option>

                      </select>
                  </p>

                  <button type="button" class="btn btn-xs btn-secondary mb-3" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
                </div>

         </form>
       </div>

       <div id="ReplyModal" class="modal fade" role="dialog">
         <div class="modal-dialog">

           <!-- Modal content-->
           <div class="modal-content">
             <div class="modal-header">
               <h4 class="modal-title"></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>

             <form action="{{ route('reply.store') }}" method="POST" enctype="multipart/form-data" id="approvalReplyForm">
               @csrf

               <div class="modal-body">
                 <div class="form-group">
                     <strong>Select Category:</strong>
                     <select class="form-control" name="category_id" id="category_id_field">
                       @foreach ($reply_categories as $category)
                         <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                       @endforeach
                     </select>
                     @if ($errors->has('category_id'))
                         <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
                     @endif
                 </div>

                 <div class="form-group">
                     <strong>Quick Reply:</strong>
                     <textarea class="form-control" id="reply_field" name="reply" placeholder="Quick Reply" required>{{ old('reply') }}</textarea>
                     @if ($errors->has('reply'))
                         <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                     @endif
                 </div>

                 <input type="hidden" name="model" id="model_field" value="">

               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-secondary">Create</button>
               </div>
             </form>
           </div>

         </div>
       </div><div id="ReplyModal" class="modal fade" role="dialog">
         <div class="modal-dialog">

           <!-- Modal content-->
           <div class="modal-content">
             <div class="modal-header">
               <h4 class="modal-title"></h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>

             <form action="{{ route('reply.store') }}" method="POST" enctype="multipart/form-data" id="approvalReplyForm">
               @csrf

               <div class="modal-body">

                 <div class="form-group">
                     <strong>Quick Reply:</strong>
                     <textarea class="form-control" id="reply_field" name="reply" placeholder="Quick Reply" required>{{ old('reply') }}</textarea>
                     @if ($errors->has('reply'))
                         <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                     @endif
                 </div>

                 <input type="hidden" name="model" id="model_field" value="">

               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-secondary">Create</button>
               </div>
             </form>
           </div>

         </div>
       </div>

       <div class="col-xs-12 col-sm-6">
           <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
              @csrf

                <div class="form-group">
                  <div class="upload-btn-wrapper btn-group">
                     <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                      <input type="file" name="image" />
                      <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
                    </div>
                </div>

                  <div class="form-group flex-fill">
                    <textarea class="form-control mb-3" name="body" placeholder="Internal Communications" id="internal-message-body"></textarea>

                    <input type="hidden" name="moduletype" value="purchase" />
                    <input type="hidden" name="moduleid" value="{{$order['id']}}" />
                    <input type="hidden" name="status" value="4" />

                    <strong>Assign to</strong>
                    <select name="assigned_user" class="form-control mb-3" required>
                      <option value="">Select User</option>
                      @foreach($users as $user)
                        <option value="{{$user['id']}}">{{$user['name']}}</option>
                      @endforeach
                    </select>

                    <p class="pb-4" style="display: block;">
                        <select name="quickCategoryInternal" id="quickCategoryInternal" class="form-control mb-3">
                          <option value="">Select Category</option>
                          @foreach($reply_categories as $category)
                              <option value="{{ $category->internal_leads }}">{{ $category->name }}</option>
                          @endforeach
                        </select>

                        <select name="quickCommentInternal" id="quickCommentInternal" class="form-control">
                            <option value="">Quick Reply</option>
                        </select>
                    </p>

                    <button type="button" class="btn btn-xs btn-secondary mb-3" data-toggle="modal" data-target="#ReplyModal" id="internal_reply">Create Quick Reply</button>
                  </div>


           </form>
         </div>

         <div class="col-xs-12 col-sm-6">
           <div class="d-flex">
             <div class="form-group">
               <button id="waMessageSend" class="btn btn-sm btn-image"><img src="/images/filled-sent.png" /></button>
             </div>

             <div class="form-group flex-fill">
               <textarea id="waNewMessage" class="form-control" placeholder="Whatsapp message"></textarea>
             </div>
           </div>

           <label>Attach Media</label>
           <input id="waMessageMedia" type="file" name="media" />
         </div>
    </div>
  </div> --}}

</div>



@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

  <script type="text/javascript">
    $('#completion-datetime, #transaction-datetime, #proforma-datetime, #shipment-datetime').datetimepicker({
      format: 'YYYY-MM-DD HH:mm'
    });

    $(document).ready(function() {
      $("body").tooltip({ selector: '[data-toggle=tooltip]' });
      $('.dropify').dropify();
    });

    $(document).on('click', '.edit-message', function(e) {
      e.preventDefault();
      var thiss = $(this);
      var message_id = $(this).data('messageid');

      $('#message_body_' + message_id).css({'display': 'none'});
      $('#edit-message-textarea' + message_id).css({'display': 'block'});

      $('#edit-message-textarea' + message_id).keypress(function(e) {
        var key = e.which;

        if (key == 13) {
          e.preventDefault();
          var token = "{{ csrf_token() }}";
          var url = "{{ url('message') }}/" + message_id;
          var message = $('#edit-message-textarea' + message_id).val();

          if ($(thiss).hasClass('whatsapp-message')) {
            var type = 'whatsapp';
          } else {
            var type = 'message';
          }

          $.ajax({
            type: 'POST',
            url: url,
            data: {
              _token: token,
              body: message,
              type: type
            },
            success: function(data) {
              $('#edit-message-textarea' + message_id).css({'display': 'none'});
              $('#message_body_' + message_id).text(message);
              $('#message_body_' + message_id).css({'display': 'block'});
            }
          });
        }
      });
    });

    $(document).on('change', '.is_statutory', function () {
        if ($(".is_statutory").val() == 1) {
            $("#completion_form_group").hide();
            $('#recurring-task').show();
        }
        else {
            $("#completion_form_group").show();
            $('#recurring-task').hide();
        }

    });

   $('#addTaskButton').on('click', function () {
     var client_name = "";

     $('#task_subject').val(client_name);
   });

   $(document).on('click', '.change_message_status', function(e) {
     e.preventDefault();
     var url = $(this).data('url');
     var token = "{{ csrf_token() }}";
     var thiss = $(this);

     if ($(this).hasClass('wa_send_message')) {
       var message_id = $(this).data('messageid');
       var message = $('#message_body_' + message_id).find('p').data('message').trim();

       $.ajax({
         url: "{{ url('whatsapp/updateAndCreate') }}",
         type: 'POST',
         data: {
           _token: token,
           moduletype: "purchase",
           message_id: message_id
         },
         beforeSend: function() {
           $(thiss).text('Loading');
         }
       }).done( function(response) {
         // $(thiss).remove();
         // console.log(response);
       }).fail(function(errObj) {
         console.log(errObj);
         alert("Could not create whatsapp message");
       });
       // $('#waNewMessage').val(message);
       // $('#waMessageSend').click();
     }
       $.ajax({
         url: url,
         type: 'GET'
         // beforeSend: function() {
         //   $(thiss).text('Loading');
         // }
       }).done( function(response) {
         $(thiss).remove();
       }).fail(function(errObj) {
         alert("Could not change status");
       });



   });

   $(document).on('click', '.task-subject', function() {
     if ($(this).data('switch') == 0) {
       $(this).text($(this).data('details'));
       $(this).data('switch', 1);
     } else {
       $(this).text($(this).data('subject'));
       $(this).data('switch', 0);
     }
   });

   function addNewRemark(id){

     var formData = $("#add-new-remark").find('#add-remark').serialize();
     var remark = $('#remark-text_'+id).val();
     $.ajax({
         type: 'POST',
         headers: {
             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         url: '{{ route('task.addRemark') }}',
         data: {id:id,remark:remark},
     }).done(response => {
         alert('Remark Added Success!')
         window.location.reload();
     });
   }

   $(".view-remark").click(function () {

     var taskId = $(this).attr('data-id');

       $.ajax({
           type: 'GET',
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           },
           url: '{{ route('task.gettaskremark') }}',
           data: {id:taskId},
       }).done(response => {
           // console.log(response);

           var html='';

           $.each(response, function( index, value ) {

             html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
             html+"<hr>";
           });
           $("#view-remark-list").find('#remark-list').html(html);
           // getActivity();
           //
           // $('#loading_activty').hide();
       });
   });

   $(document).ready(function() {
     $("body").tooltip({ selector: '[data-toggle=tooltip]' });
   });

   $('#submitButton').on('click', function(e) {
     e.preventDefault();

     var phone = $('input[name="contactno"]').val();

     if (phone.length != 0) {
       if (/^[91]{2}/.test(phone) != true) {
         $('input[name="contactno"]').val('91' + phone);
       }
     }

     $(this).closest('form').submit();
   });

    $('#change_status').on('change', function() {
      var token = "{{ csrf_token() }}";
      var status = $(this).val();
      var id = {{ $order->id }};

      $.ajax({
        url: '/purchase/' + id + '/changestatus',
        type: 'POST',
        data: {
          _token: token,
          status: status
        }
      }).done( function(response) {
        $('#change_status_message').fadeIn(400);
        setTimeout(function () {
          $('#change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(errObj) {
        alert("Could not change status");
      });
    });

    $('.change-product-status').on('change', function() {
      var token = "{{ csrf_token() }}";
      var status = $(this).val();
      var id = {{ $order->id }};
      var product_id = $(this).data('id');
      var thiss = $(this);

      $.ajax({
        url: '/purchase/' + id + '/changeProductStatus',
        type: 'POST',
        data: {
          _token: token,
          status: status,
          product_id: product_id,
        }
      }).done( function(response) {
        $(thiss).siblings('.change_status_message').fadeIn(400);
        setTimeout(function () {
          $(thiss).siblings('.change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(errObj) {
        alert("Could not change status");
      });
    });

    $('#confirmProformaButton').on('click', function() {
      var token = "{{ csrf_token() }}";
      var id = {{ $order->id }};
      var thiss = $(this);
      var proformas = $('input[name="proforma"]');
      var proformas_array = [];
      var proforma_id = $('input[name="proforma_id"]').val();
      var proforma_date = $('input[name="proforma_date"]').val();

      for (var i = 0; i < proformas.length; i++) {
        product_id = $(proformas[i]).data('productid');
        value = $(proformas[i]).val();
        proformas_array[i] = [];
        proformas_array[i].push(product_id);
        proformas_array[i].push(value);
      }

      $.ajax({
        url: '/purchase/' + id + '/confirmProforma',
        type: 'POST',
        data: {
          _token: token,
          proformas: proformas_array,
          proforma_id: proforma_id,
          proforma_date: proforma_date,
        },
        beforeSend: function() {
          $(thiss).text('Confirming...');
        }
      }).done( function(response) {
        if (response.proforma_confirmed == 1) {
          $(thiss).parent('div').append('<span class="badge">Proforma Confirmed</span>');
          $(thiss).remove();

          $(thiss).siblings('.change_status_message').fadeIn(400);
          setTimeout(function () {
            $(thiss).siblings('.change_status_message').fadeOut(400);
          }, 2000);
        } else {
          $(thiss).text('Confirm Proforma');
          $(thiss).addClass('btn-danger');
        }
      }).fail(function(response) {
        $(thiss).text('Confirm Proforma');
        console.log(response);
        alert("Could not confirm proforma!");
      });
    });

    $(document).on('click', '.save-bill', function(e) {
      e.preventDefault();

      var data = new FormData();
      var thiss = $(this);
      var id = {{ $order->id }};
      var token = "{{ csrf_token() }}";
      var supplier = $('select[name="supplier"]').val();
      var agent_id = $('select[name="agent_id"]').val();
      var transaction_id = $('input[name="transaction_id"]').val();
      var transaction_date = $('input[name="transaction_date"]').val();
      var transaction_amount = $('input[name="transaction_amount"]').val();
      var bill_number = $('input[name="bill_number"]').val();
      var shipper = $('input[name="shipper"]').val();
      var shipment_cost = $('input[name="shipment_cost"]').val();
      var shipment_date = $('input[name="shipment_date"]').val();
      var shipment_status = $('input[name="shipment_status"]').val();
      var supplier_phone = $('input[name="supplier_phone"]').val();
      var whatsapp_number = $('select[name="whatsapp_number"]').val();
      var files = $("#uploaded_files").prop("files");


      if (files && files.length > 0) {
        for (var i = 0; i != files.length; i++) {
          data.append("files[]", files[i]);
        }
      }

      data.append("_token", token);
      data.append("bill_number", bill_number);
      data.append("supplier", supplier);
      data.append("agent_id", agent_id);
      data.append("transaction_id", transaction_id);
      data.append("transaction_date", transaction_date);
      data.append("transaction_amount", transaction_amount);
      data.append("shipper", shipper);
      data.append("shipment_cost", shipment_cost);
      data.append("shipment_status", shipment_status);
      data.append("shipment_date", shipment_date);
      data.append("supplier_phone", supplier_phone);
      data.append("whatsapp_number", whatsapp_number);

      console.log(files);
      // console.log(files_array);

      $.ajax({
        url: '/purchase/' + id + '/saveBill',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        beforeSend: function() {
          $(thiss).text('Saving');
        }
      }).done( function(response) {
        console.log(response);
        $(thiss).text('Save');

        $('#save_status').fadeIn(400);
        setTimeout(function () {
          $('#save_status').fadeOut(400);
        }, 2000);
      }).fail(function(errObj) {
        $(thiss).text('Save');
        alert("Could not save Bill number");
      });
    });

    $('.replace-product-button').on('click', function (e) {
      e.preventDefault();

      $('#replace_product_id').val($(this).data('id'));
    });

    $(document).on('click', '.email-fetch', function(e) {
      e.preventDefault();

      var uid = $(this).data('uid');
      var type = $(this).data('type');
      var email_type = 'server';

      if (uid == 'no') {
        uid = $(this).data('id');
        email_type = 'local';
      }

      $('#email-content').find('.resend-email-button').attr('data-id', uid);
      $('#email-content').find('.resend-email-button').attr('data-emailtype', email_type);
      $('#email-content').find('.resend-email-button').attr('data-type', type);

      $.ajax({
        type: "GET",
        url: "{{ route('purchase.email.fetch') }}",
        data: {
          uid: uid,
          type: type,
          email_type: email_type
        },
        beforeSend: function() {
          $('#email-content .card').html('Loading...');
        }
      }).done(function(response) {
        $('#email-content .card').html(response.email);
      }).fail(function(response) {
        $('#email-content .card').html();

        alert('Could not fetch an email');
        console.log(response);
      })
    });

    $('a[href="#emails_tab"], #email-inbox-tab, #email-sent-tab').on('click', function() {
      var purchase_id = $(this).data('purchaseid');
      var type = $(this).data('type');

      $.ajax({
        url: "{{ route('purchase.email.inbox') }}",
        type: "GET",
        data: {
          purchase_id: purchase_id,
          type: type
        },
        beforeSend: function() {
          $('#emails_tab #email-container .card').html('Loading emails');
        }
      }).done(function(response) {
        console.log(response);
        $('#emails_tab #email-container').html(response.emails);
      }).fail(function(response) {
        $('#emails_tab #email-container .card').html();

        alert('Could not fetch emails');
        console.log(response);
      });
    });

    $(document).on('click', '.pagination a', function(e) {
      e.preventDefault();

      var url = "/purchase/email/inbox" + $(this).attr('href');

      $.ajax({
        url: url,
        type: "GET"
      }).done(function(response) {
        $('#emails_tab #email-container').html(response.emails);
      }).fail(function(response) {
        alert('Could not load emails');
        console.log(response);
      });
    });

    $(document).on('click', '.change-history-toggle', function() {
      $(this).siblings('.change-history-container').toggleClass('hidden');
    });

    $(document).on('click', '.resend-email-button', function() {
      var id = $(this).data('id');
      var email_type = $(this).data('emailtype');
      var type = $(this).data('type');

      $('#resend_email_id').val(id);
      $('#resend_email_type').val(email_type);
      $('#resend_type').val(type);
    });

    $('input[name="percentage"], input[name="factor"]').on('keyup', function() {
      var thiss = $(this);
      var price = $(this).data('price');

      if ($(thiss).parent('div').parent('div').find('input[name="percentage"]').val() < 0) {
        $(thiss).parent('div').parent('div').find('input[name="percentage"]').val(0);
      } else if ($(thiss).parent('div').parent('div').find('input[name="percentage"]').val() > 100) {
        $(thiss).parent('div').parent('div').find('input[name="percentage"]').val(100);
      }

      var percentage = $(thiss).parent('div').parent('div').find('input[name="percentage"]').val();
      // var factor = $(thiss).parent('div').parent('div').find('input[name="factor"]').val();

      $(thiss).parent('div').parent('div').find('.purchase-price').text(Math.round((price - (price * percentage / 100)) / 1.22));

      var temp_price;
      var temp_percentage;
      var purchase_price = 0;
      var percentages = $('input[name="percentage"]');

      for (var i = 0; i < percentages.length; i++) {
        temp_price = $(percentages[i]).data('price');
        temp_percentage = $(percentages[i]).val();

        purchase_price += Math.round((temp_price - (temp_price * temp_percentage / 100)) / 1.22);
      }

      $('#purchase_price').text(purchase_price);
    });

    $('.save-purchase-price').on('click', function(e) {
      e.preventDefault();

      // var id = $(this).data('id');
      var id = {{ $order->id }};
      var thiss = $(this);
      var url = "{{ url('purchase/product') }}/" + id;
      var token = "{{ csrf_token() }}";
      var percentages = $('input[name="percentage"]');
      var percentages_array = [];
      var value;

      for (var i = 0; i < percentages.length; i++) {
        product_id = $(percentages[i]).data('productid');
        value = $(percentages[i]).val();
        percentages_array[i] = [];
        percentages_array[i].push(product_id);
        percentages_array[i].push(value);
      }

      console.log(percentages);
      console.log(percentages_array);
      // var factor = $(this).parent('div').parent('div').find('input[name="factor"]').val();

      $.ajax({
        type: 'POST',
        url: url,
        data: {
          _token: token,
          percentages: percentages_array,
          purchase_id: {{ $order->id }},
          type: "product"
          // factor: factor
        },
        beforeSend: function() {
          $(thiss).text('Saving');
        },
        success: function() {
          $(thiss).text('Save');
          var row = '<tr><td>' + moment().format('Y-MM-DD HH:mm:ss') + '</td>';

          for (var i = 0; i < percentages.length; i++) {
            product_id = $(percentages[i]).data('productid');
            value = $(percentages[i]).val();

            row += '<td>' + product_id + ' - ' + value + ' %</td>';
          }

          row += '</tr>';

          $('#purchaseDiscounts').find('tbody').prepend(row);
        }
      });
    });

    $('#sendRemarkButton').on('click', function() {
      var id = {{ $order->id }};
      var remark = $(this).parent('div').siblings('.form-group').find('textarea').val();
      var thiss = $(this);

      $.ajax({
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('task.addRemark') }}',
          data: {
            id: id,
            remark: remark,
            module_type: 'purchase-product-remark'
          },
      }).done(response => {
          $(thiss).parent('div').siblings('.form-group').find('textarea').val('');
          var comment = '<li> '+ remark +' <br> <small>By updated on '+ moment().format('DD-M H:mm') +' </small></li>';

          $('#remarks-container').find('ul').prepend(comment);
      }).fail(function(response) {
        console.log(response);
        alert('Could not add remark');
      });
    });

    var id = {{ $order->id }};

    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: '{{ route('task.gettaskremark') }}',
        data: {
          id: id,
          module_type: "purchase-product-remark"
        },
    }).done(response => {
        var html='';

        $.each(response, function( index, value ) {
          html+=' <li> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></li>';
        });
        $("#remarks-container").find('ul').html(html);
    });

    $('#hideRemarksButton').on('click', function() {
      $('#remarks-container').toggleClass('hidden');
    });

    var purchases_array = [];
    var agents_array = {!! json_encode($agents_array) !!};

    $('#purchaseExportButton').on('click', function(e) {
      e.preventDefault();

      var purchase_id = {{ $order->id }};
      purchases_array.push(purchase_id);

      if (purchases_array.length > 0) {
        $('#selected_purchases').val(JSON.stringify(purchases_array));

        if ($('#purchaseExportForm')[0].checkValidity()) {
          $('#purchaseExportForm').submit();
          $('#sendExportModal').find('.close').click();
        } else {
          $('#purchaseExportForm')[0].reportValidity();
        }

      } else {
        alert('Please select atleast 1 purchase');
      }
    });

    $(document).on('change', '#export_supplier', function() {
      var supplier_id = $(this).val();

      agents = agents_array[supplier_id];

      $('#export_agent').empty();

      $('#export_agent').append($('<option>', {
        value: '',
        text: 'Select Agent'
      }));

      Object.keys(agents).forEach(function(agent) {
        $('#export_agent').append($('<option>', {
          value: agent,
          text: agents_array[supplier_id][agent]
        }));
      });
    });

    var selected_order_products = [];
    $('.select-order-product').on('click', function() {
      var checked = $(this).prop('checked');
      var order_product_id = $(this).val();

      if (checked) {
        selected_order_products.push(order_product_id);
      } else {
        var index = selected_order_products.indexOf(order_product_id);

        selected_order_products.splice(index, 1);
      }

      $('#selected_order_products').val(JSON.stringify(selected_order_products));

      console.log(selected_order_products);
    });
  </script>
@endsection
