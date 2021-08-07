@foreach ($websites as $list)
    {{-- <tr> --}}
        {{-- <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d-m-y') }} </td> --}}
        {{-- <td>{{ $list->id }}</td> --}}
        {{-- <td>{{ $list->storeWebsite->website ?? '' }}</td> --}}
        {{-- <td>{{ $list->name }}</td> --}}
        {{-- <td></td> --}}

        {{-- <td> --}}
            {{-- <button title="Open Images" type="button" class="btn preview-attached-img-btn btn-image no-pd" --}}
                {{-- data-suggestedproductid="{{ $list->id }}"> --}}
                {{-- <img src="/images/forward.png" style="cursor: default;"> --}}
            {{-- </button> --}}
            {{-- <button title="Send Images" type="button" class="btn btn-image sendImageMessage no-pd" data-id="{{$list->id}}" data-suggestedproductid="{{$list->id}}"><img src="/images/filled-sent.png" /></button> --}}
        {{-- </td> --}}
    {{-- </tr> --}}


    @if (!$list->stores)

        <tr class="expand-{{ $list->id }} hidden">
            <td colspan="4" class="text-center">Stores</td>
        </tr>

        <tr class="expand-{{ $list->id }} hidden">
            <td>{{ 'No Store found' }}</td>
        </tr>
    @endif

    @foreach ($list->stores as $stIndex => $store)

        @if ($stIndex == 0)
            <tr class="expand-{{ $list->id }} hidden">
                <td colspan="4" class="text-center">
                    <h4>Stores</h4>
                </td>
            </tr>
        @endif

            @foreach ($store->storeViewMany as $item)
                
                <tr class="expand-{{ $list->id }}">
                    <td>{{ \Carbon\Carbon::parse($store->created_at)->format('d-m-y') }}</td>
                    <td>{{ $store->id }}</td>
                    <td>{{ $list->storeWebsite->website ?? '' }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $item->name }}({{ $item->code }})</td>

                    <td>

                        <button data-url="{{ route('scrapper.phyhon.listImages', ['id' => $store->id,'code' => $item->code]) }}" title="Open Images"
                            type="button" class="btn show-scrape-images btn-image no-pd"
                            data-suggestedproductid="{{ $store->id }}">
                            <img src="/images/forward.png" style="cursor: default;">
                        </button>

                        <span class="btn"> <input type="checkbox" class="defaultInput" {{ $store->is_default ? 'checked' : '' }}
                                onclick="setStoreAsDefault(this)" data-website-id="{{ $list->id }}"
                                data-store-id="{{ $store->id }}" /> Set as default</span>



                        {{-- <button title="Send Images" type="button" class="btn btn-image sendImageMessage no-pd" data-id="{{$store->id}}" data-suggestedproductid="{{$list->id}}"><img src="/images/filled-sent.png" /></button> --}}
                    </td>

                    <!--  <td colspan="7" id="attach-image-list-{{ $list['id'] }}">
                    @if ($list['scrapper_image'])
                    {{-- @include('scrapper-phyhon.list-image-products') --}}
                        
                    @endif
                    </td> -->
                </tr>

            @endforeach
            



        <tr class="expand-images-{{ $store->id }} hidden">
            <td colspan="7" id="attach-image-list-{{ $store->id }}">
                {{-- @if ($store->scrapperImage) --}}
                {{-- @include('scrapper-phyhon.list-image-products') --}}
                {{-- @endif --}}
            </td>
        </tr>

    @endforeach

    <tr class="expand-{{ $list->id }} hidden">
        <td colspan="4" class="text-center">
            <hr>
        </td>
    </tr>

@endforeach
<tr>
    <td colspan="4">
        {{ $websites->appends(request()->except('page'))->links() }}
    </td>
</tr>
