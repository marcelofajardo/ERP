<script type="text/x-jsrender" id="template-create-website">
    
        <div class="modal-content">
           
           <div class="modal-header">
              <h5 class="modal-title">{{if data.id}} Edit Category SEO {{else}}Create Category SEO{{/if}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
           </div>
          
            <form name="form-create-website" method="post">
            <?php echo csrf_field(); ?>
           
              <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label >Copy from</label>
                      <select class="form-control website-form-page">
                        <option value="">-- Select --</option>
                          <?php foreach($categroy_seos_list as $item) { ?>
                            <option value="<?php echo $item->id; ?>"><?php echo $item->id.' - '.$item->meta_title; ?></option>
                          <?php } ?>
                      </select>
					  <input type="checkbox" name="ctitle" id="ctitle"> <label for="ctitle"> Meta title </label>
					  <input type="checkbox" name="ckeyword" id="ckeyword"> <label for="ckeyword"> Meta Keywords </label>
					  <input type="checkbox" name="cdesc" id="cdesc"> <label for="cdesc">Meta Description</label>
					  <button class="btn btn-secondary btn-xs reload-page-data" title="Reload page data" type="button"><i class="fa fa-refresh" ></i></button>
                    </div>
                    <div class="form-group col-md-6">
						<label for="store_copy_id">Copy to</label>
						<select id="store_copy_id" class="form-control">
							<option value="">-- Select --</option>
							<?php foreach($categroy_seos_list as $item) { ?>
								<option value="<?php echo $item->id; ?>"><?php echo $item->id.' - '.$item->meta_title; ?></option>
							<?php } ?>
						</select>
						<input type="checkbox" name="cttitle" id="cttitle"> <label for="cttitle"> Meta title </label>
						<input type="checkbox" name="ctkeyword" id="ctkeyword"> <label for="ctkeyword"> Meta Keywords </label>
						<input type="checkbox" name="ctdesc" id="ctdesc"> <label for="ctdesc">Meta Description</label>
						<br>
						<input type="checkbox" name="entire_category" id="entire_category"> <label for="entire_category"> Entire category </label>
						<button class="btn btn-secondary btn-xs copy-to-btn" title="Copy to" type="button"><i class="fa fa-clone" ></i> Copy</button>
					  
                    </div>
              	</div>
				  <hr>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="category_id">Category</label>
                      <select name="category_id" class="form-control">
                        <option value="">-- Select --</option>
                          <?php foreach($categories as $category) { ?>
                            <option {{if data.category_id == '<?php echo $category->id; ?>'}} selected {{/if}} value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                          <?php } ?>
                      </select>  
                    </div>
                    <div class="form-group col-md-6">
                      <label for="store_website_id">Store Website</label>
                      <select name="store_website_id" class="form-control">
                        <option value="">-- Select --</option>
                          <?php foreach($store_list as $k => $store) { ?>
                            <option {{if data.store_website_id == '<?php echo $k; ?>'}} selected {{/if}} value="<?php echo $k; ?>"><?php echo $store; ?></option>
                          <?php } ?>
                      </select>  
                    </div>
                     {{if data}}
                        <input type="hidden" name="id" value="{{:data.id}}"/>
                     {{/if}}
              </div>
              <div class="form-row">
                  <div class="form-group col-md-6">
                    <div class="input-group">
						<div class="d-flex justify-content-between">
							<label for="meta_title">Meta Title</label>
							<button type="button" class="btn btn-primary btn-sm" id="keyword-search-btn"> <l class="fa fa-search"></i> </button>
						</div>
						<input type="text" name="meta_title" value="{{if data}}{{:data.meta_title}}{{/if}}" class="form-control" id="meta_title" placeholder="Enter Meta title">
					</div>
                  </div>
                  <div class="form-group col-md-6">
					
				  		
                </div>
              </div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="d-flex justify-content-between">
						<label for="meta_keywords">Meta Keywords</label>
						<span id="meta_keywords_count">  </span>
					</div>
					<textarea name="meta_keyword" oninput="auto_grow(this)" class="form-control" id="meta_keywords" placeholder="Enter Keywords"> {{if data}}{{:data.meta_keyword}}{{/if}} </textarea>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="d-flex justify-content-between">
							<label for="meta_keywords_avg">Meta Keywords avg.monthly</label>
						</div>
						<textarea oninput="auto_grow(this)" name="meta_keyword_avg_monthly" class="form-control" id="meta_keyword_avg_monthly" placeholder="Enter Keywords" readonly> {{if data}}{{:data.meta_keyword_avg_monthly}}{{/if}} </textarea>
					</div>
				</div>
              <div class="row">
					<div class="form-group col-md-12">
						<div class="justify-content-end pt-4 input-group">
							<input type="text" value="" class="hide form-control  w-50" id="extra-keyword-search">
							<div class="input-group-append">
								<button type="button" class="hide btn btn-primary input-group-text" id="extra-keyword-search-btn"> <l class="fa fa-search"></i> </button>
							</div>
						</div>
						<div class="pt-3 height-fix suggestList" style="display:none">
							<table class="table table-bordered">
								<thead class="thead-dark">
									<tr>
										<th>Keywords</th>
										<th>Avg. monthly</th>
										<th>Competition</th>
										<th>Translation</th>
									</tr>
								</thead>
								<tbody class="suggestList-table"></tbody>
							</table>
						</div>
					</div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
					<div class="d-flex justify-content-between">
						<label for="meta_description">Meta Description</label>
						<span id="meta_desc_count"></span>
					</div>
                  <textarea name="meta_description" class="form-control" placeholder="Enter meta description">{{if data}}{{:data.meta_description}}{{/if}}</textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="language">Language</label>
                    <select name="language_id" class="form-control website-language-change">
                      <option value="">-- Select --</option>
                        <?php foreach($languages as $k => $language) { ?>
                          <option value="<?php echo $k; ?>"><?php echo $language; ?></option>
                        <?php } ?>
                    </select> 
                </div>
              </div>
           </div>
           <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary submit-store-category-seo">Save changes</button>
           </div>
          </form>
        </div>
</script> 