@extends('layouts.app')

@section('title', 'Backlink Checker')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Backlink Checker</h2>
        </div>
    </div>
    <form action="{{ route('filteredResults') }}" method="GET" class="form-inline align-items-start mb-5 float-right">
      <div class="form-group mr-3 mb-4">
        <select name="domain" class="form-control" placeholder="Domains">
          <option value="Domain" disabled selected></option>
          @foreach ($domains as $domain)
            <option value="{{$domain}}" {{!empty($_GET['domain']) ? $_GET['domain'] : ''}}>{{$domain}}</option>
          @endforeach
        </select>
        {{-- {!! Form::select('domain', $domains, !empty($_GET['domain']) ? $_GET['domain'] : '', ['placeholder'=> 'Domains']) !!} --}}
      </div>
      <div class="form-group mr-3 mb-4">
          <select name="rank" class="form-control" placeholder="Rank">
            <option value="Rank" disabled></option>
              @foreach ($rankings as $rank)
                <option value="{{$rank}}" {{!empty($_GET['rank']) ? $_GET['rank'] : ''}}>{{$rank}}</option>
              @endforeach
          </select>
        {{-- {!! Form::select('ranking', $rankings, !empty($_GET['ranking']) ? $_GET['ranking'] : '', ['placeholder'=> 'Rankings']) !!} --}}
      </div>
      <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
    </form>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th rowspan="2">Domain</th>
            <th rowspan="2" class="text-center">ID</th>
            <th rowspan="2" class="text-left">Link</th>
            <th rowspan="2" class="text-center">Link Type</th>
            <th rowspan="2" class="text-center">Review Numbers</th>
            <th rowspan="2" class="text-center">Rank</th>
            <th rowspan="2" class="text-center">Rating</th>
            <th rowspan="2" class="text-center">Serp ID</th>
            <th rowspan="2" class="text-center">Snippet</th>
            <th rowspan="2" class="text-center">Title</th>
            <th rowspan="2"  class="text-center">Visible Link</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($details as $key => $detail)
                <tr>
                    <td data-id="{{ $detail->id }}>
                      <span class="quick-domain"><a data-toggle="collapse" href="#collapse_domain-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->domains, 10, '...'))); @endphp</a></span>
                      <input name="domain_name" type="text" class="form-control quick-edit-domain-input hidden" placeholder="Domain Name" value="{{ $detail->domains }}"/>
                      <button type="button" class="btn-link quick-edit-domain" data-id="{{ $detail->id }}">Edit</button>
                      @if (strlen(strip_tags($detail->domains)) > 10)
                        <div>
                            <div class="panel-group">
                                <div id="collapse_domain-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$detail->domains}}     
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </div>
                      @endif
                    </td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->id, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->link, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->link_type, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->num_reviews, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->rank, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->rating, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->serp_id, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->snippet, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
                    <td data-id="{{ $detail->id }}>
                    <span class="quick-title"><a data-toggle="collapse" href="#collapse_title-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->title, 50, '...'))); @endphp</a></span>
                    <textarea name="title" class="form-control quick-edit-title-input hidden" placeholder="TItle" rows="8" cols="80">{{ $detail->title }}</textarea>
                    {{-- <input name="title" type="text" class="form-control quick-edit-title-input hidden" placeholder="TItle" value="{{ $detail->title }}"/> --}}
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
                    <td class="readmore">@php echo htmlspecialchars_decode(stripslashes(str_limit($detail->visible_link, 10, '<a href="javascript:void(0)">...</a>'))); @endphp</td>
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
  $(document).on('click', '.quick-edit-domain', function() {
      var id = $(this).data('id');

      $(this).closest('td').find('.quick-domain').addClass('hidden');
      $(this).closest('td').find('.quick-edit-domain-input').removeClass('hidden');
      $(this).closest('td').find('.quick-edit-domain-input').focus();

      $(this).closest('td').find('.quick-edit-domain-input').keypress(function(e) {
        var key = e.which;
        var thiss = $(this);

        if (key == 13) {
          e.preventDefault();
          var name = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('back-link') }}/" + id + '/updateDomain',
            data: {
              _token: "{{ csrf_token() }}",
              domain_name: name,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-domain').text(name);
            $(thiss).siblings('.quick-domain').removeClass('hidden');
            alert('Domain Name Updated');
          }).fail(function(response) {
            alert('Could not update domain name');
          });
        }
      });
    });
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
          var name = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('back-link') }}/" + id + '/updateTitle',
            data: {
              _token: "{{ csrf_token() }}",
              title: name,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-title').text(name);
            $(thiss).siblings('.quick-title').removeClass('hidden');
            alert('Title Updated');
          }).fail(function(response) {
            alert('Could not update name');
          });
        }
      });
    });
</script>
@endsection
