<div class="row">
    <table>
        <tr>
            <th>S.No</th>
            <th>WayBill ID</th>
            <th>Comment</th>
            <th>Dat</th>
            <th>Location</th>
        </tr>
    @forelse ($tracks as $key => $track)
        <tr>
            <td>$key + 1</td>
            <td>$track->waybill_id</td>
            <td>$track->comment</td>
            <td>$track->dat</td>
            <td>$track->location</td>
        </tr>        
    @empty
        <div class="col-md-12">
            <p>No record found.</p>
        </div>
    @endforelse

</div>