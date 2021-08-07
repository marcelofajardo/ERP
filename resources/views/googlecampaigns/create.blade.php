@extends('layouts.app')
@section('favicon' , 'task.png')

@section('content')
    <div class="container">
        <h2>Create Campaign</h2>
    </div>
    <form method="POST" action="/google-campaigns/create" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" value="<?php echo $_GET['account_id']; ?>" id="accountID" name="account_id"/>
        <div class="form-group row">
            <label for="campaign-name" class="col-sm-2 col-form-label">Campaign name</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="campaign-name" name="campaignName" placeholder="Campaign name">
                @if ($errors->has('campaignName'))
                <span class="text-danger">{{$errors->first('campaignName')}}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">Channel Type</label>
            <div class="col-sm-3">
                <select class="browser-default custom-select" id="channel_type" name="channel_type" style="height: auto">
                    <option value="SEARCH" selected>Search</option>
                    <option value="DISPLAY">Display</option>
                    <option value="SHOPPING">Shopping</option>
                    <option value="MULTI_CHANNEL">Multi Channel</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">ChannelSub Type</label>
            <div class="col-sm-3">
                <select class="browser-default custom-select" id="channel_sub_type" name="channel_sub_type" style="height: auto">
                    <option value="">---select subtype---</option>
                    <!-- <option value="UNKNOWN" selected>Unknown</option> -->
                    <option value="SEARCH_MOBILE_APP">Mobile App Campaigns for Search</option>
                    <option value="DISPLAY_MOBILE_APP">Mobile App Campaigns for Display</option>
                    <option value="SEARCH_EXPRESS">AdWords Express campaigns for search</option>
                    <option value="DISPLAY_EXPRESS">AdWords Express campaigns for display.</option>
                    <option value="UNIVERSAL_APP_CAMPAIGN">Google manages the keywords and ads for these campaigns</option>
                    <option value="DISPLAY_SMART_CAMPAIGN">Smart display campaign</option>
                    <option value="SHOPPING_GOAL_OPTIMIZED_ADS">Optimize automatically towards the retailer's business objectives</option>
                    <option value="DISPLAY_GMAIL_AD">Gmail Ad Campaigns</option>
                </select>
            </div>
        </div>
        <hr> <!-- settings -->
        <h3>Settings</h3>
        <div class="form-group row" id="div_shipping_setting" style="display:none;">
            <label for="campaign-status" class="col-sm-2 col-form-label">Merchant Id</label>
            <div class="col-sm-3">
                <input type="text" id="merchant_id" name="merchant_id" value="">
            </div>
            <label for="campaign-status" class="col-sm-2 col-form-label">Select Country</label>
            <div class="col-sm-3">
                <input type="text" id="sales_country" name="sales_country" value="">
                <br><span>E.g IN </span>
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

        <div class="form-group row" id="main_div_campaign_url_options">
            <label for="campaign-status" class="col-sm-2 col-form-label">Campaign URL options</label>
            <div class="col-sm-3">
            <label>Tracking template</label>
            <input type="text" name="tracking_template_url" id="tracking_template_url" />
            <br><span>Example: https://www.trackingtemplate.foo/?url={lpurl}&id=5</span>
            <label>Final URL suffix</label>
            <input type="text" name="final_url_suffix" id="final_url_suffix" /><br>
            <span>Example: param1=value1&amp;param2=value2</span>
            </div>
        </div>
        <hr>
        <!-- <div class="form-group row" id="main_div_ad_rotation">
            <label for="campaign-status" class="col-sm-2 col-form-label">Ad rotation</label>
            <div class="col-sm-3">
            <input type="radio" name="ad_rotation" id="ad_rotation1" value="OPTIMIZE"><label id="ad_rotation_label1">Optimize: Prefer best performing ads</label> <br>
            <input type="radio" name="ad_rotation" id="ad_rotation2" value="ROTATE_INDEFINITELY"><label id="ad_rotation_label2">Do not optimize: Rotate ads indefinitely</label><br>
            <input type="radio" name="ad_rotation" id="ad_rotation3" value="CONVERSION_OPTIMIZE"><label id="ad_rotation_label3">Optimize for conversions</label><br>
            <input type="radio" name="ad_rotation" id="ad_rotation3" value="ROTATE"><label id="ad_rotation_label4">Rotate evenly</label>
            </div>
        </div> -->

        
        
        <div class="form-group row">
            <label for="budget-amount" class="col-sm-2 col-form-label">Budget amount ($)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="budget-amount" name="budgetAmount" placeholder="Budget amount ($)">
                @if ($errors->has('budgetAmount'))
                <span class="text-danger">{{$errors->first('budgetAmount')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="start-date" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="start-date" name="start_date" placeholder="Start Date E.g {{date('Ymd', strtotime('+1 day'))}}">
                @if ($errors->has('start_date'))
                <span class="text-danger">{{$errors->first('start_date')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="start-date" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="end-date" name="end_date" placeholder="End Date E.g {{date('Ymd', strtotime('+1 month'))}}">
                @if ($errors->has('end_date'))
                <span class="text-danger">{{$errors->first('end_date')}}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="campaign-status" class="col-sm-2 col-form-label">Campaign status</label>
            <div class="col-sm-10">
                <select class="browser-default custom-select" id="campaign-status" name="campaignStatus" style="height: auto">
                    <option value="1" selected>Enabled</option>
                    <option value="2">Paused</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-2 float-right">Create</button>
    </form>
    <div class="container">

    </div>
    <script>
    var bidding_focus_on=$("#bidding_focus_on");
    var channel_type=$("#channel_type");
    var channel_sub_type=$("#channel_sub_type");
    $(document).ready(function(){
        biddingFocusBaseStrategy();
        channelTypeChangeFunc();

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

    $("#channel_type").on('change',function(){
        channelTypeChangeFunc();
    });

    function channelTypeChangeFunc(){
        var channelSubTypeyArray=['UNIVERSAL_APP_CAMPAIGN','SHOPPING_GOAL_OPTIMIZED_ADS'];
        //start re-arranging everything
        var channel_type_val=channel_type.val();
        $("#channel_sub_type").removeAttr('selected');
        $("#channel_sub_type option").hide();
        $("#div_shipping_setting").hide();
        //end re-arranging everything
        resetAdOptimization();
        if(channel_type_val=="SEARCH"){
            channelSubTypeyArray.push('SEARCH_MOBILE_APP','SEARCH_EXPRESS');
        }
        if(channel_type_val=="DISPLAY"){
            channelSubTypeyArray.push('DISPLAY_MOBILE_APP','DISPLAY_EXPRESS','DISPLAY_SMART_CAMPAIGN','DISPLAY_GMAIL_AD');
        }

        

        if(channel_type_val=="SHOPPING"){
            $("#div_shipping_setting").show();
        }

        if(channelSubTypeyArray.length>0){
            $(channelSubTypeyArray).each(function(i,v){
                $("#channel_sub_type option[value=" + v + "]").show();
            });
        }
    }

    function resetAdOptimization(){
        channelType=channel_type.val();
        channelSubType=channel_sub_type.val();
        if(channelSubType=="UNIVERSAL_APP_CAMPAIGN" || (channelType=="DISPLAY" && channelSubType=="DISPLAY_SMART_CAMPAIGN")){
            $("#ad_rotation3").hide();
            $("#ad_rotation_label3").hide();
        }else{
            $("#ad_rotation3").show();
            $("#ad_rotation_label3").show();
        }
    }

});
</script>
@endsection



