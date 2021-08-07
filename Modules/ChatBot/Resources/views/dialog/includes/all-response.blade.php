@foreach($dialogResponses as $reponse)
<div class="row">
    <div class="col-md-12">
        @if($reponse->storeWebsite)
        <p>{{$reponse->storeWebsite->title}}</p>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <textarea name="" id="response-{{$reponse->id}}" class="form-control" style="width:100%;">{{$reponse->value}}</textarea>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-xs btn-secondary update-response" data-id="{{$reponse->id}}">Update</button>
    </div>
</div>
<br>
@endforeach