@extends('layouts.app')

@section('title', 'Backlinking Data')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Backlinking Data</h2>
        </div>
    </div>
    <form action="{{ route('backLinkFilteredResults') }}" method="GET" class="form-inline align-items-start mb-5 float-right">
      <div class="form-group mr-3 mb-4">
        <select name="title" class="form-control" placeholder="Titles">
          @foreach ($titles as $title)
            <option value="{{$title}}" {{!empty($_GET['title']) ? $_GET['title'] : ''}}>{{$title}}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
    </form>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th rowspan="2">Sr. No</th>
            <th rowspan="2" class="text-center">Title</th>
            <th rowspan="2" class="text-left">Description</th>
            <th rowspan="2" class="text-center">URL</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($details as $key => $detail)
                <tr>
                <td>{{$detail->id}}</td>
                    <td data-id="{{ $detail->id }}><span class="quick-title"><a data-toggle="collapse" href="#collapse_title-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->title, 50, '...'))); @endphp</a></span>
                      <input name="title" type="text" class="form-control quick-edit-title-input hidden" placeholder="Title" value="{{ $detail->title }}"/>
                      <button type="button" class="btn-link quick-edit-title" data-id="{{ $detail->id }}">Edit</button>
                      @if (strlen(strip_tags($detail->title)) > 50)
                        <div>
                            <div class="panel-group">
                                <div id="collapse_title-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$detail->title}}     
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                      @endif
                    </td>
                    <td data-id="{{ $detail->id }}><span class="quick-desc"><a data-toggle="collapse" href="#collapse_desc-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->description, 50, '...'))); @endphp</a></span>
                      <textarea name="desc" class="form-control quick-edit-desc-input hidden" placeholder="Description" rows="8" cols="80">{{ $detail->description }}</textarea>
                      <button type="button" class="btn-link quick-edit-desc" data-id="{{ $detail->id }}">Edit</button>
                      @if (strlen(strip_tags($detail->description)) > 50)
                        <div>
                            <div class="panel-group">
                                <div id="collapse_desc-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$detail->description}}     
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                      @endif
                    </td>
                    <td data-id="{{ $detail->id }}><span class="quick-url"><a data-toggle="collapse" href="#collapse_url-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->url, 50, '...'))); @endphp</a></span>
                      <input name="url" type="text" class="form-control quick-edit-url-input hidden" placeholder="URL" value="{{ $detail->url }}"/>
                      <button type="button" class="btn-link quick-edit-url" data-id="{{ $detail->id }}">Edit</button>
                      @if (strlen(strip_tags($detail->url)) > 50)
                        <div>
                            <div class="panel-group">
                                <div id="collapse_url-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$detail->url}}     
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                      @endif
                    </td>
                </tr>    
            @endforeach
        </tbody>
      </table>
      <div class="text-center">
          <div class="text-center">
              {!! $details->links() !!}
          </div>
      </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).on('click', '.quick-edit-title', function() {
      var id = $(this).data('id');

      $(this).closest('td').find('.quick-title').addClass('hidden');
      $(this).closest('td').find('.quick-edit-title-input').removeClass('hidden');
      $(this).closest('td').find('.quick-edit-title-input').focus();

      $(this).closest('td').find('.quick-edit-title-input').keypress(function(e) {
        var key = e.which;
        var thiss = $(this);

        if (key == 13) {
          e.preventDefault();
          var title = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('back-linking') }}/" + id + '/updateTitle',
            data: {
              _token: "{{ csrf_token() }}",
              title: title,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-title').text(title);
            $(thiss).siblings('.quick-title').removeClass('hidden');
            alert('Title Updated');
          }).fail(function(response) {
            alert('Could not update title');
          });
        }
      });
    });
  $(document).on('click', '.quick-edit-desc', function() {
      var id = $(this).data('id');

      $(this).closest('td').find('.quick-desc').addClass('hidden');
      $(this).closest('td').find('.quick-edit-desc-input').removeClass('hidden');
      $(this).closest('td').find('.quick-edit-desc-input').focus();

      $(this).closest('td').find('.quick-edit-desc-input').keypress(function(e) {
        var key = e.which;
        var thiss = $(this);

        if (key == 13) {
          e.preventDefault();
          var name = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('back-linking') }}/" + id + '/updateDesc',
            data: {
              _token: "{{ csrf_token() }}",
              desc: name,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-desc').text(name);
            $(thiss).siblings('.quick-desc').removeClass('hidden');
            alert('Description Updated');
          }).fail(function(response) {
            alert('Could not update Description');
          });
        }
      });
    });
  $(document).on('click', '.quick-edit-url', function() {
      var id = $(this).data('id');

      $(this).closest('td').find('.quick-url').addClass('hidden');
      $(this).closest('td').find('.quick-edit-url-input').removeClass('hidden');
      $(this).closest('td').find('.quick-edit-url-input').focus();

      $(this).closest('td').find('.quick-edit-url-input').keypress(function(e) {
        var key = e.which;
        var thiss = $(this);

        if (key == 13) {
          e.preventDefault();
          var url = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('back-linking') }}/" + id + '/updateURL',
            data: {
              _token: "{{ csrf_token() }}",
              url: url,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-url').text(url);
            $(thiss).siblings('.quick-url').removeClass('hidden');
            alert('URL Updated');
          }).fail(function(response) {
            alert('Could not update URL');
          });
        }
      });
    });
</script>
@endsection
