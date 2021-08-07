@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="ml-4">Create Campaigns <h2>
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
                <form action="{{route('social.ad.campaign.store')}}" method="post" enctype="multipart/form-data" style="border:1px solid #dedede;padding:4%;border-radius: 5px">
                   @csrf


                   <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Type your campaign name">
                    @if ($errors->has('name'))
                    <p class="text-danger">{{$errors->first('name')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Objective</label>
                    <select class="form-control" name="objective" >
                        <option value="APP_INSTALLS">APP INSTALLS</option>
                        <option value="BRAND_AWARENESS">BRAND AWARENESS</option>
                        <option value="CONVERSIONS">CONVERSIONS</option>
                        <option value="EVENT_RESPONSES">EVENT RESPONSES</option>
                        <option value="LEAD_GENERATION">LEAD GENERATION</option>
                        <option value="LINK_CLICKS">LINK CLICKS</option>
                        <option value="LOCAL_AWARENESS">LOCAL AWARENESS</option>
                        <option value="MESSAGES">MESSAGES</option>
                        <option value="OFFER_CLAIMS">OFFER CLAIMS</option>
                        <option value="PAGE_LIKES">PAGE LIKES</option>
                        <option value="POST_ENGAGEMENT">POST ENGAGEMENT</option>
                        <option value="PRODUCT_CATALOG_SALES">PRODUCT CATALOG SALES</option> 
                        <option value="REACH">REACH</option> 
                        <option value="VIDEO_VIEWS">VIDEO VIEWS</option> 
                    </select>

                    @if ($errors->has('objective'))
                    <p class="text-danger">{{$errors->first('objective')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Buying Type</label>
                    <select class="form-control" name="buying_type" >
                        <option selected value="AUCTION">AUCTION</option>
                        <option value="RESERVED ">RESERVED </option>

                    </select>

                    @if ($errors->has('buying_type'))
                    <p class="text-danger">{{$errors->first('buying_type')}}</p>
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
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="ACTIVE">
                        <label class="form-check-label"  for="inlineRadio1">ACTIVE</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" checked type="radio" name="status" id="inlineRadio2" value="PAUSED">
                      <label class="form-check-label"  for="inlineRadio2">PAUSED</label>
                  </div>
              </div>

              <button type="submit" class="btn btn-info">Create Campaign</button>
          </form>
      </div>
      <div class="col-md-4"></div>
  </div>

</div>





@endsection