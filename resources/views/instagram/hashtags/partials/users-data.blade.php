@foreach($users as $user)
<tr>
    <td><a href="https://instagram.com/{{ $user->username }}" target="_blank">{{ $user->username }}</a></td>
    <td>{{ $user->location }}</td>
    <td>{{ $user->because_of }}</td>
    <td>{{ $user->followers }}</td>
    <td>{{ $user->following }}</td>
    <td>{{ $user->posts }}</td>
    <td class="expand-row table-hover-cell"><span class="td-mini-container">{{ strlen($user->bio) > 80 ? substr($user->bio, 0, 80) : $user->bio }}</span>
        <span class="td-full-container hidden">
            {{ $user->bio }}
        </span></td>
</tr> 
@endforeach
