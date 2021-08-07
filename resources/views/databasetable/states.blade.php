@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'States | Database')

@section('content')

<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">States | Database</h2>
	</div>
</div>

<div class="row">
    <div class="col-xl-12">
      <div class="card-box">
        <h4 class="header-title mb-3">Query Process List <a href="javascript:;" id="refresh-process-list"><span class="glyphicon glyphicon-refresh"></span></a></h4>
        <div class="table-responsive table-process-list-disp">
        </div>
      </div>
  </div>
</div>
@include("database.partial.template")
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif')
  50% 50% no-repeat;display:none;">
</div>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript">

  var showProcessList = function() {
      $.ajax({
          type: 'GET',
          url: "<?php echo route('database.process.list'); ?>",
          dataType:"json"
        }).done(function(response) {
          if(response.code == 200) {
            var tpl = $.templates("#template-list-state-process-list");
            var tplHtml       = tpl.render(response);
                $(".table-process-list-disp").html(tplHtml);
          }
        }).fail(function(response) {
          console.log("Sorry, something went wrong");
        });
  };

  showProcessList();

  $(document).on("click","#refresh-process-list",function() {
      showProcessList();
  });

  $(document).on("click",".kill-process",function(){
        var $this = $(this);
        $.ajax({
          type: 'GET',
          url: "<?php echo route('database.process.kill'); ?>",
          data : {id : $this.data("id")},
          dataType:"json"
        }).done(function(response) {
          if(response.code == 200) {
            showProcessList();
          }
        }).fail(function(response) {
          console.log("Sorry, something went wrong");
        });
  });

</script>
@endsection