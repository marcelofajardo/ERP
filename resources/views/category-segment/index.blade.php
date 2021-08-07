@extends('layouts.app')

@section('content')
<?php
$query = http_build_query(Request::except('page'));
$query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
?>
<div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;">
    Goto :
    <select onchange="location.href = this.value;" class="form-control" id="page-goto">
        @for($i = 1 ; $i <= $category_segments->lastPage() ; $i++ )
            <option value="{{ $query.$i }}" {{ ($i == $category_segments->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
    </select>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Category Segment List (<span>{{ $category_segments->total() }}</span>) </h2>
		<div class="pull-right">
			<a href="{{ route('category-segment.create') }}" class="btn btn-secondary">+</a>
		</div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="row">
    <div class="col-12 mt-1">
        <div class="form-inline">
            <form>
                <div class="form-group">
                    <input type="text" value="{{ request('keyword') }}" name="keyword" id="search_text" class="form-control" placeholder="Enter keyword for search">
                </div>
                <button type="submit" class="btn btn-secondary ml-3">Search</button>
            </form>
        </div>
    </div>
</div>
<br>
<?php 
    $bList = \App\Brand::pluck('name','id')->toArray();
?>
<div class="infinite-scroll">
    {!! $category_segments->links() !!}
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            @foreach ($category_segments as $key => $category_segment)
            <tr>
                <td>{{ $category_segment->id }}</td>
                <td>{{ $category_segment->name }}</td>
                <td>
                    @if($category_segment->status == 1)
                        Active
                    @elseif($category_segment->status == 2)
                        Blocked
                    @else
                        Inactive
                    @endif
                </td>
                <td>
                    <a class="btn btn-image" href="{{ route('category-segment.edit',$category_segment->id) }}"><img src="/images/edit.png" /></a>
                    {!! Form::open(['method' => 'DELETE','route' => ['category-segment.destroy',$category_segment->id],'style'=>'display:inline', 'class'=> 'delete']) !!}
                    <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script src="/js/jquery-ui.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 2500,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').first().remove();
                $(".select-multiple").select2();
            }
        });
    });
    $(".delete").on("submit", function(){
        return confirm("Are you sure want to take this action?");
    });
</script>
@endsection