@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Edit Review</h2>
        </div>
        <div class="col-md-12">
            <form action="{{ action('ReviewController@update', $review->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="title" value="{{ $review->title }}" type="text" class="form-control" placeholder="Enter Title...">
                </div>
                <div class="form-group">
                    <label for="review">About this review..</label>
                    <textarea class="form-control review-editor-box" data-id="{{$review->id}}" name="review" id="review" rows="3" placeholder="Enter Body...">{{ $review->review }}</textarea>
                    <span class="letter_count_review_{{$review->id}}">{{strlen($review->review)}}</span>
                </div>
                <div class="text-right">
                    <button class="btn btn-success">Attach A Review</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style>
        thead input {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.review-editor-box').keyup(function() {
                let data = $(this).val();
                let length = data.length;
                let id = $(this).attr('data-id');
                $('.letter_count_review_'+id).html(length);
            });
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection