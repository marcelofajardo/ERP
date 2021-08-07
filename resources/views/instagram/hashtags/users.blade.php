@extends('layouts.app')

@section('favicon' , 'instagram.png')

@section('title', 'Hashtag Users Info')

@section('styles')
<style type="text/css">
         #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection
@section('large_content')
    <div id="myDiv">
      <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
   </div>
    <div class="row">
        <div class="col-md-12">
           <h2 class="page-heading">Users (<span id="total">{{ $users->total() }}</span>)</h2>
            <div class="pull-left">
                <div class="row">
                <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control global" id="term"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="name">
                </div>
                <div class="form-group mr-3 mb-3">
                    <button type="button" class="btn btn-image" onclick="resetSearch()"><img src="/images/clear-filters.png"/></button> 
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table-striped table table-bordered" id="data-table">
                    <thead >
                    <tr>
                        <th>Username</th>
                        <th>Location</th>
                        <th>Because of</th>
                        <th>Followers</th>
                        <th>Following</th>
                        <th>Posts</th>
                        <th>Bio</th>
                    </tr>
                   </thead>
                     <tbody>
                   @include('instagram.hashtags.partials.users-data')
                    </tbody>
                </table>
                
                 {!! $users->render() !!}
            </div>
        </div>

        
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.expand-row', function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

         $(document).ready(function() {
        src = "/instagram/hashtag/users/{{ $id }}";
        $(".global").autocomplete({
        source: function(request, response) {
            term = $('#term').val();
            
            
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : term,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $('#total').val(data.total)
                $("#data-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });

     function resetSearch(){
         blank = '';
         $.ajax({
                url: src,
                dataType: "json",
                data: {
                    blank : blank,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $('#term').val('')
                $('#total').val(data.total)
                $("#data-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
     }    
    </script>

@endsection