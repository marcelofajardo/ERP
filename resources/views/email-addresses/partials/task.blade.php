<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped sort-priority-scrapper">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Store website</th>
                    <th>Type</th>
                    <th>Credential</th>
                    <th>Last Error</th>
                    <th>Last Error At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="conent">
                @foreach ($accounts as $i => $account)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $account['email'] }}</td>
                        <td>{{ $account['store_website'] }}</td>
                        <td>{{ $account['type'] }}</td>
                        <td>{{ $account['credential'] }}</td>
                        <td>{{ $account['last_error'] }}</td>
                        <td>{{ $account['last_error_at'] }}</td>
                        <td>{{ $account['status'] }}</td>
                    </tr>
                @endforeach
           </tbody>
        </table> 
    </div>
</div>