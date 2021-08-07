    @php
    $isAdmin = auth()->user()->isAdmin();
    $isHod = auth()->user()->hasRole('HOD of CRM');
    $hasSiteDevelopment = auth()->user()->hasRole('Site-development');
    $userId = auth()->user()->id;
    $pagrank = $categories->perPage() * ($categories->currentPage()- 1) + 1;
    @endphp
    @foreach($categories as $key => $category)
    <?php
    $site = $category->getDevelopment($category->id, $website->id);
    if ($isAdmin || $hasSiteDevelopment || ($site && $site->developer_id == $userId)) {
    ?>
        <tr>
            <td>
                {{ $pagrank++  }}
            </td>
            <td>
{{--                <br>--}}
                {{ $category->title }}
                <br>
                <button onclick="editCategory({{$category->id}})" style="background-color: transparent;border: 0;margin-top:5px;" class="pl-0"><i class="fa fa-edit"></i></button>


                <!-- <input class="fa-ignore-category" type="checkbox" data-onstyle="secondary" data-category-id="{{$category->id}}" data-site-id="@if($website) {{ $website->id }} @endif" <?php echo (request('status') == 'ignored') ? "checked" : "" ?>
                data-on="Allow" data-off="Disallow"
                data-toggle="toggle" data-width="90"> -->
                @if(request('status') == 'ignored')
                <button style="padding:0px;" type="button" class="btn btn-image fa-ignore-category pl-0" data-category-id="{{$category->id}}" data-site-id="@if($website) {{ $website->id }} @endif" data-status="1">
                    <i class="fa fa-ban" aria-hidden="true" style="color:red;"></i>
                </button>
                @else
                <button style="padding:0px;" type="button" class="btn btn-image fa-ignore-category pl-0" data-category-id="{{$category->id}}" data-site-id="@if($website) {{ $website->id }} @endif" data-status="0">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                </button>
                @endif


            </td>
            <td>
                <input type="hidden" id="website_id" value="@if($website) {{ $website->id }} @endif">
                <input style="margin-top: 5px;" type="text" class="form-control save-item" data-category="{{ $category->id }}" data-type="title" value="@if($site){{ $site->title }}@endif" data-site="@if($site){{ $site->id }}@endif">
                <form>
                    <label class="radio-inline">
                        <input class="save-artwork-status" type="radio" name="artwork_status" data-category="{{ $category->id }}" value="Yes" data-type="artwork_status" data-site="@if($site){{ $site->id }}@endif" @if($site){{ $site->artwork_status == 'Yes' ? 'checked' : '' }}@endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input class="save-artwork-status" type="radio" name="artwork_status" data-category="{{ $category->id }}" value="No" data-type="artwork_status" data-site="@if($site){{ $site->id }}@endif" @if($site){{ $site->artwork_status == 'No' ? 'checked' : '' }}@endif />No
                    </label>
                    <label class="radio-inline">
                        <input class="save-artwork-status" type="radio" name="artwork_status" data-category="{{ $category->id }}" value="Done" data-type="artwork_status" data-site="@if($site){{ $site->id }}@endif" @if($site){{ $site->artwork_status == 'Done' ? 'checked' : '' }}@endif />Done
                    </label>
                </form>
            </td>
            <?php /* <td><input type="text" class="form-control save-item" data-category="{{ $category->id }}" data-type="description" value="@if($site){{ $site->description }}@endif" data-site="@if($site){{ $site->id }}@endif"></td> */ ?>
            <td>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <select style="margin-top: 5px;" class="form-control save-item-select developer assign-to select2" data-category="{{ $category->id }}" data-type="developer" data-site="@if($site){{ $site->id }}@endif" name="developer_id" id="user-@if($site){{ $site->id }}@endif">
                            <option value="">Select Developer</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($site && $site->developer_id == $user->id) selected @endif >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-1">
                        <select style="margin-top: 5px;" name="designer_id" class="form-control save-item-select designer assign-to select2" data-category="{{ $category->id }}" data-type="designer_id" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                            <option value="">Select Designer</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($site && $site->designer_id == $user->id) selected @endif >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <select style="margin-top: 5px;" name="html_designer" class="form-control save-item-select html assign-to select2" data-category="{{ $category->id }}" data-type="html_designer" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                            <option value="">Select Html</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($site && $site->html_designer == $user->id) selected @endif >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-1">
                        <select style="margin-top: 5px;" name="tester_id" class="form-control save-item-select html assign-to select2" data-category="{{ $category->id }}" data-type="tester_id" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                            <option value="">Select Tester</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($site && $site->tester_id == $user->id) selected @endif >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
{{--                    @if($site)--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="chat_messages expand-row table-hover-cell d-inline">--}}
{{--                            <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ $isAdmin }}" data-is_hod_crm="{{ $isHod }}" data-object="site_development" data-id="{{$site->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>--}}
{{--                            <span class="chat-mini-container"> @if($site->lastChat) {{ $site->lastChat->message }} @endif</span>--}}
{{--                            <span class="chat-full-container hidden"></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @endif--}}

                    <div class="col-md-12 mb-3">
                        <?php
                        $MsgPreview = '# ';
                        if ($website) {
                            $MsgPreview = $website->website;
                        }
                        if ($site) {
                            $MsgPreview = $MsgPreview . ' ' . $site->title;
                        }
                        ?>
                        <div class="col-md-10 p-0">
                            <input style="margin-top: 5px;" type="text" class="form-control quick-message-field" name="message" placeholder="Message" value="" id="message-@if($site){{ $site->id }}@endif">
                        </div>
                        <div style="margin-top: 7px;" class="col-md-2 p-0">
                            <button class="btn pr-0 btn-xs btn-image send-message-site" data-prefix="{{$MsgPreview}}" data-category="{{ $category->id }}" data-id="@if($site){{ $site->id }}@endif"><img src="/images/filled-sent.png" /></button>
                            @if($site)
                            <div  class="chat_messages expand-row table-hover-cell d-inline">
                                <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ $isAdmin }}" data-is_hod_crm="{{ $isHod }}" data-object="site_development" data-id="{{$site->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>
                                <span class="chat-full-container hidden"></span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        @if($site)
                            <!-- START - Purpose : Show / Hide Chat & Remarks , Add Last Remarks - #DEVTASK-19918 -->
                            @if($site->lastChat) Chat = @endif
                            <div class="justify-content-between expand-row-msg-chat" data-id="@if($site->lastChat){{$site->lastChat->id}}@endif">
                                <span class="td-full-chat-container-@if($site->lastChat){{$site->lastChat->id}}@endif pl-1"> @if($site->lastChat) {{ str_limit($site->lastChat->message, 100,'...') }} @endif</span>
                            </div>
                            <div class="expand-row-msg-chat" data-id="@if($site->lastChat){{$site->lastChat->id}}@endif">
                                <span class="td-full-chat-container-@if($site->lastChat){{$site->lastChat->id}}@endif hidden"> @if($site->lastChat) {{ $site->lastChat->message }} @endif</span>
                            </div>
                            <br/>
                            @if($site->lastRemark) Remarks = @endif
                            <div class="justify-content-between expand-row-msg" data-id="@if($site->lastRemark){{$site->lastRemark->id}}@endif">
                                <span class="td-full-container-@if($site->lastRemark){{$site->lastRemark->id}}@endif" > @if($site->lastRemark)  {{ str_limit($site->lastRemark->remarks, 100, '...') }} @endif</span>
                            </div>
                            <div class="expand-row-msg" data-id="@if($site->lastRemark){{$site->lastRemark->id}}@endif">
                                <span class="td-full-container-@if($site->lastRemark){{$site->lastRemark->id}}@endif hidden">
                                    @if($site->lastRemark) {{ $site->lastRemark->remarks }} @endif
                                </span>
                            </div>
                            <!-- END - #DEVTASK-19918 -->
                        @endif
                    </div>

                </div>
                <div  class="d-flex mt-1">
                <span  class="hidden_row_{{ $category->id  }}" >
                    <input type="checkbox" id="developer_{{$category->id}}" name="developer" value="developer">
                    &nbsp;<label for="developer">Developer</label>&nbsp;&nbsp;
                    <input type="checkbox" id="designer_{{$category->id}}" name="designer" value="designer">
                    &nbsp;<label for="designer">Designer</label>&nbsp;&nbsp;
                    <input type="checkbox" id="html_{{$category->id}}" name="html" value="html">
                    &nbsp;<label for="html">Html</label>&nbsp;&nbsp;
                    <input type="checkbox" id="html_{{$category->id}}" name="tester" value="tester">
                    &nbsp;<label for="html">Tester</label>
                </span>
                </div>
            </td>
            <td>
                <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-upload pd-5">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                </button>
                @if($site)
                <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-file-list pd-5">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </button>
                <button type="button" data-site-id="@if($site){{ $site->id }}@endif" data-site-category-id="{{ $category->id }}" data-store-website-id="@if($website) {{ $website->id }} @endif" class="btn btn-store-development-remark pd-5">
                    <i class="fa fa-comment" aria-hidden="true"></i>
                </button>
                <button type="button" title="Artwork status history" class="btn artwork-history-btn pd-5" data-id="@if($site){{ $site->id }}@endif">
                    <i class="fa fa-history" aria-hidden="true"></i>
                </button>
                @endif
                <button type="button" class="btn preview-img-btn pd-5" data-id="@if($site){{ $site->id }}@endif">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </button>
                @if(Auth::user()->isAdmin())
                @php
                    $websitenamestr = ($website) ? $website->title : "";
                @endphp
                <button style="padding:3px;" title="create quick task" type="button" class="btn btn-image d-inline create-quick-task pd-5" data-id="@if($site){{ $site->id }}@endif" data-title="@if($site){{ $websitenamestr.' '.$site->title }}@endif"><img style="width:12px !important;" src="/images/add.png" /></button>
                <button style="padding-left: 0;float: right;padding-right:0px;" type="button" class="btn btn-image d-inline count-dev-customer-tasks" title="Show task history" data-id="@if($site){{ $site->id }}@endif"><i class="fa fa-info-circle"></i></button>
                @endif
                <button class="btn btn-image d-inline create-quick-task pd-5">
                    <span>
                <?php $status = ($site) ? $site->status : 0; ?>
                        @if($status==3)
                            <i class="fa fa-ban save-status" data-text="4" data-site="{{ ($site) ? $site->id : '' }}" data-category="{{$category->id}}"  data-type="status" aria-hidden="true" style="color:red;" title="Deactivate"></i>
                        @elseif($status==4 || $status==0 )
                            <i class="fa fa-ban save-status" data-text="3" data-site="{{ ($site) ? $site->id : '' }}" data-category="{{$category->id}}"  data-type="status" aria-hidden="true" style="color:black;" title="Activate"></i>
                        @endif
                </span>
                </button>
                <?php /* <button style="padding:3px;" type="button" class="btn btn-image d-inline toggle-class pd-5" data-id="{{ $category->id }}"><img width="2px;" src="/images/forward.png" /></button> */ ?>

                <?php echo Form::select("status", ["" => "-- Select --"] + $allStatus, ($site) ? $site->status : 0, [
                    "class" => "form-control save-item-select width-auto",
                    "data-category" => $category->id,
                    "data-type" => "status",
                    "data-site" => ($site) ? $site->id : ""
                ])  ?>

            </td>
        </tr>
        <?php /* <tr class="hidden_row_{{ $category->id  }} dis-none" data-eleid="{{ $category->id }}">
            <td colspan="2">
                <?php  echo Form::select("status", ["" => "-- Select --"] + $allStatus, ($site) ? $site->status : 0, [
                    "class" => "form-control save-item-select",
                    "data-category" => $category->id,
                    "data-type" => "status",
                    "data-site" => ($site) ? $site->id : ""
                ])  ?>

            </td>
            <?php  <td colspan="2">
            <select style="margin-top: 5px;" class="form-control save-item-select developer" data-category="{{ $category->id }}" data-type="developer" data-site="@if($site){{ $site->id }}@endif" name="developer_id" id="user-@if($site){{ $site->id }}@endif">
    				<option value="">Select Developer</option>
    				@foreach($users as $user)
    					<option value="{{ $user->id }}" @if($site && $site->developer_id == $user->id) selected @endif >{{ $user->name }}</option>
    				@endforeach
    			</select>
            <select style="margin-top: 5px;" name="designer_id" class="form-control save-item-select designer" data-category="{{ $category->id }}" data-type="designer_id" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                    <option value="">Select Designer</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"@if($site && $site->designer_id == $user->id) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
                <select style="margin-top: 5px;" name="html_designer" class="form-control save-item-select html" data-category="{{ $category->id }}" data-type="html_designer" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                    <option value="">Select Html</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($site && $site->html_designer == $user->id) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
                <select style="margin-top: 5px;" name="tester_id" class="form-control save-item-select html" data-category="{{ $category->id }}" data-type="tester_id" data-site="@if($site) {{ $site->id }} @endif" id="user-@if($site){{ $site->id }}@endif">
                    <option value="">Select Tester</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($site && $site->tester_id == $user->id) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
            </td>
            <td></td>
            <td></td>
        </tr> */ ?>
    <?php } ?>
    @include("storewebsite::site-development.partials.edit-modal")
    @endforeach