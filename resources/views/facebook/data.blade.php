@foreach ($posts as $post)
            <tr>
            <td>{{ \Carbon\Carbon::parse($post->created_at)->format('d-m') }}</td>
              <td>{{ $post->account->first_name }}</td>
              <td>{{ $post->caption }}</td>
              <td>{{ $post->post_body }}</td>
              <td>
              @if ($post->hasMedia(config('constants.media_tags')))
              <a data-fancybox="gallery" href="{{ $post->getMedia(config('constants.media_tags'))->first()->getUrl() }}"><img width="100" src="{{ $post->getMedia(config('constants.media_tags'))->first()->getUrl() }}"></a>

              @endif
              
              </td>
              <td>{{ \Carbon\Carbon::parse($post->posted_on)->format('d-m-y h:m') }}</td>
              <td>{{ $post->status ? 'Posted' : '' }}</td>
              <td></td>
              </tr>
          @endforeach
          {{$posts->appends(request()->except("page"))->links()}}