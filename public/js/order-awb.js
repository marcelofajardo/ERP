$(document).on('click', '#generateAWB', function() {
  $('#generateAWBForm').submit();
});

$(document).on("click",".btn-rate-request",function(e) {
    e.preventDefault();
    var form = $(this).closest("form");
    //send request
    $.ajax({
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      },
      url: BASE_URL+'/order/generate/awb/rate-request',
      data: form.serialize(),
      beforeSend: function() {
        $('.ajax-loader').show();
      },
    }).done(response => {
      $('.ajax-loader').hide();
      if(response.code == 200) {
        toastr['success'](response.message, 'success');
        var htmlReturn = "";
        if(response.data) {
            $.each(response.data, function(key , result) {
              htmlReturn += "<div class='col-md-4'> <ul style='list-style:none;'>";
               if(result.charges && result.charges.length > 0) {
                 $.each(response.data.charges,function(k,v) {
                    htmlReturn += "<li>"+v.name+" : "+v.amount+"</li>";
                 });
              }
              htmlReturn += "<li>Total : "+result.amount+" "+result.currency+"</li>";
              htmlReturn += "<li>Delivery Time : "+result.delivery_time+"</li>";
              htmlReturn += "<li>Service Type : "+result.service_type+"</li>";
              htmlReturn += "<li>Total Transit day : "+result.total_transit_days+"</li>";
              htmlReturn += "</ul></div>";
            });
        }
        $(".price-break-down").html(htmlReturn);
    }else{
	  toastr['error'](response.message, 'error');
    }
  });
});

  $(document).on("click",".btn-create-shipment-request",function(e) {
    e.preventDefault();
    var form = $(this).closest("form");
      //send request
      $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/order/generate/awb/dhl',
    		data: form.serialize(),
    		beforeSend: function() {
    			$('.ajax-loader').show();
    		},
      }).done(response => {
  			 $('.ajax-loader').hide();
          if(response.code == 200) {
            toastr['success'](response.message, 'success');
            location.reload();
          }else{
            toastr['error'](response.message, 'error');
          }
      }).fail(error => {
          $('.ajax-loader').hide();
          var errors = "";
          $.each(error.responseJSON.errors, function(key,value) {
            errors += "<span>"+value+"</span></br>";
         }); 
          $('#validation-errors').append('<div class="alert alert-danger">'+errors+'</div');
      });
  });
      
  $('#completion-datetime, #pickup-datetime').datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
  });

  $(document).on('click','.track-package-slip',function() {
      var $this = $(this);
      $.ajax({
        type: "GET",
        url: "/order/track/packageSlip",
        data: {
          _token: jQuery('meta[name="csrf-token"]').attr('content'),
          awb: $this.data("awb"),
          id: $this.data("waybill_id"),
        },
        beforeSend: function() {
          $this.text('Tracking...');
        }
      }).done(function(response) {
        $this.text('Track Package Slip');
        $("#tracking-events").find(".abw-no-txt").html(response.awb);
        $("#tracking-events").find(".modal-body").html(response._h);
        $("#tracking-events").modal("show");
      }).fail(function(response) {
    
      });
  });

  $(document).on('click', '.track-shipment-button', function() {
    var thiss = $(this);
    var order_id = $(this).data('id');
    var awb = $('#awb_field_' + order_id).val();

    $.ajax({
      type: "POST",
      url: "/stock/track/package",
      data: {
        _token: jQuery('meta[name="csrf-token"]').attr('content'),
        awb: awb
      },
      beforeSend: function() {
        $(thiss).text('Tracking...');
      }
    }).done(function(response) {
      $(thiss).text('Track');

      $('#tracking-container-' + order_id).html(response);
    }).fail(function(response) {
      $(thiss).text('Tracking...');
      alert('Could not track this package');
      console.log(response);
    });
  });