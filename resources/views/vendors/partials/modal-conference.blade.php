<div id="conferenceModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Conference Call to Multiple Suppliers</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('vendors.email.send.bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <strong>Vendors</strong>
                        <select class="form-control select-multiple selectpicker" name="vendors[]" multiple data-live-search="true" id="vendors-conference" required>
                            {{-- <option value="">Select Suppliers</option> --}}
                            @php
                            $vendors = \App\Vendor::where('is_blocked',1)->get();
                            @endphp

                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->phone }}">{{ $vendor->name }} - {{ $vendor->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" value="vendors" id="context">
                    <div class="form-group">
                        <strong>Number</strong>

                        <select class="form-control" data-context="vendors" data-id="{{ isset($vendor) ? $vendor->id : 0 }}" data-phone="+918082488108" id="conference-number-selected" required>
                            <option disabled selected>Select Number</option>
                                @foreach(\Config::get("twilio.caller_id") as $caller)
                                <option value="{{ $caller }}">{{ $caller }}</option>
                                @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-image conference-twilio"><img src="/images/call.png"></button>
                </div>
            </form>
        </div>

    </div>
</div>
