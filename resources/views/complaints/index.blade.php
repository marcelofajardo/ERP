@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
@endsection

@section('large_content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Customer Complaints</h2>
            <div class="pull-left">
              <form action="{{ route('complaint.index') }}" method="GET">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <select class="form-control" name="platform">
                        <option value="">Select Platform</option>
                        <option value="instagram" {{ 'instagram' == $filter_platform ? 'selected' : '' }}>Instagram</option>
                        <option value="facebook" {{ 'facebook' == $filter_platform ? 'selected' : '' }}>Facebook</option>
                        <option value="sitejabber" {{ 'sitejabber' == $filter_platform ? 'selected' : '' }}>Sitejabber</option>
                        <option value="google" {{ 'google' == $filter_platform ? 'selected' : '' }}>Google</option>
                        <option value="trustpilot" {{ 'trustpilot' == $filter_platform ? 'selected' : '' }}>Trustpilot</option>
                      </select>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group ml-3">
                      <div class='input-group date' id='filter_posted_date'>
                        <input type='text' class="form-control" name="posted_date" value="{{ $filter_posted_date }}" />

                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                  </div>
                </div>
              </form>

            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#complaintCreateModal">Create Complaint</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Platform</th>
            <th>Where</th>
            <th>Username</th>
            <th>Conversation thread</th>
            <th>Internal</th>
            <th>Status</th>
            <th>Plan of Action</th>
            <th>Link</th>
            <th>Notes & Instructions</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($complaints as $complaint)
            <tr>
              <td>{{ \Carbon\Carbon::parse($complaint->date)->format('Y-m-d') }}</td>
              <td>
                @if ($complaint->customer)
                  <a href="{{ route('customer.show', $complaint->customer->id) }}" target="_blank">{{ $complaint->customer->name }}</a>
                @endif
              </td>
              <td>{{ ucwords($complaint->platform) }}</td>
              <td>{{ ucwords($complaint->where ?? 'N/A') }}</td>
              <td>
                {{ ucwords($complaint->name) }}
                <span class="text-muted">{{ ucwords($complaint->username) }}</span>
              </td>
              <td class="expand-row">
                  <span class="td-mini-container">
                    {{ strlen($complaint->complaint) > 7 ? substr($complaint->complaint, 0, 20). '...' : $complaint->complaint }}
                  </span>

                  <span class="td-full-container hidden">
                    {{ $complaint->complaint }}
                  </span>

                @if ($complaint->threads)
                  <ul class="mx-0 px-4">
                    @foreach ($complaint->threads as $key => $thread)
                      <li class="ml-{{ $key + 1 }}">
                        {{ $thread->thread }} ({{ $thread->account->email ?? '' }})
                      </li>
                    @endforeach
                  </ul>
                @endif

                @if ($complaint->hasMedia(config('constants.media_tags')))
                  <ul>
                    @foreach ($complaint->getMedia(config('constants.media_tags')) as $image)
                      <li><a href="{{ $image->getUrl() }}" target="_blank"><img src="{{ $image->getUrl() }}" class="img-responsive" /></a></li>
                    @endforeach
                  </ul>
                @endif
              </td>
              <td>
                <input type="text" name="message" class="form-control quick-message-input" data-type="internal" placeholder="Internal message" value="" data-id="{{ $complaint->id }}">

                <ul class="internal-container">
                  @foreach ($complaint->internal_messages as $message)
                    <li>{{ $message->remark }}</li>
                  @endforeach
                </ul>
              </td>
              <td>
                @if (count($complaint->status_changes) > 0)
                  <button type="button" class="btn btn-xs btn-secondary change-history-toggle">?</button>

                  <div class="change-history-container hidden">
                    <ul>
                      @foreach ($complaint->status_changes as $status_history)
                        <li>
                          {{ array_key_exists($status_history->user_id, $users_array) ? $users_array[$status_history->user_id] : 'Unknown User' }} - <strong>from</strong>: {{ $status_history->from_status }} <strong>to</strong> - {{ $status_history->to_status }} <strong>on</strong> {{ \Carbon\Carbon::parse($status_history->created_at)->format('H:i d-m') }}
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <div class="form-group">
                  <select class="form-control update-complaint-status" name="status" data-id="{{ $complaint->id }}">
                    <option value="pending" {{ 'pending' == $complaint->status ? 'selected' : '' }}>Pending</option>
                    <option value="planned" {{ 'planned' == $complaint->status ? 'selected' : '' }}>Planned</option>
                    <option value="replied" {{ 'replied' == $complaint->status ? 'selected' : '' }}>Replied</option>
                    <option value="followed up" {{ 'followed up' == $complaint->status ? 'selected' : '' }}>Followed Up</option>
                    <option value="deleted" {{ 'deleted' == $complaint->status ? 'selected' : '' }}>Deleted</option>
                  </select>

                  <span class="text-success change_status_message" style="display: none;">Successfully changed schedule status</span>
                </div>
              </td>
              <td>
                {{ $complaint->plan_of_action }}

                <input type="text" name="message" class="form-control quick-message-input" data-type="plan" placeholder="Comment" value="" data-id="{{ $complaint->id }}">

                <ul class="plan-comments-container">
                  @foreach ($complaint->plan_messages as $message)
                    <li>{{ $message->remark }}</li>
                  @endforeach
                </ul>
              </td>
              <td>
                <a href="{{ $complaint->link }}" target="_blank">Visit Link</a>
              </td>
              <td>
                  <div class="panel panel-default">
                      <div class="panel-heading">
                          <h4 class="panel-title">
                              <a data-toggle="collapse" href="#collapse_{{$complaint->id}}">Remarks ({{ count($complaint->remarks) }})</a>
                          </h4>
                      </div>
                      <div id="collapse_{{$complaint->id}}" class="panel-collapse collapse">
                          <div class="panel-body">
                              <input type="text" class="form-control save-remark" id="enter_remark_{{$complaint->id}}" data-id="{{$complaint->id}}" placeholder="Type here...">
                          </div>
                          <div class="panel-footer" id="remarks_holder_{{$complaint->id}}">
                              @foreach($complaint->remarks as $rem)
                                  <li>{{ $rem->remark }}</li>
                              @endforeach
                          </div>
                      </div>
                  </div>
              </td>
              <td>
                <button type="button" class="btn btn-image edit-complaint" data-toggle="modal" data-target="#complaintEditModal" data-complaint="{{ $complaint }}" data-threads="{{ $complaint->threads }}"><img src="/images/edit.png" /></button>

                {!! Form::open(['method' => 'DELETE','route' => ['complaint.destroy', $complaint->id],'style'=>'display:inline']) !!}
                  <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $complaints->appends(Request::except('complaints-page'))->links() !!}

    @include('complaints.partials.complaint-modals')
{{--    @include('complaints.partials.remark-modals')--}}

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#birthday-datetime, #account_birthday, #review_date, #edit_review_date, #edit_posted_date, #filter_posted_date, #complaint_date').datetimepicker({
        format: 'YYYY-MM-DD'
      });
    });

    var accounts_array = {!! json_encode($accounts_array) !!};

    $('#add-complaint-button').on('click', function() {
      var account_html = '<div class="form-group"><strong>Account:</strong><select class="form-control" name="account_id[]"><option value="">Select an Account</option>';

      Object.keys(accounts_array).forEach(function(index) {
        account_html += '<option value="' + accounts_array[index].id + '">' + accounts_array[index].first_name + ' ' + accounts_array[index].last_name + ' - ' + accounts_array[index].email + '</option>';
      });

      account_html += '</select></div>';

      var complaint_html = '<div class="thread-container"><div class="form-group"><strong>Thread:</strong><input type="text" name="thread[]" class="form-control" value=""></div>' + account_html + '<button type="button" class="btn btn-image btn-secondary remove-review-button remove-special"><img src="/images/delete.png" /></button></div>';

      $('#complaint-container').append(complaint_html);
    });

    $('#add-edit-complaint-button').on('click', function() {
      var complaint_html = '<div class="form-group"><strong>Thread:</strong><input type="text" name="thread[]" class="form-control" value=""><button type="button" class="btn btn-image btn-secondary remove-review-button"><img src="/images/delete.png" /></button></div>';

      $('#complaint-container-extra').append(complaint_html);
    });

    $(document).on('click', '.remove-review-button', function() {
      if ($(this).hasClass('remove-special')) {
        $(this).closest('.thread-container').remove();
      } else {
        $(this).closest('.form-group').remove();
      }
    });

    $(document).on('click', '.edit-complaint', function() {
      fillEditComplaint(this);
    });

    $(document).on('change', '.update-complaint-status', function() {
      var status = $(this).val();
      var id = $(this).data('id');
      var thiss = $(this);

      $.ajax({
        type: "POST",
        url: "{{ url('complaint') }}/" + id + '/status',
        data: {
          _token: "{{ csrf_token() }}",
          status: status
        }
      }).done(function() {
        $(thiss).siblings('.change_status_message').fadeIn(400);

        setTimeout(function () {
          $(thiss).siblings('.change_status_message').fadeOut(400);
        }, 2000);
      }).fail(function(response) {
        alert('Could not change the status');
        console.log(response);
      });
    });

    $(document).on('click', '.expand-row', function() {
        let selection = window.getSelection();
        if (selection.toString().length === 0) {
            $(this).find('.td-mini-container').toggleClass('hidden');
            $(this).find('.td-full-container').toggleClass('hidden');
        }
    });

    function fillEditComplaint(thiss) {
      var complaint = $(thiss).data('complaint');
      var threads = $(thiss).data('threads');
      var url = "{{ url('complaint') }}/" + complaint.id;

      $('#complaintEditModal form').attr('action', url);
      $('#complaint_customer_id option[value="' + complaint.customer_id + '"]').prop('selected', true);
      $('#edit_complaint_date input').val(complaint.date);
      $('#complaint_platform option[value="' + complaint.platform + '"]').prop('selected', true);
      $('#complaint_complaint').val(complaint.complaint);
      $('#edit_plan_of_action').val(complaint.plan_of_action);
      $('#complaint_link').val(complaint.link);
      $('#complaint_where').val(complaint.where);
      $('#complaint_username').val(complaint.username);
      $('#complaint_name').val(complaint.name);

      $('#complaint-container-extra').empty();
      Object.keys(threads).forEach(function(index) {
        var account_html = '<div class="form-group"><strong>Account:</strong><select class="form-control" name="account_id[]"><option value="">Select an Account</option>';

        Object.keys(accounts_array).forEach(function(key) {
          var selected = threads[index].account_id == accounts_array[key].id ? "selected" : "";
          account_html += '<option value="' + accounts_array[key].id + '" ' + selected + '>' + accounts_array[key].first_name + ' ' + accounts_array[key].last_name + ' - ' + accounts_array[key].email + '</option>';
        });

        account_html += '</select></div>';

        var complaint_html = '<div class="thread-container"><div class="form-group"><strong>Thread:</strong><input type="text" name="thread[]" class="form-control" value="' + threads[index].thread + '"></div>' + account_html + '<button type="button" class="btn btn-image btn-secondary remove-review-button remove-special"><img src="/images/delete.png" /></button></div>';

        $('#complaint-container-extra').append(complaint_html);
      });
    }

    $('.add-task').on('click', function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      $('#add-remark input[name="id"]').val(id);
    });

    $('.save-remark').on('keyup', function(event) {
        if (event.which !== 13) {
            return;
        }
        let id = $(this).attr('data-id');
        let remark = $(this).val();
        let self = this;

      $.ajax({
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('task.addRemark') }}',
          data: {
            id:id,
            remark:remark,
            module_type: 'complaint'
          }, beforeSend: function() {
              $(self).attr('disabled', true);
          }
      }).done(response => {
          toastr['success']('Remark Added Success!', 'success');
          $(self).removeAttr('disabled');
          $('#remarks_holder_'+id).prepend(`<li>`+remark+`</li>`);
          $(self).val('');
      }).fail(function(response) {
        console.log(response);
        $(self).removeAttr('disabled');
      });
    });


    $(".view-remark").click(function () {
      var id = $(this).attr('data-id');

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.gettaskremark') }}',
            data: {
              id:id,
              module_type: "complaint"
            },
        }).done(response => {
            var html='';

            $.each(response, function( index, value ) {
              html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
              html+"<hr>";
            });
            $("#viewRemarkModal").find('#remark-list').html(html);
        });
    });

    $('.quick-message-input').keypress(function(e) {
      var key = e.which;
      var thiss = $(this);
      var type = $(this).data('type');

      if (type == 'internal') {
        var module_type = 'internal-complaint';
        var container = '.internal-container';
      } else {
        var module_type = 'complaint-plan-comment';
        var container = '.plan-comments-container';
      }

      if (key == 13) {
        e.preventDefault();
        var phone = $(thiss).val();

        var id = $(thiss).data('id');
        var remark = $(thiss).val();

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.addRemark') }}',
            data: {
              id:id,
              remark:remark,
              module_type: module_type
            },
        }).done(response => {
            // alert('Remark Added Success!')
            // window.location.reload();
            var remark_message = $('<li>' + remark + '</li>');
            $(thiss).siblings(container).prepend(remark_message);
            $(thiss).val('');
        }).fail(function(response) {
          console.log(response);
        });
      }
    });

    $(document).on('click', '.change-history-toggle', function() {
      $(this).siblings('.change-history-container').toggleClass('hidden');
    });
  </script>
@endsection
