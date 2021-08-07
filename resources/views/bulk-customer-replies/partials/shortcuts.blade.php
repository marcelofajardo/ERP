<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-6 d-inline form-inline">
                <input style="width: 87%" type="text" name="category_name" placeholder="Enter New Category" class="form-control mb-3 quick_category">
                <button class="btn btn-secondary quick_category_add" style="position: absolute;  margin-left: 8px;">+</button>
            </div>
            <div class="col-6 d-inline form-inline" style="padding-left: 0px;">
                <div style="float: left; width: 86%">
                    <select name="quickCategory" class="form-control mb-3 quickCategory select-child">
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
            <div class="col-6 d-inline form-inline" style="padding-left: 0px;">
                <div style="float: left; width: 86%">
                    <select name="quickComment" class="form-control quickComment">
                        <option value="">Quick Reply</option>
                    </select>
                </div>
                <div style="float: right; width: 14%;">
                    <a class="btn btn-image delete_quick_comment"><img src="/images/delete.png"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 expand-row dis-none">
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
            <button type="button" class="btn btn-image latest-scraped-shortcut" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#categoryBrandModal" style="padding: 6px 0px !important">Send Latest Scraped</button>
        </div>
    </div>    
</div>