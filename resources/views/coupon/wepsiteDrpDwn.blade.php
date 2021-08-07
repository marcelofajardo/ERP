@foreach($websites as $website)
    <option value="{{ $website->platform_id }}">{{ $website->name }}</option>
@endforeach