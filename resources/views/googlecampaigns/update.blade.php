@extends('layouts.app')
@section('favicon' , 'task.png')

@section('content')
    <div class="container">
        <h2>Update Campaign</h2>
    </div>
    <form method="POST" action="/google-campaigns/update" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="campaignId" value="{{$campaign['google_campaign_id']}}">
        <div class="form-group row">
            <label for="campaign-name" class="col-sm-2 col-form-label">Campaign name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="campaign-name" name="campaignName" placeholder="Campaign name" value="{{$campaign['campaign_name']}}">
                @if ($errors->has('campaignName'))
                <span class="text-danger">{{$errors->first('campaignName')}}</span>
                @endif
            </div>
        </div>


        <hr><h3>Bidding</h3> <!-- biddingstrategy -->
        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">what do you want to focus on?</label>
            <div class="col-sm-3">
                <select class="browser-default custom-select" id="bidding_focus_on" name="bidding_focus_on" style="height: auto">
                    <option value="conversions" selected>Conversions</option>
                    <option value="conversions_value">Conversions value</option>
                    <option value="viewable_impressions">Viewable impressions</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">Bidding Strategy</label>
            <div class="col-sm-3" id="biddingStrategyType_second_div">
                <select class="browser-default custom-select" id="biddingStrategyType" name="biddingStrategyType" style="height: auto">
                    @foreach($biddingStrategyTypes as $bskey=>$bs)
                    <option value="{{$bskey}}">{{$bs}}</option>
                    @endforeach
                </select>

            <div id="maindiv_for_target" style="display:none;"><input type="checkbox" name="target_cost_per_action" id="target_cost_per_action" value="1"> Set a target cost per action
            <div id="div_html_append_1" style="display:none;">
            <label>Target CPA</lable> 
            <input type="text" name="txt_target_cpa" id="txt_target_cpa" value=""> 
            <!-- <label>Pay For</lable> 
            <select name="pay_for" id="pay_for">
            <option value="clicks">Clicks</option>
            <option value="viewable_impressions">Viewable Impressions</option>
            </select> -->
            </div></div>
            <div id="div_roas" style="display:none; margin-top:20px;">
            <label>Target ROAS (This field must be between 0.01 and 1000.0, inclusive)</lable> 
                <input type="text" name="txt_target_roas" id="txt_target_roas" value="0.01"> %
            </div>
            <div id="div_targetspend" style="display:none; margin-top:20px;">
            <label>Maximize clicks (This field must be greater than or equal to 0)</lable> 
                <input type="text" name="txt_maximize_clicks" id="txt_maximize_clicks" value="0">
            </div>
                <br><br>
                <a href="javascript:void(0);" class="btn btn-link" id="directiBiddingSelect">Or, select a bid strategy directly (not recommended)</a>

                <a href="javascript:void(0);" class="btn btn-link" id="resetBiddingSection">Reset</a>
            </div>
        </div>
        <hr>


        <div class="form-group row">
            <label for="budget-amount" class="col-sm-2 col-form-label">Budget amount ($)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="budget-amount" name="budgetAmount" placeholder="Budget amount ($)" value="{{$campaign['budget_amount']}}">
                @if ($errors->has('budgetAmount'))
                <span class="text-danger">{{$errors->first('budgetAmount')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="start-date" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="start-date" name="start_date" placeholder="Start Date E.g {{date('Ymd', strtotime('+1 day'))}}" value="{{$campaign['start_date']}}">
                @if ($errors->has('start_date'))
                <span class="text-danger">{{$errors->first('start_date')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="start-date" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="end-date" name="end_date" placeholder="End Date E.g {{date('Ymd', strtotime('+1 month'))}}" value="{{$campaign['end_date']}}">
                @if ($errors->has('end_date'))
                <span class="text-danger">{{$errors->first('end_date')}}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">Campaign status</label>
            <div class="col-sm-10">
                <select class="browser-default custom-select" id="campaign-status" name="campaignStatus" style="height: auto">
                    <option value="1" {{($campaign['status'] == 'ENABLED') ? 'selected' : ''}}>Enabled</option>
                    <option value="2" {{($campaign['status'] == 'PAUSED') ? 'selected' : ''}}>Paused</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-2 float-right">Update</button>
    </form>
{{--    {!! Form::open(['method' => 'GET','route' => ['adgroup.createPage'],'style'=>'display:inline']) !!}--}}
{{--        <button type="submit" class="btn btn-image float-right">Create Ad Group</button>--}}
{{--    {!! Form::close() !!}--}}



<script>
    var bidding_focus_on=$("#bidding_focus_on");
    var channel_type=$("#channel_type");
    var channel_sub_type=$("#channel_sub_type");
    $(document).ready(function(){
        biddingFocusBaseStrategy();
        

    function biddingFocusBaseStrategy(){
        /* var biddingStrategyArray= '<?php //echo json_encode(array_keys($biddingStrategyTypes)); ?>';
        biddingStrategyArray=JSON.parse(biddingStrategyArray); */
        var biddingStrategyArray=[];
        //start re-arranging everything
        var bidding_focus_on_val=bidding_focus_on.val();
        //$("#biddingStrategyType").children('option').hide();

        $("#biddingStrategyType").removeAttr('selected');
        $("#biddingStrategyType option").hide();

        //end re-arranging everything

        if(bidding_focus_on_val=="conversions"){
                biddingStrategyArray=['MANUAL_CPC','MAXIMIZE_CONVERSION_VALUE'];
        }
        if(biddingStrategyArray.length>0){
            $(biddingStrategyArray).each(function(i,v){
                $("#biddingStrategyType option[value=" + v + "]").show();
            });
        }

    }

    
    $("#biddingStrategyType").on('change',function(){
        var biddingStrategyTypeVal=$(this).val();
        $("#maindiv_for_target").hide();
        $("#div_html_append_1").hide();
        $("#target_cost_per_action").prop('checked',false);
        $("#div_roas").hide();
        $("#div_targetspend").hide();
        if(biddingStrategyTypeVal=="MAXIMIZE_CONVERSION_VALUE" || biddingStrategyTypeVal=="TARGET_CPA"){
            //append HTML into form
            /* var html='<div id="maindiv_for_target"><input type="checkbox" name="target_cost_per_action" id="target_cost_per_action" value="1"> Set a target cost per action\n\
            <div id="div_html_append_1" style="display:none;">\n\
            <label>Target CPA</lable> \n\
            <input type="text" name="txt_target_cpa" id="txt_target_cpa" value=""> \n\
            <label>Pay For</lable> \n\
            <select name="pay_for id="pay_for">\n\
            <option value="clicks">Clicks</option>\n\
            <option value="viewable_impressions">Viewable Impressions</option>\n\
            </select>\n\
            </div></div>';
            $("#biddingStrategyType_second_div").append(html); */
            $("#maindiv_for_target").css('display','block');
        }
        if(biddingStrategyTypeVal=="TARGET_ROAS"){
            $("#div_roas").show();
        }

        if(biddingStrategyTypeVal=="TARGET_SPEND"){
            $("#div_targetspend").show();
        }

    });
    
    //$(document).on("click", '#target_cost_per_action', function() {
    $("#target_cost_per_action").click(function(){
         if($("#target_cost_per_action").is( 
                      ":checked")){
                $("#div_html_append_1").show();
        }else{
                $("#div_html_append_1").hide();
        } 

    });

    $("#directiBiddingSelect").click(function(){
        $("#maindiv_for_target").hide();
        $("#div_html_append_1").hide();
        $("#target_cost_per_action").prop('checked',false);
        
        $("#div_roas").hide();
        $("#div_targetspend").hide();
        var bidding_focus_on_val=bidding_focus_on.val();
        if(bidding_focus_on_val=="conversions"){
                biddingStrategyArray=['TARGET_CPA','TARGET_ROAS','TARGET_SPEND','MAXIMIZE_CONVERSION','MANUAL_CPM','MANUAL_CPC'];
        }
        if(biddingStrategyArray.length>0){
            $(biddingStrategyArray).each(function(i,v){
                $("#biddingStrategyType option[value=" + v + "]").show();
            });
        }
    });
    
    $("#resetBiddingSection").click(function(){
        $("#biddingStrategyType").removeAttr('selected');
        $("#biddingStrategyType option").hide();
        
       
                biddingStrategyArray=['MANUAL_CPC','MAXIMIZE_CONVERSION_VALUE'];
       
        if(biddingStrategyArray.length>0){
            $(biddingStrategyArray).each(function(i,v){
                $("#biddingStrategyType option[value=" + v + "]").show();
            });
        }

    });
});
</script>
@endsection