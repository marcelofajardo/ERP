<html>
<head>
  <style>
    * {
      color: #6c6c6c;
    }
  </style>
</head>
<body>

<div class="row">
  <div class="col-12">
    <h2 class="page-heading">Customer details</h2>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered" border="1" style="border-collapse: collapse;">
    <tbody>
        <tr>
            <th width="10%">Name</th>
            <td width="90%">{{$customer->name}}</th>
        </tr>
        <tr>
            <th width="10%">Phone</th>
            <td width="90%">{{$customer->phone}}</th>
        </tr>
        <tr>
            <th width="15%">Email</th>
            <td width="90%">{{$customer->email}}</th>
        </tr>
        <tr>  
            <th width="10%">Address</th>
            <td width="90%">{{$customer->address}}</th>
        </tr>
        <tr>  
            <th width="15%">City</th>
            <td width="90%">{{$customer->city}}</th>
        </tr>
        <tr>
            <th width="10%">Country</th>
            <td width="90%">{{$customer->country}}</th>
        </tr>
        <tr>  
            <th width="10%">Pincode</th>
            <td width="90%">{{$customer->pincode}}</th>
        </tr>
    </tbody>
  </table>
</div>
</body>
</html>