 @include('google.affiliate.partials.modal-email')
 @foreach($posts as $key=>$post)
 <tr>

   <td><input type="checkbox" class="affliateDelete" id="{{$post->id}}" /></td>
   <td>{{ date('d-M-Y H:i:s', strtotime($post->posted_at)) }}</td>

   <td>{{ $post->hashTags->hashtag }}</td>
   <td>
     {{ $post->title }}
     @if ($post->emailaddress != '')
     <br>
     <span class="text-muted">
       <a href="#" class="send-supplier-email" data-toggle="modal" data-target="#emailSendModal" data-id="{{ $post->id }}">{{ $post->emailaddress }}</a>
       @if ($post->has_error == 1)
       <span class="text-danger">!!!</span>
       @endif
     </span>
     @endif
     <br>
     @if ($post->is_flagged == 1)
     <button type="button" class="btn btn-image flag-supplier" data-id="{{ $post->id }}"><img src="/images/flagged.png" /></button>
     @else
     <button type="button" class="btn btn-image flag-supplier" data-id="{{ $post->id }}"><img src="/images/unflagged.png" /></button>
     @endif
     <button type="button" class="btn btn-image make-remark d-inline" data-toggle="modal" data-target="#affiliateRemarkModal" data-id="{{ $post->id }}"><img src="/images/remark.png" /></button>
   </td>
   <td>{{ $post->address }}</td>
   <td style="word-break:break-all; white-space: normal; line-height:190%">
     @if ($post->phone == '' && $post->location == '' && $post->emailaddress == '' && $post->facebook == '' && $post->instagram == '' && $post->twitter == '' && $post->youtube == '' && $post->linkedin == '' && $post->pinterest == '')
     N/A
     @endif
     @if ($post->phone != '')
     <img src="{{ asset('images/call.png') }}" alt="" style="width: 18px;"> {{ $post->phone }}<br>
     @endif
     @if ($post->location != '')
     <img src="{{ asset('images/url_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->location }}" target="_blank">{{ $post->location }}</a><br>
     @endif
     @if ($post->facebook != '')
     <img src="{{ asset('images/facebook_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->facebook }}" target="_blank">{{ $post->facebook }}</a><br>
     @endif
     @if ($post->instagram != '')
     <img src="{{ asset('images/instagram_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->instagram }}" target="_blank">{{ $post->instagram }}</a><br>
     @endif
     @if ($post->twitter != '')
     <img src="{{ asset('images/twitter_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->twitter }}" target="_blank">{{ $post->twitter }}</a><br>
     @endif
     @if ($post->youtube != '')
     <img src="{{ asset('images/youtube_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->youtube }}" target="_blank">{{ $post->youtube }}</a><br>
     @endif
     @if ($post->linkedin != '')
     <img src="{{ asset('images/linkedin_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->linkedin }}" target="_blank">{{ $post->linkedin }}</a><br>
     @endif
     @if ($post->pinterest != '')
     <img src="{{ asset('images/pinterest_128.png') }}" alt="" style="width: 18px;"> <a style="word-break:break-all; white-space: normal;" href="{{ $post->pinterest }}" target="_blank">{{ $post->pinterest }}</a><br>
     @endif
   </td>
   <td class="expand-row">
     <div class="td-mini-container">
       {{ strlen($post->caption) > 30 ? substr($post->caption, 0, 30).'...' : $post->caption }}
     </div>
     <div class="td-full-container hidden">
       {{ $post->caption }}
     </div>
   </td>
 </tr>
 @endforeach
 <script type="text/javascript">
   $(document).on('click', '.flag-supplier', function() {
     var affiliate_id = $(this).data('id');
     var thiss = $(this);

     $.ajax({
       type: "POST",
       url: "{{ route('affiliate.flag') }}",
       data: {
         _token: "{{ csrf_token() }}",
         id: affiliate_id
       },
       beforeSend: function() {
         $(thiss).text('Flagging...');
       }
     }).done(function(response) {
       if (response.is_flagged == 1) {
         $(thiss).html('<img src="/images/flagged.png" />');
       } else {
         $(thiss).html('<img src="/images/unflagged.png" />');
       }

     }).fail(function(response) {
       $(thiss).html('<img src="/images/unflagged.png" />');

       alert('Could not flag supplier!');

       console.log(response);
     });
   });

   $(document).on('click', '.send-supplier-email', function() {
     var id = $(this).data('id');

     $('#emailSendModal').find('input[name="affiliate_id"]').val(id);
   });

   $(document).on('click', '.affliateDelete', function() {
     var id = $(this).attr('id');

    //  if (confirm("Are you sure you want to delete ?")) {

    //    $.ajax({
    //      type: "DELETE",
    //      headers: {
    //        "X-CSRF-TOKEN": "{{csrf_token()}}"
    //      },
    //      cache: false,
    //      contentType: false,
    //      processData: false,
    //      url: "{{ url('google/affiliate/results') }}/" + id,
    //      success: function(html) {
    //         location.reload();
    //      }
    //    });
    //  }

     swal({
        title: "Are you sure?",
        text: "Are you sure that you want to delete this record?",
        icon: "warning",
        dangerMode: true,
      })
      .then(willDelete => {
        if (willDelete) {
          $.ajax({
           type: "DELETE",
           headers: {
             "X-CSRF-TOKEN": "{{csrf_token()}}"
           },
           cache: false,
           contentType: false,
           processData: false,
           url: "{{ url('google/affiliate/results') }}/" + id,
           success: function(html) {
            swal("Deleted!", "Your record  has been deleted!", "success");
              location.reload();
           }
         })
        }
      });
   });
 </script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>