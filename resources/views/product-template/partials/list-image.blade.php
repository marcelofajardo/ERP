
@foreach($templates as $template)
<tr>
<td>{{ $template->id }}</td>
<td>{{ $template->template_no }}</td>
<td><img src="{{ $template->getMedia('template-image')->last() ? $template->getMedia('template-image')->last()->getUrl() : '' }}" class="img-responsive" alt="" width="300" height="300" /></td>
<td>{{ $template->product_title }}</td>
<td>@if($template->brand) {{ $template->brand->name }} @endif</td>
<td>@if($template->category) {{ $template->category->title }} @endif</td>
<td>{{ $template->currency }}</td>
<td>{{ $template->price }}</td>
<td>{{ $template->discounted_price }}</td>
<td>{{ $template->created_at }}</td>
</tr>
@endforeach


