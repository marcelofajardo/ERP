@extends('layouts.app')

@section('title', __('Media Manager'))

@section('content')
 <link rel="stylesheet" href="{{ asset('/css/instagram.css') }}?v={{ config('pilot.version') }}">
    <style type="text/css">
        .imagecheck-image{
            height: 100px !important;
            width: 100px !important;
        }
    </style>
    <div class="card media-manager">
        <div class="card-header pl-3">
            <h3 class="card-title">@lang('Media')</h3>
            <small class="ml-3 text-gray">Total ( {{ $used_space }} )</small>
            <div class="card-options">
                <button type="button" class="btn btn-secondary btn-sm btn-delete mr-3" disabled>
                    <i class="fe fe-trash"></i> <span class="d-none d-md-inline">{{ __('Delete selected') }}</span>
                </button>
                <span class="btn btn-primary btn-sm btn-upload">
                    <i class="fe fe-upload"></i> <span class="d-none d-md-inline">@lang('Upload')</span>
                    <input type="file" name="files[]" data-url="{{ route('media.upload') }}" multiple />
                </span>
            </div>
        </div>
        <div class="p-3">
            <div class="dimmer active">
                <div class="loader"></div>
                <div class="dimmer-content">
                    <div class="d-flex flex-wrap align-content-start media-files-container"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="{{ asset('/js/instagram.js') }}?v={{ config('pilot.version') }}" type="text/javascript"></script>

@stop

