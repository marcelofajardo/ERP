@foreach($posts as $key=>$post)
<tr>
  <td><input type="checkbox" class="searchDelete" id="{{$post->id}}" /></td>
  <td>{{ date('d-M-Y H:i:s', strtotime($post->posted_at)) }}</td>
  <td>{{ $post->hashTags->hashtag }}</td>
  <td><a style="word-break:break-all; white-space: normal;" href="{{ $post->location }}" target="_blank">{{ $post->location }}</a></td>
  <td>{{ substr($post->caption,0,50) }}..<button type="button" data-caption="{{$post->caption}}" class="btn btn-xs btn-image load-comment-trick" title="Load messages">
    <img src="/images/chat.png" alt="" style="cursor: nwse-resize; width: 0px;"></button>
  </td>
</tr>
@endforeach

<div class="modal fade" id="load-comment-trick" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Caption</h3>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

<script>

  $(document).on("click",".load-comment-trick",function() {
      var loadTrick = $("#load-comment-trick");
      loadTrick.find(".modal-body").html("<p>"+$(this).data('caption')+"</p>");
      loadTrick.modal("show");
  });

  $(document).on('click', '.searchDelete', function() {
    var id = $(this).attr('id');


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
           url: "{{ url('google/search/results') }}/" + id,
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