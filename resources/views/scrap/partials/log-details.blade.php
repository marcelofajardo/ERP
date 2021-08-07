<table class="table table-bordered table-striped sort-priority-scrapper">
    <thead>
        <tr>
            <th>File name</th>
            <th>Folder Name</th>
            <th>log_messages</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody class="conent">
        @foreach ($logDetails as $log)
            <tr>
                <td>{{ $log->file_name }}</td>
                <td>{{ $log->folder_name }}</td>
                <td>{{ $log->log_messages }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @endforeach
   </tbody>
</table>
