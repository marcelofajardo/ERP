@extends('layouts.app')
@section('favicon' , 'task.png')

@section('content')
    <div class="container">
        <h2>Create Ad group for {{$campaign_name}}</h2>
    </div>
    <form method="POST" action="/google-campaigns/{{$campaignId}}/adgroups/create" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group row">
            <label for="ad-group-name" class="col-sm-2 col-form-label">Ad group name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="ad-group-name" name="adGroupName" placeholder="Ad group name">
                @if ($errors->has('adGroupName'))
                <span class="text-danger">{{$errors->first('adGroupName')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="bid-amount" class="col-sm-2 col-form-label">Bid ($)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="bid-amount" name="microAmount" placeholder="Bid ($)">
                @if ($errors->has('microAmount'))
                <span class="text-danger">{{$errors->first('microAmount')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="ad-group-status" class="col-sm-2 col-form-label">Ad group status</label>
            <div class="col-sm-10">
                <select class="browser-default custom-select" id="ad-group-status" name="adGroupStatus" style="height: auto">
                    <option value="1" selected>Enabled</option>
                    <option value="2">Paused</option>
                </select>
            </div>
        </div>
{{--        <div class="form-group row">--}}
{{--            <label for="criterion-type-group" class="col-sm-2 col-form-label">Criterion type group</label>--}}
{{--            <div class="col-sm-10">--}}
{{--                <select class="browser-default custom-select" id="criterion-type-group" name="criterionTypeGroup" style="height: auto">--}}
{{--                    <option value="0">Keyword</option>--}}
{{--                    <option value="1">User interest and list</option>--}}
{{--                    <option value="2">Vertical</option>--}}
{{--                    <option value="3">Gender</option>--}}
{{--                    <option value="4">Age range</option>--}}
{{--                    <option value="5">Placement</option>--}}
{{--                    <option value="6">Parent</option>--}}
{{--                    <option value="7">Income range</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="form-group row">--}}
{{--            <label for="ad-rotation-mode" class="col-sm-2 col-form-label">Ad rotation mode</label>--}}
{{--            <div class="col-sm-10">--}}
{{--                <select class="browser-default custom-select" id="ad-rotation-mode" name="adRotationMode" style="height: auto">--}}
{{--                    <option value="0">Unknown</option>--}}
{{--                    <option value="1">Optimize</option>--}}
{{--                    <option value="2">Rotate forever</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
        <button type="submit" class="mb-2 float-right">Create</button>
    </form>
@endsection