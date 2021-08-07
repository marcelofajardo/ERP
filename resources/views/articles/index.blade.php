@extends('layouts.app')

@section('title', 'Articles')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Article Approval</h2>
        </div>
    </div>
    <div class="text-center">
      {{$articles->links()}}
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th rowspan="2">Sr.No.</th>
            <th rowspan="2" class="text-center">Title</th>
            <th rowspan="2" class="text-left">Article</th>
            <th rowspan="2" class="text-center">No of words</th>
            <th rowspan="2" class="text-center">Status</th>
            <th rowspan="2" class="text-center">Post to</th>
            <th rowspan="2" class="text-center">Date Posted on</th>
            <th rowspan="2" class="text-center">Remark</th>
            <th rowspan="2" class="text-center">Assign to</th>
            <th rowspan="2" class="text-center">Date Edited</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($articles as $key => $article)
            <tr>
                <td class="readmore">{{$article->id}}</td>
                <td width="20%" data-id="{{ $article->id }}">
					<span class="quick-title"><a data-toggle="collapse" href="#collapse_domain-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($article->title, 10, '...'))); @endphp</a></span>
					<input name="article_title" type="text" class="form-control quick-edit-title-input hidden" placeholder="Title" value="{{ $article->title }}"/>
					<button type="button" class="btn-link quick-edit-title" data-id="{{ $article->id }}">Edit</button>
					@if (strlen(strip_tags($article->title)) > 10)
					<div>
						<div class="panel-group">
							<div id="collapse_domain-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
								<div class="messageList" id="message_list_310">
									{{$article->title}}     
								</div>
								</div>
							</div>
						</div>
						</div>
					@endif
                </td>
				<td data-id="{{ $article->id }}">
					<span class="quick-desc"><a data-toggle="collapse" href="#collapse_domain-{{$key}}" class="collapsed" aria-expanded="false">@php echo htmlspecialchars_decode(stripslashes(str_limit($article->description, 80, '...'))); @endphp</a></span>
					<textarea name="article_desc" class="form-control quick-edit-desc-input hidden" placeholder="Description" cols="30" rows="10">{{$article->description}}</textarea>
					{{-- <input name="article_title" type="text" class="form-control quick-edit-desc-input hidden" placeholder="Title" value="{{ $article->description }}"/> --}}
					<button type="button" class="btn-link quick-edit-desc" data-id="{{ $article->id }}">Edit</button>
					@if (strlen(strip_tags($article->description)) > 80)
					<div>
						<div class="panel-group">
							<div id="collapse_domain-{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
								<div class="messageList" id="message_list_310">
									{{$article->description}}     
								</div>
								</div>
							</div>
						</div>
						</div>
					@endif
				</td>
                <td class="readmore">{{str_word_count($article->description)}}</td>
                <td class="readmore">{{$article->status}}</td>
                <td class="readmore">{{$article->posted_to}}</td>
                <td class="readmore">{{\Carbon\Carbon::parse($article->created_at)->format('d M, Y')}}</td>
                <td class="readmore">{{$article->remark}}</td>
                <td class="readmore">{{$article->assign_to}}</td>
                <td class="readmore">{{\Carbon\Carbon::parse($article->updated_at)->format('d M, Y')}}</td>
            </tr>    
            @endforeach
        </tbody>
      </table>
      <div class="text-center">
        {{$articles->links()}}
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
          var name = thiss.val();

          $.ajax({
            type: 'POST',
            url: "{{ url('article') }}/" + id + '/updateTitle',
            data: {
              _token: "{{ csrf_token() }}",
              article_title: name,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-title').text(name);
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
            url: "{{ url('article') }}/" + id + '/updateDescription',
            data: {
              _token: "{{ csrf_token() }}",
              article_desc: name,
            }
          }).done(function() {
            $(thiss).addClass('hidden');
            $(thiss).siblings('.quick-desc').text(name);
            $(thiss).siblings('.quick-desc').removeClass('hidden');
            alert('Description Updated');
          }).fail(function(response) {
            alert('Could not update description');
          });
        }
      });
    });
</script>
@endsection

