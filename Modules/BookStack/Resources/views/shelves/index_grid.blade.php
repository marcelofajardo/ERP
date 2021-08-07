@extends('bookstack::tri-layout_grid')
@extends('bookstack::base')
@section('favicon' , 'shstid.png')

@section('title', 'Shelves')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@3.1.0/dist/tagify.css" />
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@3.1.0/dist/tagify.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script> -->
    <style type="text/css"> 
        .tagify__input{
            display: inline-block;
        }
    </style>
@endsection

@section('large_content')
        @php
            $user = auth()->user();
            $isAdmin = $user->isAdmin();
            $hod = $user->hasRole('HOD of CRM');
        @endphp

    <div class="row mb-5">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Knowledge Base List <span class="total-info"></span></h2>
        </div>
    </div>

    @include('partials.flash_messages')
    <?php $status = request()->get('status', ''); ?>
    <?php $excelOnly = request()->get('excelOnly', ''); ?>

@php
$tags = '';
if(isset($request['tags'])){
    foreach(json_decode($request['tags']) as $key =>$tag){
        if($key == 0){
            $tags .= $tag->value;
        }
        $tags .= ',' . $tag->value;
    }
}    
$exact = '';
if(isset($request['exact'])){
    foreach(json_decode($request['exact']) as $key =>$tag){
        if($key == 0){
            $exact .= $tag->value;
        }
        $exact .= ',' . $tag->value;
    }
}
@endphp
    <form action="/searchGrid" class="form-inline align-items-start">
        <div class="row"> 
            <div class="form-group mr-3 mb-3">
                <input name="keywords" type="text" class="form-control" id="keywords" value="{{$request['keywords'] ?? ''}}" placeholder="Search by book,shelf,page,chapter">
            </div>
            
            <div class="form-group mr-3">
                <select class="form-control select-multiple0 select-multiple2" name="type[]" multiple data-placeholder=" Select Entity">
                    <option value="book" {{ isset($request['type']) && in_array('book', $request['type']) == 'book' ? 'selected' : (isset($request['type']) ? '' : 'selected') }}>Book</option>
                    <option value="bookshelf" {{ isset($request['type']) && in_array('bookshelf', $request['type']) == 'bookshelf' ? 'selected' : (isset($request['type']) ? '' : 'selected') }}>Shelf</option>
                    <option value="chapter" {{ isset($request['type']) && in_array('chapter', $request['type']) == 'chapter' ? 'selected' : (isset($request['type']) ? '' : 'selected') }}>Chapter</option>
                    <option value="page" {{ isset($request['type']) && in_array('page', $request['type']) == 'page' ? 'selected' : (isset($request['type']) ? '' : 'selected') }}>Page</option>
                </select>
            </div>
            <div class="form-group mr-3">
                <select class="form-control select-multiple0 select-multiple2" name="options[]" multiple data-placeholder=" Select Options">
                    <option value="viewed_by_me" {{ isset($request['options']) && in_array('viewed_by_me', $request['options']) == 'viewed_by_me' ? 'selected' : '' }}>Viewed by me</option>
                    <option value="not_viewed_by_me" {{ isset($request['options']) && in_array('not_viewed_by_me', $request['options']) == 'not_viewed_by_me' ? 'selected' : '' }}>Not viewed by me</option>
                    <option value="is_restricted" {{ isset($request['options']) && in_array('is_restricted', $request['options']) == 'is_restricted' ? 'selected' : '' }}>Permissions set</option>
                    <option value="created_by_me" {{ isset($request['options']) && in_array('created_by_me', $request['options']) == 'created_by_me' ? 'selected' : '' }}>Created by me</option>
                    <option value="updated_by_me" {{ isset($request['options']) && in_array('updated_by_me', $request['options']) == 'updated_by_me' ? 'selected' : '' }}>Updated by me</option>
                </select>
            </div>
            <div class="form-group mr-3 mb-3">
                <input name="tags" type="text" class="form-control" id="tags" value="{{$tags}}" placeholder="Tags" multiple>
            </div>
            <div class="form-group mr-3 mb-3">
                <input name="exact" type="text" class="form-control" id="exact" value="{{$exact}}" placeholder="Exact Match" multiple>
            </div>
            <div class="form-group mr-3 mb-3">
                <input class="calender" placeholder="Updated after" style="max-width: 190px"type="text" onfocus="(this.type='date')"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" name="updated_after" value="{{$request['updated_after'] ?? ''}}">
                <input class="calender" placeholder="Updated before" style="max-width: 190px"type="text" onfocus="(this.type='date')"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" name="updated_before" value="{{$request['updated_before'] ?? ''}}">
                <input class="calender" placeholder="Created after" style="max-width: 190px"type="text" onfocus="(this.type='date')"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" name="created_after" value="{{$request['created_after'] ?? ''}}">
                <input class="calender" placeholder="Created before" style="max-width: 190px"type="text" onfocus="(this.type='date')"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" name="created_before" value="{{$request['created_before'] ?? ''}}">
            </div> 
            <div class="form-group mr-3">
                <button type="submit" class="btn btn-image filter_btn" ><img src="/images/filter.png"></button>
            </div>
        </div>
    </form>   
   <?php $totalCountedUrl = 0; ?>
    <div class="row no-gutters mt-3">
        <div class="col-md-12" id="plannerColumn">
            <div class="">
                <table class="table table-bordered table-striped sort-priority-scrapper">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Entity</th> 
                        <th>Name</th> 
                        <th>Description</th> 
                        <th>Created at</th> 
                        <th>Updated at</th> 
                        <th>Action</th> 
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($entities as $entity)
                    <tr>
                        <td width="5%">{{$i++}}</td> 
                        <td width="15%">{{$entity->getType()}}</td> 
                        <td width="15%">{{$entity->name}}</td> 
                        <td width="15%">{{$entity->description}}</td> 
                        <td width="15%">{{$entity->created_at}}</td> 
                        <td width="15%">{{$entity->updated_at}}</td> 
                        <td width="50%">
                            <div style="float:left;">       
                                <button style="padding:1px;" type="button" class="btn btn-image d-inline" onclick='showEntity("{{$entity->getType()}}", "{{$entity->getType() == 'page' ? $entity->id : $entity->slug}}", "{{in_array($entity->getType(), ['chapter', 'page']) ? $entity->book->name : null }}")' title="Show Shelf">
                                <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>  
            </div>
        </div>
    </div>

    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
               50% 50% no-repeat;display:none;">
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/bootstrap-datepicker.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">

    new Tagify(document.querySelector('input[name=tags]'));
    new Tagify(document.querySelector('input[name=exact]'));

    
    $('#filter-date').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $(document).ready(function(){
        $('.search-box').addClass('d-none');
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
    });

    $('.sortByDate').select2();
    $('.sortByView').select2();
    $('.pageType').select2();
  
  function showEntity(type, slug, book = null){
      let des_url = '/kb/';
      if(type == 'bookshelf'){
        des_url += 'shelves/' + slug;
      } 
      if(type == 'book'){
        des_url += 'books/' + slug;
      } 
      if(type == 'page'){
        des_url += 'books/' + book.split(' ').join('-') + '/draft/' + slug.split(' ').join('-');
      } 
      if(type == 'chapter'){
        des_url += 'books/' + book.split(' ').join('-') + '/chapter/' + slug.split(' ').join('-');
      } 
      window.location = des_url;
  } 

    
</script>
@endsection
