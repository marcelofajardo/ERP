@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="ml-4">Create Adsets <h2>
            </div>
        </div>
    </div>


    @if ($message = Session::get('message'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="container-fluid"">
        <div class="row">
            <div class="col-md-8">
                <form action="{{route('social.ad.adset.store')}}" method="post" enctype="multipart/form-data" style="border:1px solid #dedede;padding:4%;border-radius: 5px">
                 @csrf

                 <div class="form-group">
                    <label for="">Choose Existing Campaign</label>
                    <select class="form-control" name="campaign_id" >
                        <?php foreach($campaigns as $campaign): ?>
                            <option value="<?=$campaign->id ?>"><?=$campaign->name; ?></option>
                        <?php endforeach; ?>
                    </select>

                    @if ($errors->has('campaign_id'))
                    <p class="text-danger">{{$errors->first('campaign_id')}}</p>
                    @endif
                </div>


                <div class="form-group">
                    <label for="">Adset Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Type your Adset name">
                    @if ($errors->has('name'))
                    <p class="text-danger">{{$errors->first('name')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Destination Type</label>
                    <select class="form-control" name="destination_type" >
                        <option value="WEBSITE">Website</option>
                        <option value="APP">App</option>
                        <option value="APPLINKS_AUTOMATIC">Applinks Automatic</option>
                        <option value="MESSENGER">Messenger</option>
                    </select>

                    @if ($errors->has('destination_type'))
                    <p class="text-danger">{{$errors->first('destination_type')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Billing Event</label>
                    <select class="form-control" name="billing_event" >
                        <option value="APP_INSTALLS">APP INSTALLS</option>
                        <option value="CLICKS">CLICKS</option>
                        <option value="IMPRESSIONS">IMPRESSIONS</option>
                        <option value="LINK_CLICKS">LINK_CLICKS</option>
                        <option value="NONE">NONE</option>
                        <option value="OFFER_CLAIMS">OFFER CLAIMS</option>
                        <option value="PAGE_LIKES">PAGE LIKES</option>
                        <option value="POST_ENGAGEMENT">POST ENGAGEMENT</option>
                        <option value="VIDEO_VIEWS">VIDEO VIEWS</option>
                        <option value="THRUPLAY">THRUPLAY</option>
                    </select>

                    @if ($errors->has('billing_event'))
                    <p class="text-danger">{{$errors->first('billing_event')}}</p>
                    @endif
                </div>
                
                <div class="form-group">
                    <label for="">Start Time</label>
                    <input type="date" class="form-control" name="start_time">

                    @if ($errors->has('start_time'))
                    <p class="text-danger">{{$errors->first('start_time')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">End Time</label>
                    <input type="date" class="form-control" name="end_time">

                    @if ($errors->has('end_time'))
                    <p class="text-danger">{{$errors->first('end_time')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Daily Budget</label>
                    <input type="number" class="form-control" name="daily_budget">
                    @if ($errors->has('daily_budget'))
                    <p class="text-danger">{{$errors->first('daily_budget')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Bid Amount</label>
                    <input type="number" class="form-control" name="bid_amount">
                    @if ($errors->has('bid_amount'))
                    <p class="text-danger">{{$errors->first('bid_amount')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="ACTIVE">
                        <label class="form-check-label"  for="inlineRadio1">ACTIVE</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" checked type="radio" name="status" id="inlineRadio2" value="PAUSED">
                      <label class="form-check-label"  for="inlineRadio2">PAUSED</label>
                  </div>
              </div>

              <button type="submit" class="btn btn-info">Create Adset</button>
          </form>
      </div>
      <div class="col-md-4"></div>
  </div>

</div>





@endsection