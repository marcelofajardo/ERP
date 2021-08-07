
            <form name="add-content" style="padding:10px;" action="{{ route('add_content') }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" value="{{ isset($data->id) ? $data->id : 0  }}" name="id">
                    <input type="hidden" value="{{ isset($data->mailinglist_templates_id) ? $data->mailinglist_templates_id : $mail_list_id  }}" name="mail_list_id" id="mail_list_id">

                        <div class="form-group">
                            <label for="exampleInputName">Updated By</label>
                                <input  class="form-control"  placeholder="Content"  value="{{ isset($data->addedBy->name) ? $data->addedBy->name : auth()->user()->name }}" disabled>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="form_subject">Updated On</label>
                            <input type="text" class="form-control" name="date"  value="{{ isset($data->updated_at) ?  $data->updated_at : date('Y-m-d') }}" disabled />
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleInputName">Content</label>
                                <textarea  class="form-control" name="content" placeholder="Content" required>{{ isset($data->content) ? $data->content : '' }}</textarea>
                            <span class="text-danger"></span>
                        </div>
						
                        <button id="store" type="submit" class="btn btn-primary">Submit</button>
                    </form>