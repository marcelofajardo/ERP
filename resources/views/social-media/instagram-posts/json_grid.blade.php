@foreach($posts as $key=>$post)
    <tr>
        <td>{{ date('d-M-Y H:i:s', strtotime($post->posted_at)) }}</td>
        <td>#{{ $post->hashTags->hashtag }}</td>
        <td><a href="https://instagram.com/{{ $post->username }}" target="_blank">{{ $post->username }}</a></td>
        <td>{{ wordwrap($post->caption,75, "\n", true) }}</td>
    </tr>
@endforeach