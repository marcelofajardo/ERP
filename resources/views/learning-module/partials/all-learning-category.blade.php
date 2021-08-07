<div class="modal-header">
        <h4 class="modal-title">All Task Categories</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form action="{{ url('task_category/change-status') }}" method="POST">
        @csrf

        <div class="modal-body">
            <div class="overflow-auto" id="collapse" style="height:400px;overflow-y:scroll;">
                <strong>Categories:</strong>
                <input type="text" id="myInput" placeholder="Search for category.." class="form-control search-category">
                <ul id="myUL" class="padding-left-zero">
                @foreach($categories as $key => $category)
                <li style="list-style-type: none;">
                        <a>
                            <input type="checkbox" name="categoriesList[]" value="{{$category->id}}" {{$category->is_active ? 'checked' : ''}}>
                            <strong>{{$category->title}}</strong>
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary submit-category-status">Submit</button>
        </div>
      </form>
