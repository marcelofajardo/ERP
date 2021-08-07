@extends('layouts.app')

@section('title', 'Master Control')

@section('styles')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <style type="text/css">
   .sub-table{
    padding-top: 0 !important;
    padding-bottom: 0 !important;
   }
  </style>
  
@endsection

@section('content')

  <div class="row mb-5">
      <div class="col-lg-12 margin-tb">
          <h2 class="page-heading">Dev Master Control - {{ date('Y-m-d') }}</h2>
          <div class="pull-left">
          </div>
          <div class="pull-right mt-4">
          </div>
      </div>
  </div>
    @include('master-dev-task.partials.data')
@endsection

@section('scripts')
<script type="text/javascript">
  $(".sub-table").find(".table").hide();
  $(".table").click(function() {
    var $target = $(event.target);
    if ( $target.closest("td").attr("colspan") > 1 ) {
      $target.slideUp();
    } else {
      $target.closest("tr").next().find(".table").slideToggle();
    }                    
  });
</script>
@endsection

