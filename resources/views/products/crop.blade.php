@extends('layouts.app')

@section('large_content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="page-heading">
                Crop Image Approval <a href="{{ asset('Crop_approval_SOP.pdf') }}" class="pull-right">SOP</a>
{{--                Crop Image Approval <a href="{{ route('sop.index') }}?type=Crop" class="pull-right">SOP</a>--}}
            </h5>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>{{ $product->sku }}
                                <br>{{ $product->is_on_sale ? 'ON SALE' : 'NO SALE' }}
                                <br><a href="{{ action('ProductController@show', $product->id) }}">{{ $product->id }}</a>
                                <br><strong>{{ $product->product_category->title }}</strong>
                                <br>
                                <strong class="text-danger">Update Category If Incorrect:</strong>
                                <select data-id="{{$product->id}}" class="form-control" id="category" name="category">
                                    @foreach ($category_array as $data)
                                        <option value="{{ $data['id'] }}" {{ $data['id']==$product->category ? 'selected' : '' }}>{{ $data['title'] }}</option>
                                        @if ($data['title'] == 'Men')
                                            @php
                                                $color = '#D6EAF8';
                                            @endphp
                                        @elseif ($data['title'] == 'Women')
                                            @php
                                                $color = '#FADBD8';
                                            @endphp
                                        @else
                                            @php
                                                $color = '';
                                            @endphp
                                        @endif

                                        @foreach ($data['child'] as $children)
                                            <option style="background-color: {{ $color }};" value="{{ $children['id'] }}" {{ $children['id']==$product->category ? 'selected' : '' }}>&nbsp;&nbsp;{{ $children['title'] }}</option>
                                            @foreach ($children['child'] as $child)
                                                <option style="background-color: {{ $color }};" value="{{ $child['id'] }}" {{ $child['id']==$product->category ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child['title'] }}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @if($product->crop_rejected_by != null)
                                    <br>INPORTANT :: <strong class="text-danger">Previous Rejection Reason: {{ $product->crop_remark }}</strong>
                                @endif
                                <form action="{{ action('ProductCropperController@rejectCrop', $product->id) }}">
                                    <a href="{{ action('ProductCropperController@approveCrop', $product->id) }}{{'?'.$q}}" type="button" class="btn btn-secondary approvebtn">Approve</a>
                                    <br><br>
                                    @if($q=='rejected=yes')
                                        <input type="hidden" name="rejected" value="yes">
                                    @endif
                                    <label for="remark" class="text-danger">Select <strong>CORRECT</strong> Reason (MUST SELECT, otherwise you wont be PAID!!)</label>
                                    <select name="remark" id="remark" required>
                                        <option value="">Select reason...</option>
                                        <option value="White Image Crop Issue">White Image Crop Issue</option>
                                        <option value="Images Not Cropped Correctly">Images Not Cropped Correctly</option>
                                        <option value="No Images Shown">No Images Shown</option>
                                        <option value="Grid Not Shown">Grid Not Shown</option>
                                        <option value="First Image Not Available">First Image Not Available</option>
                                        <option value="Dimension Not Available">Dimension Not Available</option>
                                        <option value="Wrong Grid Showing For Category">Wrong Grid Showing For Category</option>
                                        <option value="Incorrect Category">Incorrect Category</option>
                                        <option value="Only One Image Available">Only One Image Available</option>
                                        <option value="Blurry Image">Blurry Image</option>
                                        <option value="No Reference">No Reference</option>
                                    </select>
{{--                                    <input type="text" class="form-control" placeholder="Remark..." name="remark" id="remark">--}}
                                    <button class="btn btn-danger">Reject</button>
                                    <br>
                                    @if($secondProduct)
{{--                                        <a href="{{ action('ProductCropperController@showImageToBeVerified', $secondProduct->id) }}">Next Image</a>--}}
                                    @endif
                                </form>
                            </td>
                            <td>
                                <strong>Dimenson: {{$product->lmeasurement }} X {{ $product->hmeasurement }} X {{ $product->dmeasurement }}</strong>
                                <?php
                                try {
                                    ?>
                                        <strong>Dimension: {{round($product->lmeasurement*0.393701)}} X {{round($product->hmeasurement*0.393701)}} X {{round($product->dmeasurement*0.393701)}}</strong>
                                    <?php
                                } catch (Exception $exception) {

                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
        </div>
        <div class="col-md-12">
            <div style="margin: 0 auto; width: 100%">
                <form action="{{ action('ProductCropperController@ammendCrop', $product->id) }}" method="post">
                    @csrf
                    @foreach($product->media()->get() as $image)

                        @if (stripos($image->filename, 'cropped') !== false)
                            <div style="display: inline-block; border: 1px solid #ccc" class="mt-5">
                                <div style="width: 80%; margin: 5px auto;">
                                    @if(\App\CroppedImageReference::where('new_media_id', $image->id)->first())
                                        <span class="label label-success" style="font-size: 12px;">Has Reference</span>
                                    @else
                                        <span class="label label-danger" style="font-size: 12px;">NO REFERENCE</span>
                                    @endif
                                        <br>
                                        <br>
                                    <input type="hidden" name="url[{{$image->filename}}]" value="{!! $image->getUrl() !!}">
                                    <input type="hidden" name="mediaIds[{{$image->filename}}]" value="{!! $image->id !!}">
                                    <div class="form-group">
                                        <select class="form-control avoid-approve" style="width: 100px; !important;" name="size[{{$image->filename}}]" id="size">
                                            <option value="ok">Select Sizes...</option>
                                            <optgroup label="Model Image">
                                                <option value="H812">HEIGHT - 812</option>
                                                <option value="H848">HEIGHT - 848</option>
                                                <option value="H804">HEIGHT - 804</option>
                                            </optgroup>
                                            @if($img=='Backpack.png')
                                                <optgroup label="Backpacks">
                                                    <option value="H257">HEIGHT - 257</option>
                                                    <option value="H326">HEIGHT - 326</option>
                                                    <option value="H366">HEIGHT - 366</option>
                                                    <option value="H471">HEIGHT - 471</option>
                                                    <option value="H540">HEIGHT - 540</option>
                                                    <option value="H612">HEIGHT - 612</option>
                                                    <option value="H677">HEIGHT - 677</option>
                                                    <option value="H744">HEIGHT - 744</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='belt.png')
                                                <optgroup label="Belts">
                                                    <option value="W738">Width - 738</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Clothing.png')
                                                <optgroup label="Clothing">
                                                    <option value="H790">HEIGHT- 790</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='shoes_grid.png')
                                                <optgroup label="Shoes">
                                                    <option value="W720">WIDTH - 720</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='bow.png')
                                                <optgroup label="Bow">
                                                    <option value="W738">WIDTH - 738</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Hair_accessories.png')
                                                <optgroup label="Hair Accessories">
                                                    <option value="W606">WIDTH - 606</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Jewellery.png')
                                                <optgroup label="Jewelry">
                                                    <option value="W606">WIDTH - 606</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Wallet.png')
                                                <optgroup label="Wallet">
                                                    <option value="H210">HEIGHT - 210</option>
                                                    <option value="H290">HEIGHT - 290</option>
                                                    <option value="H353">HEIGHT - 353</option>
                                                    <option value="H448">HEIGHT - 448</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Tote.png')
                                                <optgroup label="Tote Bags">
                                                    <option value="H252">HEIGHT - 252</option>
                                                    <option value="H326">HEIGHT - 326</option>
                                                    <option value="H404">HEIGHT - 404</option>
                                                    <option value="H470">HEIGHT - 470</option>
                                                    <option value="H540">HEIGHT - 540</option>
                                                    <option value="H606">HEIGHT - 606</option>
                                                    <option value="H678">HEIGHT - 678</option>
                                                    <option value="H742">HEIGHT - 742</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Sunglasses.png')
                                                <optgroup label="Sunglasses">
                                                    <option value="H235">HEIGHT - 235</option>
                                                    <option value="H442">HEIGHT - 442</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Shoulder_bag.png')
                                                <optgroup label="Shoulder Bags">
                                                    <option value="H256">HEIGHT - 256</option>
                                                    <option value="H316">HEIGHT - 316</option>
                                                    <option value="H380">HEIGHT - 380</option>
                                                    <option value="H446">HEIGHT - 446</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Shawl.png')
                                                <optgroup label="Shawl">
                                                    <option value="H540">HEIGHT - 540</option>
                                                    <option value="H610">HEIGHT - 610</option>
                                                    <option value="H742">HEIGHT - 742</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Handbag.png')
                                                <optgroup label="Handbags">
                                                    <option value="H252">HEIGHT - 252</option>
                                                    <option value="H404">HEIGHT - 404</option>
                                                    <option value="H542">HEIGHT - 542</option>
                                                    <option value="H694">HEIGHT - 694</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Clutch.png')
                                                <optgroup label="Clutch">
                                                    <option value="H525">HEIGHT - 252</option>
                                                    <option value="H322">HEIGHT - 322</option>
                                                    <option value="H382">HEIGHT - 382</option>
                                                    <option value="H443">HEIGHT - 443</option>
                                                </optgroup>
                                            @endif
                                            @if($img=='Keychains.png')
                                                <optgroup label="Keychain">
                                                    <option value="H470">HEIGHT - 470</option>
                                                </optgroup>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control avoid-approve" style="width: 100px; !important;" name="padding[{{$image->filename}}]" id="padding">
                                            <option value="ok">Padding...</option>
                                            <option value="96">96</option>
                                            <option value="121">121</option>
                                            <option value="108">108</option>
                                        </select>
                                    </div>
                                </div>
                                <div style=" margin-bottom: 5px; width: 500px;height: 500px; background-image: url('{{$image->getUrl()}}'); background-size: 500px">
                                    <img style="width: 500px;" src="{{ asset('images/'.$img) }}" alt="">
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div style="position: fixed; width: 100px; height: 200px; top: 18px; left: 18px;">
                        <button class="btn btn-secondary btn-lg">Update Cropped <br>Images</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Fotorama from CDNJS, 19 KB -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
        $(document).ready(function() {
            $('.avoid-approve').change(function() {
                $('.approvebtn').fadeOut();
            });
            $('#category').change(function() {
                let productId = $(this).data('id');
                let categoryId = $(this).val();

                $.ajax({
                    url: '/products/'+productId+'/updateCategory',
                    data: {
                        category: categoryId,
                        _token: "{{csrf_token()}}"
                    },
                    type: 'POST',
                    success: function() {
                        location.reload();
                    }
                })

            });
        });
    </script>
    @if (Session::has('mesage'))
        <script>
            Swal.fire(
                'Success',
                '{{Session::get('message')}}',
                'success'
            )
        </script>
    @endif
@endsection