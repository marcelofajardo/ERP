@extends('layouts.app')

@section('favicon' , 'selectiongrid.png')
@section('title', 'Product Selection Grid')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Selectors</h2>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="productGrid" id="productGrid">
    {{--@foreach ($productselection as $product)

            <div class="col-md-3 col-xs-6">
                <a href="{{ route('products.show',$product->id) }}">
                    <img src="/uploads/{{ $product->image }}" class="img-responsive" style="max-width: 200px;" alt="">
                                    <p>{{ $product->sku }}</p>
                </a>
            </div>

        @endforeach--}}
    </div>

    <script>


        Array.prototype.groupBy = function(prop) {
            return this.reduce(function(groups, item) {
                const val = item[prop]
                groups[val] = groups[val] || []
                groups[val].push(item)
                return groups
            }, {})
        };


        const products = [
            @foreach ($productselection as $product)
            <?php $r = explode(' ',$product->created_at); ?>
            {   'sku': '{{ $product->sku }}',
                'id' : '{{ $product->id }}',
                'size' : '{{ $product->size}}',
                'price' : '{{ $product->price }}',
                'image' : '{{ $product->image }}',
                'created_at': '{{ $r[0]  }}',
                'link' : '{{ route('productselection.edit',$product->id) }}'
            },
            @endforeach
        ];

        const groupedByTime = products.groupBy('created_at');

        jQuery(document).ready(function () {

            Object.keys(groupedByTime).forEach(function(key) {

                let html = '<h4>'+getTodayYesterdayDate(key)+'</h4><div class="row">';

                groupedByTime[key].forEach( function (product) {

                    html +=  `
                        <div class="col-md-3 col-xs-6 text-center">
                        <a href="`+product['link']+`">
                            <img src="/uploads/`+product['image']+`" class="img-responsive" style="max-width: 200px;" alt="">
                                            <p>Sku : `+product['sku']+`</p>
                                            <p>Id : `+product['id']+`</p>
                                            <p>Size : `+product['size']+`</p>
                                            <p>Price : `+product['price']+`</p>
                        </a>
                        </div>
                    `;
                });

                jQuery('#productGrid').append(html+'</div>');
            });

        });

    </script>

    {!! $productselection->links() !!}

@endsection