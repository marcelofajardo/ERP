@extends('layouts.app')

@section('favicon' , 'coldleadsimported.png')
@section('title', 'Cold Leads Imported')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Imported Cold Leads ({{$leads->total()}})</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-5">
                <form method="get" action="{{ action('ColdLeadsController@showImportedColdLeads') }}">
                    <div class="row">

                    <div class="col-md-3">
                        <input value="{{ $query }}" type="text" class="form-control" name="address" id="address" placeholder="Address/Phone/Name...">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary">Ok</button>
                    </div>
                    </div>
                </form>
        </div>
        <div class="col-md-12 text-center">
            {!! $leads->links() !!}
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                @foreach($leads as $key => $lead)
                    <tr id="cl_{{$lead->id}}" class="{{ $lead->whatsapp ? 'bg-info' : '' }}">
                        <th>{{ $key+1 }}</th>
                        <th>{{ $lead->name }}</th>
                        <th>{{ $lead->platform_id }}</th>
                        <th>{{ $lead->address }}</th>
                        <th>
                            @if($lead->customer || $lead->whatsapp)
                                <span class="label label-default">Already Added</span>
                            @else
                                <button data-leadId="{{$lead->id}}" class="btn btn-sm btn-secondary add-lead">Add To Customer</button>
                            @endif
                            <button class="btn btn-default btn-image delete-cold-lead" data-id="{{$lead->id}}">
                                <img src="{{ asset('images/delete.png') }}" alt="Delete" class="">
                            </button>
                        </th>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-12 text-center">
            {!! $leads->links() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.delete-cold-lead').click(function() {
            let leadId = $(this).attr('data-id');

            $.ajax({
                url: '{{ action('ColdLeadsController@deleteColdLead') }}',
                data: {
                    lead_id: leadId
                },
                success: function(response) {
                    toastr['success']('Deleted successfully!', 'Deleted');
                    $('#cl_'+leadId).hide();
                },
                error: function() {
                    toastr['error']('Could not delete lead.', 'Error');
                }
            });

        });
        $('.add-lead').click(function() {
            let leadId = $(this).attr('data-leadId');
            if (leadId == '') {
                return;
            }

            let self = this;

            $.ajax({
                url: "{{action('ColdLeadsController@addLeadToCustomer')}}",
                data: {
                    cold_lead_id: leadId
                },
                method: 'get',
                success: function() {
                    toastr['success']("Added to customers", 'Done!');
                    $(self).hide();
                    $(self).html('Added');
                },
                beforeSend: function() {
                    $(self).attr('disabled');
                },
                error: function() {
                    $(self).removeAttr('disabled');
                }
            });
        });
    </script>
@endsection

