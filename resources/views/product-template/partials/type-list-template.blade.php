
@foreach($templates as $template)
<tr>
	<td>{{ $template->id }}</td>
<td>{{ $template->template_no }}</td>
<td>{{ $template->product_title }}</td>
<td>{{ $template->brand_id }}</td>
<td>{{ $template->currency }}</td>
<td>{{ $template->price }}</td>
<td>{{ $template->discounted_price }}</td>
<td>{{ $template->product_id }}</td>
<td>{{ $template->is_processed }}</td>
<td>{{ $template->created_at }}</td>

</tr>
@endforeach

