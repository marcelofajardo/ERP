@extends('layouts.app')

@section('title','Product pricing')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<style>
    .model-width{
        max-width: 1250px !important;
    }
</style>
<div class = "row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Product pricing</h2>
    </div>
</div>


@include('partials.flash_messages')
<div class = "row">
    <div class="col-md-10 margin-tb">
        <div class="pull-left cls_filter_box">
            <form class="form-inline" action="" method="GET">
                <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Enter Product Or SKU">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <select name="country_code" class="form-control">
                        @php $country = request('country_code','') @endphp
                        <option value="">Select country code</option>
                        @foreach ($countryGroups as $key => $item)
                            <option value="{{ $key }}" {{ ( $country == $key ) ? 'selected' : '' }} >{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox">
                    <?php echo Form::select("random",["" => "No","Yes" => "Yes"],request('random'),["class"=> "form-control"]); ?>
                </div>
                {{-- <div class="form-group ml-3 cls_filter_inputbox">
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="keyword">
                </div> --}}
                <button type="submit" class="btn btn-secondary ml-3">Get record</button>
            </form> 
        </div>
    </div>  
    <div class="col-md-2 margin-tb">
        <div class="pull-right mt-3">
            {{-- <button type="button" class="btn btn-secondary" btn="" btn-success="" btn-block="" btn-publish="" mt-0="" data-toggle="modal" data-target="#setSchedule" title="" data-id="1">Set cron time</button> --}}
           
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 margin-tb">
        {{-- {{ $list->links() }} --}}
        <div class="panel-group" style="margin-bottom: 5px;">
            <div class="panel mt-3 panel-default">
                <table class="table table-bordered table-striped" id="product-price">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>PRODUCT ID</th>
                            <th>Country</th>
                            <th>BRAND</th>
                            <th>SEGMENT</th>
                            <th>MAIN WEBSITE</th>
                            <th>EURO PRICE</th>
                            <th>SEG DISCOUNT</th>
                            <th>LESS IVA</th>
                            <th>ADD DUTY ( DEFAULT )</th>
                            <th>ADD PROFIT</th>
                            <th>FINAL PRICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product_list as $key)
                            <tr>
                                <td>{{ $key['sku'] }}</td>
                                <td>{{ $key['id'] }}</td>
                                <td>{{ $key['country_name'] }}</td>
                                <td>{{ $key['brand'] }}</td>
                                <td>{{ $key['segment'] }}</td>
                                <td>{{ $key['website'] }}</td>
                                <td>{{ $key['eur_price'] }}</td>
                                <td>{{ $key['seg_discount'] }}</td>
                                <td>{{ $key['iva'] }}</td>
                                <td>{{ $key['add_duty'] }}</td>
                                <td>{{ $key['add_profit'] }}</td>
                                <td>{{ $key['final_price'] }}</td>
                            </tr>
                        @empty
                            <tr>
                               <td colspan="11"> NO data found </td> 
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- {{ $list->links() }} --}}
    </div>
</div>

@endsection
    
@section('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>

    $(document).ready( function () {
        $('#product-price').DataTable({
            "paging":   false,
            "ordering": true,
            "info":     false
        });
    } );
</script>

@endsection
