@extends('layouts.app')
@section('content')
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Manage Calls</h2>
        </div>
    </div>
    @include('partials.flash_messages')
    <div class="row mb-3">
        <div class="col-md-10 col-sm-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mr-3">
                        <form method="get" id="change_twilio_account">
                            <select class="form-control" name="id" id="twilio_account_id">
                                <option value="">Select Twilio Account</option>
                                @if(isset($twilio_accounts))
                                    @foreach($twilio_accounts as $account)
                                        <option value="{{ $account->id }}" @if(request()->get('id') && (request()->get('id') == $account->id)) selected @endif>{{ $account->twilio_email }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-gutters mb-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Account ID</th>
                    <th scope="col" class="text-center">Phone Number</th>
                    <th scope="col" class="text-center">Site ID</th>
                    <th scope="col" class="text-center">Live Agent Timing</th>
                    <th scope="col" class="text-center">Live Agent Days of the week</th>
                    <th scope="col" class="text-center">Agents Assigned</th>
                    <th scope="col" class="text-center">Greeting Message during operation hours</th>
                    <th scope="col" class="text-center">Greeting Message during non operation hours</th>
                    <th scope="col" class="text-center">Greeting Message if busy</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody class="text-center">
                    @if(isset($twilio_account_details))
                        @foreach($twilio_account_details->numbers as $number)
                            <tr>
                                <td>{{ @$number->account_sid }}</td>
                                <td>{{ @$number->phone_number }}</td>
                                <td>{{ @$number->assigned_stores->store_website->title }}</td>
                                <td>{{ @$number->forwarded->forwarded_number_details->user_availabilities->from }} - {{ @$number->forwarded->forwarded_number_details->user_availabilities->to }}</td>
                                <td>{{ date('d-m-Y', strtotime( @$number->forwarded->forwarded_number_details->user_availabilities->date )) }}</td>
                                <td>{{ @$number->forwarded->forwarded_number_details->name }}</td>
                                <td>{{ @$number->assigned_stores->message_available }}</td>
                                <td>{{ @$number->assigned_stores->message_not_available }}</td>
                                <td>{{ @$number->assigned_stores->message_busy }}</td>
                                <td>
                                    <a href="{{ route('twilio-incoming-calls', [$number->sid, $number->phone_number]).'?id='.request()->get('id') }}" >Incoming Calls</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            console.log("in");
            $(document).on("change",'#twilio_account_id', function(){
                console.log('yes');
                $('#change_twilio_account').submit();
            });
        });
    </script>
@endsection