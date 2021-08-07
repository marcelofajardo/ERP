@extends('bookstack::base')

@section('head')
    <script src="{{ url('/libs/tinymce/tinymce.min.js?ver=4.9.4') }}"></script>
@stop

@section('body-class', 'flexbox')

@section('content')

    <div class="flex-fill flex">
        <form action="{{ $page->getUrl() }}" autocomplete="off" data-page-id="{{ $page->id }}" method="POST" class="flex flex-fill">
            {{ csrf_field() }}

            @if(!isset($isDraft))
                <input type="hidden" name="_method" value="PUT">
            @endif
            @include('bookstack::pages.form', ['model' => $page])
            @include('bookstack::pages.editor-toolbox')
        </form>
    </div>
    
    @include('bookstack::components.image-manager', ['imageType' => 'gallery', 'uploaded_to' => $page->id])
    @include('bookstack::components.code-editor')
    @include('bookstack::components.entity-selector-popup')

@stop