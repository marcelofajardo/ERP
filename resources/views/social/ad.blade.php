@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="ml-4">Create Ad <h2>
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
                <form action="{{route('social.ad.store')}}" method="post" enctype="multipart/form-data" style="border:1px solid #dedede;padding:4%;border-radius: 5px">
                   @csrf

                   <div class="form-group">
                    <label for="">Choose Existing Adset</label>
                    <select class="form-control" name="adset_id" >
                        <?php foreach($adsets as $adset): ?>
                            <option value="<?=$adset->id ?>"><?=$adset->name; ?></option>
                        <?php endforeach; ?>
                    </select>

                    @if ($errors->has('adset_id'))
                    <p class="text-danger">{{$errors->first('adset_id')}}</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Choose Existing AdCreatives</label>
                    <select class="form-control" name="adcreative_id" >
                        <?php foreach($adcreatives as $adcreative): ?>
                            <option value="<?=$adcreative->id ?>"><?=$adcreative->name; ?></option>
                        <?php endforeach; ?>
                    </select>

                    @if ($errors->has('adset_id'))
                    <p class="text-danger">{{$errors->first('adset_id')}}</p>
                    @endif
                </div>


                <div class="form-group">
                    <label for="">Ad Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Type your ad name">
                    @if ($errors->has('name'))
                    <p class="text-danger">{{$errors->first('name')}}</p>
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

              <button type="submit" class="btn btn-info">Create Ad</button>
          </form>
      </div>
      <div class="col-md-4"></div>
  </div>

</div>





@endsection