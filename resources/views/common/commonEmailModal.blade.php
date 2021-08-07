<div id="commonEmailModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Email</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('common.send.email') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id">
                <input type="hidden" name="object">
                <input type="hidden" name="action" class="action" value="{{route('common.getmailtemplate')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Send To</strong>
                        <input type="text" name="sendto" class="form-control">
                    </div>

                    <div class="form-group">
                        <strong>From Mail</strong>
                        <select class="form-control" name="from_mail">
                          <?php $emailAddressArr = \App\EmailAddress::all(); ?>
                          @foreach ($emailAddressArr as $emailAddress)
                            <option value="{{ $emailAddress->id }}">{{ $emailAddress->from_name }} - {{ $emailAddress->from_address }} </option>
                          @endforeach
                        </select>
                    </div>

                    <div class="form-group text-right">
                        <a class="add-cc mr-3" href="#">Cc</a>
                        <a class="add-bcc" href="#">Bcc</a>
                    </div>

                    <div id="cc-label" class="form-group" style="display:none;">
                        <strong class="mr-3">Cc</strong>
                        <a href="#" class="add-cc">+</a>
                    </div>

                    <div id="cc-list" class="form-group">

                    </div>

                    <div id="bcc-label" class="form-group" style="display:none;">
                        <strong class="mr-3">Bcc</strong>
                        <a href="#" class="add-bcc">+</a>
                    </div>

                    <div id="bcc-list" class="form-group">

                    </div>
                    <div class="form-group">
                        <strong>Mail Templates</strong>
                        <select class="form-control getTemplateData" name="mail_template" required>
                          <?php $mail_templates = \App\MailinglistTemplate::whereNotNull('static_template')->get(); ?>
                           <option value="">Select a template</option>
                          @foreach ($mail_templates as $mail_template)
                            <option value="{{ $mail_template->id }}">{{ $mail_template->name }}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Subject *</strong>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
                    </div>

                    <div class="form-group">
                        <strong>Message *</strong>
                        <textarea name="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
                    </div>

                    <div class="form-group">
                        <strong>Files</strong>
                        <input type="file" name="file[]" value="" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Send</button>
                </div>
            </form>
        </div>

    </div>
</div>
