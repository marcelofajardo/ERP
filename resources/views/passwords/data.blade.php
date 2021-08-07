 @if($passwords->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
        @else

          @foreach ($passwords as $password)

            <tr>
              <td>
                {{ $password->website }}
                <br>
                <a href="{{ $password->url }}" target="_blank"><small class="text-muted">{{ $password->url }}</small></a>
              </td>
              <td>{{ $password->username }}</td>
              <td>{{ Crypt::decrypt($password->password) }}</td>
              <td>{{ $password->registered_with }}</td>
                <td><button onclick="changePassword({{ $password->id }})" class="btn btn-secondary btn-sm">Change</button>
                <button onclick="getData({{ $password->id }})" class="btn btn-secondary btn-sm">History</button></td>
            </tr>


          @endforeach

          @endif