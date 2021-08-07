	<div class="row">
        <div class="col-xs-12 col-md-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <td colspan="3">ID: <strong>{{ $id }}</strong></td>
                    <td colspan="2">Name: <strong>{{ $name }}</strong></td>
                    <td colspan="3">Scraped: <strong>@if($scraped) {{ $scraped->created_at ? $scraped->created_at->format('Y-m-d') : 'N/A' }} @else N/A @endif</strong></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3">Size: <strong>{{ $size ?? 'N/A' }}</strong></td>
                    <td colspan="2" rowspan="4">
                        <div style="width: 250px;">
                            <strong>Short Description:</strong> <br>{{ $short_description }}
                        </div>
                    </td>
                    <td colspan="3">Cropped: <strong>{{ 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3">Made In: <strong>{{ $made_in ?? 'N/A' }}</strong></td>
                    <td>Crop Approval</td>
                    <td>Date & Time</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $product->is_crop_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->crop_approved_at ?? 'N/A' }}</td>
                    <td>{{ $product->cropApprover ? $product->cropApprover->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Brand: {{ \App\Http\Controllers\BrandController::getBrandName($brand)}}</td>
                    <td colspan="2">
                        Measurement <br>
                        L : {{$lmeasurement ?? 'N/A'}} &nbsp;
                        H : {{$hmeasurement ?? 'N/A'}} &nbsp;
                        D : {{$dmeasurement ?? 'N/A'}} &nbsp;
                    </td>
                    <td>Sequence Approval</td>
                    <td>Date</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>{{ $product->is_crop_ordered ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->crop_ordered_at ?? 'N/A' }}</td>
                    <td>{{ $product->cropOrderer ? $product->cropOrderer->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Color: <strong>{{ $color }}</strong></td>
                    <td colspan="2">Composition: <strong>{{ $composition }}</strong></td>
                    <td>Attribute Approval</td>
                    <td>Date</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>{{ $product->is_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->listing_approved_at ?? 'N/A' }}</td>
                    <td>{{ $product->approver ? $product->approver->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Price (In Euro): <strong>{{$price}}</strong></td>
                    <td colspan="2">SKU: <strong>{{$sku}}</strong></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td colspan="3">Price (in INR): <strong>{{$price_inr}}</strong></td>
                    <td colspan="2">Sku+color: {{ $sku.$color }}</td>
                    <td colspan="3" rowspan="3"><img src="{{ $images }}" class="img-responsive thumbnail-200 mb-1" alt="Product Image" title="Product Image"></td>
                </tr>
                <tr>
                    <td colspan="3">Price Special (in INR): <strong>{{ $price_inr_special }}</strong></td>
                    <td colspan="2">
                        Category:
                        <strong>
                            @if(isset($categories) && $categories != null)
                            @for( $i = 0 ; $i < count($categories) - 1 ; $i++)
                                {{ $categories[$i] }}->
                            @endfor
                            {{ $categories[$i] }}
                            @endif
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
        </div>
    </div>