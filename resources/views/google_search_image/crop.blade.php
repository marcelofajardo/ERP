@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rcrop.min.css') }}">
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Google Search Image Crop</h2>
        </div>
    </div>
    @include('partials.flash_messages')
    <form  method="POST" action="{{route('google.search.crop.post')}}" id="formSubmit">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <img id="image_crop" src="{{ $image }}" width="100%">
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <select name="type" id="crop-type" class="form-control">
                    <option value="0">Select Crop Type</option>
                    <option value="8">8</option>
                </select>
                <input type="hidden" name="product_id" value="{{$product_id}}" id="product-id">
                <input type="hidden" name="media_id" value="{{$media_id}}">
                <button type="button" class="btn btn-image my-3" onclick="sendImageMessage()"><img src="/images/filled-sent.png" /></button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
  <script src="{{ asset('js/rcrop.min.js') }}"></script>
  <script>
    function sendImageMessage(){
         crop = $('#crop-type').val();
         if(crop == 0){
            document.getElementById('formSubmit').submit();
         }
         else{
            id = $('#product-id').val();
            sequence = crop;
            $.ajax({
                    url: "{{ route('google.crop.sequence') }}",
                    type: 'POST',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    success: function (response) {
                        $("#loading-image").hide();
                        history.back();
                    },
                    data: {
                        id: id,
                        sequence : sequence,
                        _token: "{{ csrf_token() }}",
                    }
                });
         }
    }
    $(document).ready(function() {
        $('#image_crop').rcrop({full : true});
    });
</script>
@endsection
