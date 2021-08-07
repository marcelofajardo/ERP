<div class="modal-content">
  <form class="update-reference-category-form" action="{{!$is_auto_fix ? '/category/new-references/save-category' :'/category/new-references/save-category?is_auto_fix=true' }}" method="post">
     {!! csrf_field() !!}
     <div class="modal-header">
        <h5 class="modal-title"> {{!$is_auto_fix  ? 'List Of updated categories' :'Show auto fix categories'}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
     </div>
     <div class="modal-body">
          <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="2%">Category</th>
                        <th width="2%">Update to</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($links as $link)
                        <tr>
                          <td>{{ $link['from'] }}</td>
                          <td>
                            <?php
                                  echo "<select class='form-control select2' name='updated_category[".$link["from_id"]."]'>";
                                  $categories = \App\Category::attr(["name" => "updated_category[".$link["from_id"]."]", "class" => "form-control select2"])->renderAsArray();
                                  if(!empty($categories)) {
                                    foreach($categories as $cat) {
                                      $selected = ($cat['id'] == $link["to"]) ? "selected='selected'" : "";
                                      echo  "<option ".$selected." value='".$cat['id']."'>".$cat['title']."</option>";
                                      if(!empty($cat['child'])) {
                                        foreach($cat['child'] as $child) {
                                          $selected = ($child['id'] == $link["to"]) ? "selected='selected'" : "";
                                          echo  "<option ".$selected." value='".$child['id']."'>".$cat['title']." > ".$child['title']."</option>";
                                          if(!empty($child['child'])) {
                                            foreach($child['child'] as $mchild) {
                                              $selected = ($mchild['id'] == $link["to"]) ? "selected='selected'" : "";
                                              echo  "<option ".$selected." value='".$mchild['id']."'>".$cat['title']." > ".$child['title']." > ".$mchild['title']."</option>";
                                              if(!empty($mchild['child'])) {
                                                foreach($mchild['child'] as $pchild) {
                                                  $selected = ($pchild['id'] == $link["to"]) ? "selected='selected'" : "";
                                                  echo  "<option ".$selected." value='".$pchild['id']."'>".$cat['title']." > ".$child['title']." > ".$mchild['title']." > ".$pchild['title']."</option>";
                                                }
                                              } 
                                            }
                                          }
                                        }
                                      }
                                    }
                                  }
                                  echo "</select>";
                                ?>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
          </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-default">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
   </form>
</div>