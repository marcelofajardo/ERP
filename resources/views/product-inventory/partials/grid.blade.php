<?php $isAdmin = auth()->user()->isAdmin();  ?>
<?php foreach($items as $date => $raw){ ?>
  <h4><?php echo $date; ?></h4>
    <div class="row">
    <?php 
      $rowCount = 0;
    ?>  
    <?php foreach($raw as $product) { ?>
      <?php 
        /*if($rowCount % 4 == 0) { echo '<div class="row">'; } 
           $rowCount++;  */
      ?>
      <div class="col-md-3 col-xs-6 text-left mb-5">
          <a href="{{ route( 'products.show', $product->id ) }}">
            <img style="object-fit: cover;max-width:75%;" src="{{ $product->getMedia(config('constants.attach_image_tag'))->first() ? $product->getMedia(config('constants.attach_image_tag'))->first()->getUrl()
              : '' }}" class="img-responsive grid-image" alt="...">
          </a>      
          <div class="card-body">
            <a href="{{ route( 'products.show', $product->id ) }}">
              <p class="card-text">SKU : {{ $product->sku }}</p>
              <p class="card-text">Id : {{ $product->id }}</p>
              <p class="card-text">Size : {{ $product->size }}</p>
              @if($isAdmin)
                <p class="card-text">Price : {{ $product->price }}</p>
                <p class="card-text">Discounted % : {{ $product->discounted_percentage }}%</p>
              @endif
              <p class="card-text">Price INR : {{ $product->price_inr }}</p>  
              <p class="card-text">Status : {{ (new \App\Stage)->getNameById( $product->stage ) }}</p>
              <p class="card-text">Ref. Category : {{ $product->reference_category }}</p>
            </a>
            @if($isAdmin)
            <p class="card-text">Category : <?php echo Form::select("category",$categoryArray,$product->category,[
                "class" => "form-control update-product" , 
                "id" => "category_".$product->id,
                "data-id" => $product->id
              ]); ?></p>
            @else 
              <p class="card-text">Category : {{ ($product->categories) ? $product->categories->title : "N/A" }}</p>
            @endif  
            <p class="card-text">Ref. Color : {{ $product->reference_color }}</p>
            @if($isAdmin)
              <p class="card-text">Color : <?php echo Form::select("color",$sampleColors,$product->color,[
                  "class" => "form-control update-color" , 
                  "id" => "id_".$product->id,
                  "data-id" => $product->id
                ]); ?></p>
            @else 
              <p class="card-text">Color : {{ $product->color }}</p>
            @endif
            
              <p class="card-text">Supplier : {{ $product->supplier }}</p>
              <p class="card-text">Suppliers : {{ $product->supplier_list }}</p>
              <p class="card-text">
                <input type="checkbox" class="select-product-edit" name="product_id" data-id="{{ $product->id }}">
                <a href="{{ route('product-inventory.fetch.img.google',['name'=> $product->name, 'id' => $product->id]) }}" class="btn btn-secondary btn-sm"><i class="fa fa-picture-o"></i></a>

                <i class="fa fa-check-square-o add_purchase_product" title="Purchase Product" style="cursor: pointer;" data-id="{{ $product->id }}" aria-hidden="true"></i>
              </p>
            
          </div>
        </div>
      <?php //if($rowCount % 4 == 0) { echo '</div>'; } ?>
    <?php } ?>
    </div>
<?php } ?>