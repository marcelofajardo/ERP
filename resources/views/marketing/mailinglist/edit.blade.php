<form action="{!! route('mailingList.update',$list->id) !!}" class="mailing_form">
    <div class="form-group">
        <select name="service_id" id="service_id" class="form-control">
            <option value="">Select Service</option>
            @foreach($services as $service)
                <option value="{{$service->id}}" {{ $service->id == $list->service_id ? 'selected' : '' }}>{{$service->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <select name="website_id" id="website_id" class="form-control">
            <option value="">Select Website</option>
            @foreach($websites as $website)
                <option value="{{$website->id}}" {{ $website->id == $list->website_id ? 'selected' : '' }}>{{$website->title}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <input type="email" name="email" id="email" class="form-control email" placeholder="Email" value="{!! $list->email !!}">
    </div>

    <div class="form-group">
        <input type="text" name="name" class="form-control name" placeholder="Name" value="{!! $list->name !!}">
    </div>
</form>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary save_list">Save changes</button>
</div>