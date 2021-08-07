<div class="table-responsive mt-2">
      <table class="table table-bordered order-table" style="border: 1px solid #ddd !important; color:black;">
        <thead>
        <tr>
            <th width="5%">Name</th>
            <th width="5%">Email</th>
            <th width="5%">Phone</th>
            <th width="5%">Whatsapp</th>
            <th width="5%">Gender</th>
            <th width="5%">Address</th>
            <th width="5%">Country</th>
         </tr>
        </thead>

        <tbody>
            <tr>
            <td>{{$data->name}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->whatsapp_number}}</td>
            <td>{{$data->gender}}</td>
            <td>{{$data->address}}</td>
            <td>{{$data->country}}</td>
            </tr>
        </tbody>
      </table>
	</div>