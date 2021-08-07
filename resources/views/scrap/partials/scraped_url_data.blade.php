@foreach ($logs as $log)

<tr @if($log->validated == 0) style="background:red !important;" @endif>
    @if($log->product_id)
    <td><a href="{{ route('products.show', $log->product_id) }}" target="_blank">{{ $log->product_id }}</a></td>
    @else
    <td></td>
    @endif
    <td>{{ $log->website }}</td>
    <td class="expand-row table-hover-cell"><span class="td-mini-container">
            <a href="{{ $log->url }}" target="_blank">{{ strlen( $log->url ) > 20 ? substr( $log->url , 0, 20).'...' :  $log->url }}</a>
        </span>
        <span class="td-full-container hidden">
            <a href="{{ $log->url }}" target="_blank">{{ $log->url }}</a>
        </span>
    </td>
    <td class="expand-row table-hover-cell">
        <span class="td-mini-container">
            {{ strlen( $log->sku ) > 6 ? substr( $log->sku , 0, 6).'...' :  $log->sku }}
        </span>
        <span class="td-full-container hidden">
            {{ $log->sku }}
        </span>

    </td>
    <td>
        @isset($log->brand->name))
            {{ $log->brand->name }}
        @endisset
    </td>
    <td class="expand-row table-hover-cell">
        <span class="td-mini-container">
            {{ strlen( $log->title ) > 6 ? substr( $log->title , 0, 6).'...' :  $log->title }}
        </span>
        <span class="td-full-container hidden">
            {{ $log->title }}
        </span>
    </td>
    <td>{{ $log->currency }}</td>
    <td>{{ $log->price }}</td>
    <td>
        @if(is_array($log->images))
            @if(array_find('.jpg', $log->images)==1 || array_find('.png', $log->images)==1 || array_find('.jpeg', $log->images)==1 || array_find('.gif', $log->images)==1 ) 
            <div class="green_img"></div>
            @else
            <div class="red_img"></div>
            @endif
        @elseif(false !== strpos($log->images,'.jpg') || false !== strpos($log->images,'.png') || false !== strpos($log->images,'.jpeg') || false !== strpos($log->images,'.gif') )
            <div class="green_img"></div>
        @else
            <div class="red_img"></div>
        @endif
    </td>
    <td>
        @isset($log->created_at)
        {{ $log->created_at->format('d-m-y') }}
        @endisset
    </td>
    <!-- <td>{{ $log->updated_at->format('d-m-y H:i:s') }}</td> -->
    @if($response != null)

    @if(in_array('color',$response['columns']))
    <td class="expand-row table-hover-cell">
        @if(is_array($log->properties))
        <span class="td-mini-container">
            {{ isset($log->properties['color'])?strlen($log->properties['color']) > 5 ? substr( $log->properties['color'] , 0, 5).'...' :  $log->properties['color']:'' }}
        </span>
        <span class="td-full-container hidden">
            {{ isset($log->properties['color'])?$log->properties['color']:'' }}
        </span>
        @else
        {{ unserialize($log->properties)['color'] }}
        @endif
    </td>
    @endif

    @if(in_array('category',$response['columns']))
    <td class="expand-row table-hover-cell">@if(($log->category != null && $log->category != '') || isset($log->properties['category']))
        @if(isset($log->properties['category']))
        @if(is_array($log->properties['category']))
        <span class="td-mini-container">
            {{ count($log->properties['category']) > 2 ? substr( implode(' , ',$log->properties['category']) , 0, 10).'...' : implode(' , ',$log->properties['category']) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',$log->properties['category']) }}
        </span>
        @else
        {{ $log->properties['category'] }}
        @endif
        @elseif(is_array(unserialize($log->category)))
        {{ implode(' ',unserialize($log->category) )}}
        @else
        {{ unserialize($log->category) }}
        @endif
        @endif
    </td>
    @endif

    @if(in_array('description',$response['columns']))
    <td>{{ $log->description }}</td>
    @endif

    @if(in_array('size_system',$response['columns']))
    <td>{{ $log->size_system }}</td>
    @endif

    @if(in_array('is_sale',$response['columns']))
    <td>{{ $log->is_sale }}</td>
    @endif

    @if(in_array('gender',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['gender']))
        {{ unserialize($log->properties)['gender']  }}
        @endif
    </td>
    @endif

    @if(in_array('composition',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['composition']))
        {{ unserialize($log->properties)['composition']  }}
        @endif
    </td>
    @endif

    @if(in_array('size',$response['columns']))
    <td class="expand-row table-hover-cell">
        @if(is_array($log->properties))
        @if(isset($log->properties['sizes']))
        @if(is_array($log->properties['sizes']))
        <span class="td-mini-container">
            {{ count($log->properties['sizes']) > 2 ? substr( implode(' , ',$log->properties['sizes']) , 0, 10).'...' : implode(' , ',$log->properties['sizes']) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',$log->properties['sizes']) }}
        </span>
        @else
        <span class="td-mini-container">
            {{ strlen( $log->properties['sizes'] ) > 10 ? substr( $log->properties['sizes'] , 0, 6).'...' :  $log->properties['sizes'] }}
        </span>
        <span class="td-full-container hidden">
            {{ $log->properties['sizes'] }}
        </span>
        @endif
        @elseif(isset($log->properties['size']))
        @if(is_array($log->properties['size']))
        <span class="td-mini-container">
            {{ count($log->properties['size']) > 2 ? substr( implode(' , ',$log->properties['size']) , 0, 10).'...' : implode(' , ',$log->properties['size']) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',$log->properties['size']) }}
        </span>
        @else
        <span class="td-mini-container">
            {{ strlen( $log->properties['sizes'] ) > 10 ? substr( $log->properties['sizes'] , 0, 6).'...' :  $log->properties['sizes'] }}
        </span>
        <span class="td-full-container hidden">
            {{ $log->properties['sizes'] }}
        </span>
        @endif
        @endif
        @elseif(isset(unserialize($log->properties)['size']) )
        @if(is_array(unserialize($log->properties)['size']))
        <span class="td-mini-container">
            {{ strlen( implode(' , ',unserialize($log->properties)['size'] ) ) > 10 ? substr( implode(' , ',unserialize($log->properties)['size'] ) , 0, 6).'...' :  implode(' , ',unserialize($log->properties)['size'] ) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',unserialize($log->properties)['size'] )}}
        </span>
        @else
        <span class="td-mini-container">
            {{ strlen( unserialize($log->properties)['size'] ) > 10 ? substr( unserialize($log->properties)['size'] , 0, 6).'...' : unserialize($log->properties)['size'] }}
        </span>
        <span class="td-full-container hidden">
            {{ unserialize($log->properties)['size'] }}
        </span>
        @endif
        @elseif(isset(unserialize($log->properties)['sizes']))
        @if(is_array(unserialize($log->properties)['sizes']))
        <span class="td-mini-container">
            {{ strlen( implode(' , ',unserialize($log->properties)['sizes'] ) ) > 10 ? substr( implode(' , ',unserialize($log->properties)['sizes'] ) , 0, 6).'...' :  implode(' , ',unserialize($log->properties)['sizes'] ) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',unserialize($log->properties)['sizes'] )}}
        </span>
        @else
        <span class="td-mini-container">
            {{ strlen( unserialize($log->properties)['sizes'] ) > 10 ? substr( unserialize($log->properties)['sizes'] , 0, 6).'...' : unserialize($log->properties)['sizes'] }}
        </span>
        <span class="td-full-container hidden">
            {{ unserialize($log->properties)['sizes'] }}
        </span>
        @endif
        @endif
    </td>
    @endif
    @if(in_array('dimension',$response['columns']))
    <td class="expand-row table-hover-cell">
        @if(is_array($log->properties))
        @if(isset($log->properties['dimension']))
        @if(is_array($log->properties['dimension']))
        <span class="td-mini-container">
            {{ count($log->properties['dimension']) > 2 ? substr( implode(' , ',$log->properties['dimension']) , 0, 10).'...' : implode(' , ',$log->properties['dimension']) }}
        </span>
        <span class="td-full-container hidden">
            {{ implode(' , ',$log->properties['dimension']) }}
        </span>
        @else
        <span class="td-mini-container">
            {{ strlen( $log->properties['dimension'] ) > 10 ? substr( $log->properties['dimension'] , 0, 6).'...' :  $log->properties['dimension'] }}
        </span>
        <span class="td-full-container hidden">
            {{ $log->properties['dimension'] }}
        </span>
        @endif
        @endif
        @endif
    </td>
    @endif
    @if(in_array('lmeasurement',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['lmeasurement'])) {{ unserialize($log->properties)['lmeasurement']  }} @endif</td>
    @endif

    @if(in_array('hmeasurement',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['hmeasurement'])) {{ unserialize($log->properties)['hmeasurement']  }} @endif</td>
    @endif

    @if(in_array('dmeasurement',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['dmeasurement'])) {{ unserialize($log->properties)['dmeasurement']  }} @endif</td>
    @endif

    @if(in_array('measurement_size_type',$response['columns']))
    <td>@if(isset(unserialize($log->properties)['measurement_size_type'])) {{ unserialize($log->properties)['measurement_size_type']  }} @endif</td>
    @endif

    @endif
    <td>{{ $log->validation_result }}</td>
    <td>
        <button data-toggle="tooltip" type="button" class="btn btn-xs btn-image load-task-assign-modal" data-id="{{$log->id}}" title="Load task assign modal"><i class="fa fa-tasks"></i></button>
    </td>
</tr>

@endforeach

<div id="loadTaskAssignModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('scrap.assignTask') }}" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Assign Generic Scraper task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Assigned To:</strong>
                        <select class="form-control assigned_to" name="assigned_to" data-live-search="true">
                            @foreach($users as $k => $user)
                            <option value="{{ $k }}">{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    @csrf
                    <div class="form-group">
                        <strong>Subject:</strong>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Message:</strong>
                        <textarea class="form-control " name="message"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Create</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".load-task-assign-modal").click(function(e) {
            $("#loadTaskAssignModal").modal("show");
        });
    });
</script>