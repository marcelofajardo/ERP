@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 style="text-align: center; padding-bottom: 20px">Direct Message</h1>
            
      </div>
        <div class="col-md-12">
             <div class="table-responsive">
                <table class="table-striped table-bordered table">
                    <tr>
                        <th>S.N</th>
                        <th>Name</th>
                        <th style="width: 30%">Basic Info</th>
                        <th>Gender</th>
                        <th>Direct Message</th>
                    </tr>
                    @foreach($leads as $key=>$lead)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $lead['name'] }}</td>
                            <td><p>Username : {{ $lead['username'] }} <br> Profile : <a href="https://instagram.com/{{ $lead['username'] }}" target="_blank">Click</a> <br> Bio : {{ $lead['bio'] }}</p></td>
                            <td>@if($lead['gender'] == 'm') Male @else Female @endif</td>
                            <td><div class="card" style="min-width: 600px; width: 100%;">
   <div class="card-header">
      <strong class="pull-left">Chat</strong> 
      <div class="pull-right">
         <div class="form-group form-group-sm"><strong>Admin Acccount</strong></div>
      </div>
   </div>
   <div class="card-body">
      <!----> 
      <div class="chats text-center mb-4">
         <div class="row">
            <div class="col-md-12">
               <!----> 
               <div class="messages-list" style="height: 200px; max-height: 250px; overflow: auto; padding: 10px 0px;" id="messages-list{{ $lead['id'] }}">
                  @php 
                     $conversations = \App\InstagramDirectMessages::where('receiver_id',$lead['platform_id'])->get();
                  @endphp  
                  @if(!empty($conversations))
                  @foreach($conversations as $conversation)
                  <div>
                     <div class=" is-received p-2 m-0 position-relative balon1">
                        <a class="float-right">
                           {{ $conversation->message }}
                        </a>
                     </div>
                     <br clear="all">
                  </div>
                  @endforeach
                  
                 @endif 
               </div>
            </div>
         </div>
      </div>
      <div class="messagebox">
         <div class="row">
            <div class="col-md-12">
               <div class="form-group"><input placeholder="Type Message here..." type="text" name="message" class="form-control" id="message{{ $lead['id'] }}"></div>
            </div>
            <div class="col-md-12 text-right">
               <!-- <button class="btn btn-sm btn-primary" onclick="sendImage({{ $lead['id'] }} , )"><i class="fa fa-image"></i> Send Image
               </button> -->
                <button class="btn btn-sm btn-success" onclick="sendMessage({{ $lead['id'] }} , )"><i class="fa fa-send"></i> Send Message
               </button>
            </div>
         </div>
      </div>
   </div>
</div></td>
                        </tr>
                    @endforeach
                </table>
                {{ $leads->render() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Broadcast</h4>
        </div>
        <div class="modal-body">
          <form action="/instagram/create-broadcast" method="POST">
            @csrf
            <label>Enter Text or Link</label>
            <br>
            <input type="text" name="text" >
            <br>
            <label>Type</label>
            <br>
            <select name="type">
              <option>Select Type</option>
              <option value="1">Post Image</option>
              <option value="2">Post Video</option>
              <option value="3">Text</option>
            </select>
            <br>
            <br>
            <button type="submit">Submit</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

@endsection



@section('scripts')
    
<script type="text/javascript">
    
    function sendMessage(id) {
        var message = $('#message'+id).val();
        link = '/instagram/thread/'+id;
        $.ajax({
            url: link,
            type: 'POST',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                message: message,
            },
        })
        .done(function() {
            $("#messages-list"+id).append('<div><div class=" is-received p-2 m-0 position-relative balon1"><a class="float-right">'+message+'</a></div><br clear="all"></div>');
            $('#message'+id).val('');
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }


    


</script>
   
@endsection