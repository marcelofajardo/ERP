@extends('layouts.app')

@section('link-css')
<style type="text/css">
  .form-group {
    padding: 10px;
  }
</style>
@endsection

@section('content')

@if(Session::has('message'))
<div class="alert alert-success alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>{{ Session::get('message') }}</strong>
</div>
@endif

<h2 class="text-center">Users List from Hubstaff </h2>

<div class="container">
  @if(!empty($members))
  <div class="row">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>HubStaff Id</th>
          <th>Hubstaff Email</th>
          <th>Minimum Activity</th>
          <th>User</th>

        </tr>
      </thead>
      @foreach($members as $member)
      <tbody>
        <tr>
          <td>{{ $member->hubstaff_user_id }}</td>
          <td>{{ $member->email }}</td>
          <td>
            <div class="form-group">
              <input type="text" data-member-id="{{ $member->id }}" class="form-control change-activity-percentage" name="min_activity_percentage" value="{{ $member->min_activity_percentage }}">
            </div>
          </td>
          <td>
            <select onchange="saveUser(this)">
              <option value="unassigned">Unassigned</option>
              @foreach($users as $user)
              <option value="{{$user->id}}|{{ $member->hubstaff_user_id }}" <?= ($member->user_id == $user->id) ? 'selected' : '' ?>>{{$user->name}}</option>
              @endforeach
            </select>
          </td>

        </tr>
      </tbody>
      @endforeach
    </table>
    <br>
    <hr>
  </div>
  @else
  <div style="text-align: center;color: red;font-size: 14px;">
    {{$members['error_description']}}
  </div>
  @endif
</div>
@endsection
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif')
  50% 50% no-repeat;display:none;">
</div>
@section("scripts")
<script type="text/javascript">
  function saveUser(a) {
    var selectedValue = (a.value || a.options[a.selectedIndex].value); //crossbrowser solution =)
    console.log('selectedValue', selectedValue);
    if (selectedValue != 'unassigned') {
      var splitValues = selectedValue.split('|');
      var userId = splitValues[0];
      var hubstaffUserId = splitValues[1];

      var xhr = new XMLHttpRequest();
      var url = "linkuser";
      xhr.open("POST", url, true);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var json = JSON.parse(xhr.responseText);
          console.log(json.email + ", " + json.password);
        }
      };
      var data = JSON.stringify({
        "user_id": userId,
        "hubstaff_user_id": hubstaffUserId
      });
      xhr.send(data);
    }
  }
  $(document).on("focusout",".change-activity-percentage",function(e){
     e.preventDefault();
     var $this = $(this);
     var memberId = $this.data("member-id");
     $.ajax({
        type: 'POST',
        url: "/hubstaff/members/"+memberId+"/save-field",
        data: {
          _token: "{{ csrf_token() }}",
          field_name : "min_activity_percentage",
          field_value : $this.val()
        },
        dataType:"json",
        beforeSend : function(response) {
          $(".loading-image").show();
          
        }
      }).done(function(response) {
        $(".loading-image").hide();
        if(response.code == 200) {
          toastr["success"](response.message);
        }else{
          toastr["error"](response.message);
        } 
      }).fail(function(response) {
          console.log(response);
      });
  });

</script>
@endsection