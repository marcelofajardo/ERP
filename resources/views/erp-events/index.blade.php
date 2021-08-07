@extends('layouts.app')

@section('title', 'Erp Events')

@section("styles")
<link rel="stylesheet" type="text/css" href="/css/clndr.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Erp Events</h2>
  </div>
  <div class="col-md-12">
  	<div class="container">
        <div id="full-clndr" class="clearfix">
          <script type="text/template" id="full-clndr-template">
            <div class="clndr-controls">
              <div class="clndr-previous-button">&lt;</div>
              <div class="clndr-next-button">&gt;</div>
              <div class="current-month"><%= month %> <%= year %></div>

            </div>
            <div class="clndr-grid">
              <div class="days-of-the-week clearfix">
                <% _.each(daysOfTheWeek, function(day) { %>
                  <div class="header-day"><%= day %></div>
                <% }); %>
              </div>
              <div class="days">
                <% _.each(days, function(day) { %>
                  <div class="<%= day.classes %>" id="<%= day.id %>"><span class="day-number"><%= day.day %></span></div>
                <% }); %>
              </div>
            </div>
            <div class="event-listing">
              <div class="event-listing-title">EVENTS THIS MONTH</div>
              <% _.each(eventsThisMonth, function(event) { %>
                  <div class="event-item">
                  	<div class="event-item-name"><%= event.startDate %> - <%= event.endDate %> [<%= event.title %>]</div>
                  </div>
                <% }); %>
            </div>
          </script>
        </div>
    </div>
    <div class="modal" id="addEvent" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Create Event</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <form id="submit-event-form">
		      <div class="modal-body">
		      	  {{ csrf_field() }}
		          <div class="form-group">
				    <label for="event_name">Event Name</label>
				    <input type="text" name="event_name" class="form-control" id="event_name" placeholder="Enter your event name">
				  </div>
				  <div class="form-row">
				  	<div class="form-group col-md-6">
				    	<label for="start_date">Start date</label>
				    	<input type="text" name="start_date" class="form-control datepicker" id="start_date">
				  	</div>
				  	<div class="form-group col-md-6">
				    	<label for="end_date">End date</label>
				    	<input type="text" name="end_date" class="form-control datepicker" id="end_date">
				  	</div>
				  </div>
				  <div class="form-row">
					  <div class="form-group col-md-6">
					    <label for="type">Brand</label>
					    <?php echo Form::select("brand_id[]",\App\Brand::where("name","!=", "")->get()->pluck("name","id")->toArray(),null, [
					    	"class" => "form-control select-2" ,
					    	"multiple" => true,
					    	"style" => "width:100%;" 
					    ]); ?>
					  </div>
					  <div class="form-group col-md-6">
					    <label for="category_id">Category</label>
					    <?php echo Form::select("category_id[]",\App\Category::where("title","!=", "")->get()->pluck("title","id")->toArray(),null, [
					    	"class" => "form-control select-2" ,
					    	"multiple" => true,
					    	"style" => "width:100%;"
					    ]); ?>
					  </div>
				  </div>
				  <div class="form-group">
				    <label for="number_of_person">Number of person</label>
				    <input type="text" name="number_of_person" class="form-control" id="number_of_person">
				  </div>
				  <div class="form-row">
					  <div class="form-group col-md-6">
					    <label for="product_start_date">Product Created on</label>
					    <input type="text" name="product_start_date" class="form-control datepicker" id="product_start_date">
					  </div>
					  <div class="form-group col-md-6">
					    <label for="product_end_date">Product Created end</label>
					    <input type="text" name="product_end_date" class="form-control datepicker" id="product_end_date">
					  </div>
					</div>
				  	<div class="form-row">
					    <div class="form-group col-md-6">
					      <label for="cron_minutes">Minute(s)</label>
					      <?php echo Form::select("minute",config("constants.cron_minutes"),null,["class" => "form-control", "id" => "cron_minutes"]); ?>
					    </div>
					    <div class="form-group col-md-6">
					      <label for="cron_hours">Hour(s)</label>
					      <?php echo Form::select("hour",config("constants.cron_hours"),null,["class" => "form-control", "id" => "cron_hours"]); ?>
					    </div>
					    <div class="form-group col-md-6">
					      <label for="cron_months">Months(s)</label>
					      <?php echo Form::select("month",config("constants.cron_months"),null,["class" => "form-control", "id" => "cron_months"]); ?>
					    </div>
					    <div class="form-group col-md-6">
					      <label for="cron_days">Day(s)</label>
					      <?php echo Form::select("day_of_month",config("constants.cron_days"),null,["class" => "form-control", "id" => "cron_days"]); ?>
					    </div>
					    <div class="form-group col-md-6">
					      <label for="cron_weekdays">Weekday(s)</label>
					      <?php echo Form::select("day_of_week",config("constants.cron_weekdays"),null,["class" => "form-control", "id" => "cron_weekdays"]); ?>
					    </div>
					</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-primary btn-submit-event">Save changes</button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		 </form>
	    </div>
	  </div>
	</div>
  </div>
</div>
@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="/js/clndr/clndr.min.js"></script>
	<script type="text/javascript">
		// Call this from the developer console and you can control both instances
var calendars = {};

$(document).ready( function() {

	$(".select-2").select2({tags :true});
	$(".datepicker").datepicker({
	  	format: 'yyyy-mm-dd'
	});

	$(document).on("click",".btn-submit-event",function(){
		var form = $("#submit-event-form");	
		  $.ajax({
	        method : "post",
	        url: "/erp-events/store",
	        data : form.serialize(),
	        dataType : "json"
	      }).done(function(data) {
	         location.reload();
	      }).fail(function() {
	        alert('Error loading more purchases');
	      });
	});
	 // Assuming you've got the appropriate language files,
	    // clndr will respect whatever moment's language is set to.
	    // moment.locale('ru');

	    // Here's some magic to make sure the dates are happening this month.
	     var currentMonth = moment().format('YYYY-MM');
 		 var nextMonth    = moment().add('month', 1).format('YYYY-MM');

 		 var eventArray = JSON.parse('<?php echo json_encode($listEvents); ?>');


		  clndr = $('#full-clndr').clndr({
		  	clickEvents: {
	            click: function (target) {
	                $("#start_date").val(target.date._i);
	                $("#end_date").val(target.date._i);
	                $("#addEvent").modal("show");
	            }
	        },
	        multiDayEvents: {
	            singleDay: 'date',
	            endDate: 'endDate',
	            startDate: 'startDate'
	        },

		    template: $('#full-clndr-template').html(),
		    events: eventArray,
		    forceSixRows: true
		  });

	    // Bind all clndrs to the left and right arrow keys
	    $(document).keydown( function(e) {
	        // Left arrow
	        if (e.keyCode == 37) {
	            calendars.clndr1.back();
	        }

	        // Right arrow
	        if (e.keyCode == 39) {
	            calendars.clndr1.forward();
	        }
	    });
	});
	</script>
@endsection
@endsection
