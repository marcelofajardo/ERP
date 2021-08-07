@extends('layouts.app')
@section('favicon' , 'affilate-management.png')

@section('title', 'Affiliates Info')

@section('styles')

<style type="text/css">
    #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
</style>
@endsection
@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Affiliates Listing (<span id="affiliate_count">{{ $data->total() }}</span>)</h2>
            <div class="pull-left">
                <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="search affiliate" id="term">
                                
                            </div>
                            <div class="col-md-4">
                            <?php echo Form::select("type",["" => "Select Type", "affiliate" => "Affiliate" , "influencer" => "Influencer"],request('type'),["class" =>"form-control type-filter"]) ?>
                            </div>
                            <div class="col-md-4">
                               <button type="button" class="btn btn-image" onclick="submitSearch()"><img src="/images/filter.png"/></button>
                               <button type="button" class="btn btn-image" id="resetFilter" onclick="resetSearch()"><img src="/images/resend2.png"/></button>    
                                <button type="button" onclick="delete_multiple()" class="btn btn-image" title="delete multiple affiliates"><img src="/images/delete.png"/></button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive">
        <table class="table table-bordered" id="affiliates-table">
              <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Source</th>
                <th>Email</th>
                <th>Visitors/month</th>
                <th>Page views/month</th>
                <th>FB followers</th>
                <th>Insta followers</th>
                <th>Youtube followers</th>
                <th>Linkedin followers</th>
                <th>Pinterest followers</th>
                <th>Country</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @include('affiliates.partials.list-affiliate')
            </tbody>
        </table>
    </div>

    {!! $data->render() !!}

    <div class="modal fade bd-affiliate-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">

           </div> 
        </div>
      </div>
    </div>


@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = "{{route('affiliates.list')}}"
        term = $('#term').val()
        type = $('.type-filter').val()
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                term : term,
                type: type
            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#affiliates-table tbody").empty().html(data.tbody);
            $("#affiliate_count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
        
    }

    function resetSearch(){
        src = "{{route('affiliates.list')}}"
        blank = ''
        $.ajax({
            url: src,
            dataType: "json",
            data: {
               
               blank : blank, 

            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $('#term').val('')
            $('#affiliate-select').val('')
            $("#affiliates-table tbody").empty().html(data.tbody);
            $("#affiliate_count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }
    function delete_multiple(){
        var values = new Array();
        $.each($("input[name='affilate_multi_select[]']:checked"), function() {
          values.push($(this).val());
        });
        if(values==''){
            alert('please select affilates for removing first !');
            return;
        }
        $.ajax({
            url:"{{route('affiliates.destroy')}}",
            type:'POST',
            data: {"_token": "{{ csrf_token() }}",
                    id:values
                  },
            success:function(data){
                location.reload();
            }
        });
    } 

    $(document).on("click",".get-details",function(){
        var $this = $(this);
        var id = $(this).data("id");
        $.ajax({
            url:"affiliates/"+id+"/edit",
            type:'GET',
            success:function(data){
                if(data.code == 200) {
                    var data = data.data;
                    var html = `<div class="col-md-12">
                                <div class="form-group">
                                    <label for="frequency">Location</label>
                                    <input type="text" readonly name="Location" id="Location" value="`+data.location+`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">caption</label>
                                    <input type="text" readonly name="caption" id="caption" value="`+data.caption+`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Posted at</label>
                                    <input type="text" readonly name="posted_at" id="posted_at" value="`+data.posted_at +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Source</label>
                                    <input type="text" readonly name="source" id="source" value="`+data.source +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Address</label>
                                    <input type="text" readonly name="address" id="address" value="`+data.address +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Facebook</label>
                                    <input type="text" readonly name="facebook" id="facebook" value="`+data.facebook +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Facebook followers</label>
                                    <input type="text" readonly name="facebook_followers" id="facebook_followers" value="`+data.facebook_followers +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Instagram</label>
                                    <input type="text" readonly name="instagram" id="instagram" value="`+data.instagram +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Instagram followers</label>
                                    <input type="text" readonly name="instagram_followers" id="instagram_followers" value="`+data.instagram_followers +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Twitter</label>
                                    <input type="text" readonly name="twitter" id="twitter" value="`+data.twitter +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Twitter followers</label>
                                    <input type="text" readonly name="twitter_followers" id="twitter_followers" value="`+data.twitter_followers +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Youtube</label>
                                    <input type="text" readonly name="youtube" id="youtube" value="`+data.youtube +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Linkedin</label>
                                    <input type="text" readonly name="linkedin" id="linkedin" value="`+data.linkedin +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Linkedin followers</label>
                                    <input type="text" readonly name="linkedin_followers" id="linkedin_followers" value="`+data.linkedin_followers +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Pinterest</label>
                                    <input type="text" readonly name="pinterest" id="pinterest" value="`+data.pinterest +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Pinterest followers</label>
                                    <input type="text" readonly name="pinterest_followers" id="pinterest_followers" value="`+data.pinterest_followers +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Phone</label>
                                    <input type="text" readonly name="phone" id="phone" value="`+data.phone +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Email address</label>
                                    <input type="text" readonly name="emailaddress" id="emailaddress" value="`+data.emailaddress +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Title</label>
                                    <input type="text" readonly name="title" id="title" value="`+data.title +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Is flagged</label>
                                    <input type="text" readonly name="is_flagged" id="is_flagged" value="`+data.is_flagged +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">First name</label>
                                    <input type="text" readonly name="first_name" id="first_name" value="`+data.first_name +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Last name</label>
                                    <input type="text" readonly name="last_name" id="last_name" value="`+data.last_name +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Url</label>
                                    <input type="text" readonly name="url" id="url" value="`+data.url +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Website name</label>
                                    <input type="text" readonly name="website_name" id="website_name" value="`+data.website_name +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Unique visitors per month</label>
                                    <input type="text" readonly name="unique_visitors_per_month" id="unique_visitors_per_month" value="`+data.unique_visitors_per_month +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Page views per month</label>
                                    <input type="text" readonly name="page_views_per_month" id="page_views_per_month" value="`+data.page_views_per_month +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Worked on</label>
                                    <input type="text" readonly name="worked_on" id="worked_on" value="`+data.worked_on +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">City</label>
                                    <input type="text" readonly name="city" id="city" value="`+data.city +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Postcode</label>
                                    <input type="text" readonly name="postcode" id="postcode" value="`+data.postcode +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Country</label>
                                    <input type="text" readonly name="country" id="country" value="`+data.country +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Type</label>
                                    <input type="text" readonly name="type" id="type" value="`+data.type +`" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="frequency">Store website id</label>
                                    <input type="text" readonly name="store_website_id" id="store_website_id" value="`+data.store_website_id +`" class="form-control">
                                </div>
                    </div>`;

                    $(".bd-affiliate-modal-lg").find(".modal-body").html(html);
                    $(".bd-affiliate-modal-lg").modal("show");
                }
            }
        });
    });

</script>

@endsection
