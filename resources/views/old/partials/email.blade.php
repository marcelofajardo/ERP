<div id="emailAccordion">
    @if (count($emails) > 0)
        @foreach ($emails as $key => $email)
            <div class="card mb-2">
                <div class="card-header" id="headingEmail{{ $key }}">
                    <h5 class="mb-0">
                        <p class="btn-link collapsed collapse-fix email-fetch" data-toggle="collapse" data-target="#emailAcc{{ $key }}" data-uid="{{ $email['uid'] ?? 'no' }}" data-id="{{ $email['id'] ?? 'null' }}" data-type="{{ $type }}" aria-expanded="false" aria-controls="">
                            @if ($email['seen'] == 0)
                                <u>
                                    <strong>{{ $email['subject'] }}</strong>

                                    @if ($email['type'] == 'outgoing')
                                        {{ $email['to'] }}
                                    @else
                                        {{ $email['from'] }}
                                    @endif

                                    <br>
                                    {{ $email['date'] }}

                                    @if ($email['cc'])
                                        <div style="color: #767676">
                                            CC: {{ join(', ', $email['cc']) }}
                                        </div>
                                    @endif
                                    @if ($email['bcc'])
                                        <div style="color: #767676">
                                            BCC: {{ join(', ', $email['bcc']) }}
                                        </div>
                                    @endif
                                </u>
                            @else
                                <strong>{{ $email['subject'] }}</strong>

                                @if ($email['type'] == 'outgoing')
                                    {{ $email['to'] }}
                                @else
                                    {{ $email['from'] }}
                                @endif

                                <br>
                        {{ $email['date'] }}

                        @if ($email['cc'])
                            <div style="color: #767676">
                                CC: {{ join(', ', $email['cc']) }}
                            </div>
                        @endif
                        @if ($email['bcc'])
                            <div style="color: #767676">
                                BCC: {{ join(', ', $email['bcc']) }}
                            </div>
                            @endif
                            @endif
                            </p>
                    </h5>
                </div>

                <div id="emailAcc{{ $key }}" class="collapse collapse-element" aria-labelledby="headingInstruction" data-parent="#instructionAccordion">
                    <div class="email-content">
                        <div class="card p-3">

                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a class="email-reply-link" href="#">Reply</a>
                    <a class="cancel-email-reply-link" href="#" style="display: none;">Cancel Reply</a>
                    <a class="email-forward-link ml-3" href="#">Forward</a>
                    <a class="cancel-email-forward-link ml-3" href="#" style="display: none;">Cancel Forward</a>

                    <div class="alert alert-danger reply-error-messages mt-2" style="display: none;"></div>
                    <div class="alert alert-success reply-success-messages mt-2" style="display: none;"></div>
                    <div class="alert alert-danger forward-error-messages mt-2" style="display: none;"></div>
                    <div class="alert alert-success forward-success-messages mt-2" style="display: none;"></div>

                    <form action="{{ route('purchase.email.reply') }}" class="email-reply-form mt-2" style="display: none;">
                        @csrf
                        <input type="hidden" name="reply_email_id" value="{{ $email['id'] }}">

                        <div class="form-group">
                            <textarea name="message" class="form-control reply-message-textarea" rows="3"></textarea>

                            <div class="message-to-reply">
                                {{ $email['replyInfo'] }}
                                <blockquote style="margin:0px 0px 0px 0.8ex;border-left:1px solid rgb(204,204,204);padding-left:1ex">
                                    {!! $email['message'] !!}
                                </blockquote>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-sm btn-secondary email-reply-form-submit-button">Reply</button>
                        </div>
                    </form>

                    <form action="{{ route('purchase.email.forward') }}" class="email-forward-form mt-2" style="display: none;">
                        @csrf
                        <input type="hidden" name="forward_email_id" value="{{ $email['id'] }}">

                        <div class="form-group" id="forward-to-emails-list">
                            <strong>To *</strong>

                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <input type="text" name="to[]" class="form-control">
                                </div>
                                <div class="col-md-2 text-center">
                                    <a href="#" class="add-forward-to btn btn-secondary">+</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea name="message" class="form-control forward-message-textarea" rows="3"></textarea>

                            <div class="message-to-forward">
                                <div>---------- Forwarded message ---------</div>
                                <div>From: &lt;{{ $email['from'] }}&gt;</div>
                                <div>Date: {{ $email['dateCreated'] }} at {{ $email['timeCreated'] }}</div>
                                <div>Subject: {{ $email['subject'] }}</div>
                                <div>To: {{ $email['to'] }}</div>
                                <br>
                                {!! $email['message'] !!}
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-sm btn-secondary email-forward-form-submit-button">Forward</button>
                        </div>
                    </form>

                </div>
            </div>
        @endforeach

        {!! $emails->appends(Request::except('page'))->links() !!}
    @else
        No emails for this customer
    @endif

</div>

{{-- <div class="row">
  <div class="col-md-4">
    <div class="card">
      @if (count($emails) > 0)
        <ul class="list-group list-group-flush">
          @foreach ($emails as $email)
            <li class="list-group-item">
              <a href="#" class="email-fetch" data-uid="{{ $email['uid'] ?? 'no' }}" data-id="{{ $email['id'] ?? 'null' }}" data-type="{{ $type }}">
                <strong>{{ $email['subject'] }}</strong>
              </a>
              <br>
              {{ $email['from'] }}
              {{ $email['date'] }}
            </li>
          @endforeach
        </ul>
      @else
        No emails for this user
      @endif
    </div>

    @if (count($emails) > 0)
      {!! $emails->appends(Request::except('page'))->links() !!}
    @endif
  </div>

  <div class="col-md-8" id="email-content">
    <div class="mb-3">
      <button type="button" class="btn btn-xs btn-secondary resend-email-button" data-toggle="modal" data-target="#chooseRecipientModal" data-id="" data-emailtype="" data-type="">Resend</button>

      @if (isset($to_email))
        {{ $to_email }}
      @endif
    </div>

    <div class="card p-3">

    </div>
  </div>
</div> --}}

<style type="text/css">
    .message-to-reply blockquote,
    .message-to-forward blockquote {
        font-size: 14px;
    }
    .reply-message-textarea,
    .forward-message-textarea {
        border-bottom: none;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .reply-message-textarea:focus,
    .forward-message-textarea:focus {
        border-color: #ccc;
        box-shadow: unset;
    }
    .message-to-reply,
    .message-to-forward {
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: 0;
        padding: 6px 12px;
        border-bottom-right-radius: 4px;
        border-bottom-left-radius: 4px;
    }
</style>