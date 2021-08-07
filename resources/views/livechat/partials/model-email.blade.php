<div id="emailModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send an Email</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ route('tickets.email.send') }}"  method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="ticket_id" value="">

        <div class="modal-body">
          <div class="form-group">
          <strong>To Mail</strong>
            <input   class="form-control input-sm select-multiple" id="to_email"  readonly />
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

          <div class="form-group">
            <a class="add-cc mr-3" href="#">Cc</a>
            <a class="add-bcc" href="#">Bcc</a>
          </div>

          
          <div id="cc-list" class="form-group">

          </div>

          

          <div id="bcc-list" class="form-group">

          </div>

          <div class="form-group">
            <strong>Subject</strong>
            <input type="text" id="subject" class="form-control" name="subject"  value="{{ old('subject') }}" required>
          </div>

          <div class="form-group">
            <strong>Message</strong>
            <textarea name="message" id="message" class="form-control" rows="8" cols="80" required>{{ old('message') }}</textarea>
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
{{-- <div id="subject1" class="modal fade in" role="dialog">
     <div class="modal-dialog">

          
          <div class="modal-content">
               <div class="modal-header">
                    <h4 class="modal-title">Subject </h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
               </div>
            <div id="content" style="padding:30px;"></div>
              
          </div>

     </div>
</div>
<div id="message1" class="modal fade in" role="dialog">
     <div class="modal-dialog">

        
          <div class="modal-content">
               <div class="modal-header">
                    <h4 class="modal-title">Message </h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
               </div>
            <div id="content" style="padding:30px;"></div>
              
          </div>

     </div>
</div> --}}

<div id="viewmore" class="modal fade in" role="dialog">
     <div class="modal-dialog">        
          <div class="modal-content">
               <div class="modal-header">
                    {{-- <h4 class="modal-title">soruce</h4> --}}
                    <button type="button" class="close" data-dismiss="modal">×</button>
               </div>
            <div id="contentview" style="padding:30px;"></div>
              
          </div>

     </div>
</div>
    