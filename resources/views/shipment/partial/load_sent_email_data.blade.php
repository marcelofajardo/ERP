
<div class="row">
    @forelse ($emails as $key => $item)
        <div class="col-md-12">
            <p class="pull-right"><label>Sent at: </label> {{ date('d-m-Y', strtotime($item->created_at)) }}</p>
            <p><label>To: </label> {{ $item->to }}</p>
            @if($item->cc) <p><label>Cc: </label> {{ $item->cc }}</p> @endif
            @if($item->bcc) <p><label>Bcc: </label> {{ $item->bcc }}</p> @endif
            <p><label>Subject: </label> {{ $item->subject }}</p>
            <p><label>Message: </label> {{ $item->message }}</p>
            <p><label>Attachment: </label>
                @if ($item->additional_data)
                    @php
                        $data = json_decode($item->additional_data, true);
                    @endphp
                    @forelse ($data['attachment'] as $key1 => $file)
                        <p>{{ substr($file, strrpos($file, '/') + 1) }}</p>
                    @empty
                        
                    @endforelse
                @endif
            </p>
            <hr class="mt-2 mb-3"/>
        </div>
    @empty
        <div class="col-md-12">
            <p>No record found.</p>
        </div>
    @endforelse
</div>