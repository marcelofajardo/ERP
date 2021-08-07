 @if($languages->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
        @else

          @foreach ($languages as $language)

            <tr id="row{{ $language->id }}">
              <td><input type="text" value="{{ $language->locale }}" class="form-control" onfocusout="updateDetails({{ $language->id }})" id="locale{{ $language->id }}">
              </td>
              <td><input type="text" value="{{ $language->code }}" class="form-control" onfocusout="updateDetails({{ $language->id }})" id="code{{ $language->id }}"></td>
              <td><input type="text" value="{{ $language->store_view }}" class="form-control" onfocusout="updateDetails({{ $language->id }})" id="store_view{{ $language->id }}"></td>
              <td><input type="text" value="{{ $language->status }}" class="form-control" onfocusout="updateDetails({{ $language->id }})" id="status{{ $language->id }}"></td>
              <td><button type="button" onclick="deleteLanguage({{ $language->id }})" class="btn btn-image"><img src="/images/delete.png" /></button></td>
            </tr>
           @endforeach

          @endif

