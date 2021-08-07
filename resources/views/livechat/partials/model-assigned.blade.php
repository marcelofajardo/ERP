<div id="assignedModal" class="modal fade" role="dialog">
     <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
               <div class="modal-header">
                    <h4 class="modal-title">Assign Ticket </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>

               <form action="{{ route('tickets.assign') }}"  method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="">

                    <div class="modal-body">
                         <div class="form-group">
                         <strong>From Mail</strong>
                         <select class="form-control" name="users_id" id="users_id">
                              <option value="">Select Users</option>
                              @foreach($users as $key => $user)
                              <option value="{{ $user->id }}">{{ $user->name }}</option>
                              @endforeach
                         </select>
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
