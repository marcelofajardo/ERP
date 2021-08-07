<div id="groupCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Keyword Group</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Name:</strong>
                       <input type="text" name="name" id="keywordname" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Existing Group:</strong>
                        <select class="form-control selectpicker" id="keywordGroup" data-live-search="true">
                            <option value="">Select Keyword Group</option>    
                            @foreach($groupKeywords as $groupKeyword)
                                <option value="{{ $groupKeyword->id }}">{{ $groupKeyword->keyword }}</option>
                            @endforeach
                       </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary" onclick="createGroup()">Create</button>
                </div>
            </form>
        </div>

    </div>
</div>


<div id="groupPhraseCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content1-->
        <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Phrase Group</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Select or create intent Group:</strong>
                         <select class="form-control existing-intent select2" id="phraseGroup">
                            <option value="">Select Intent Group</option>
                                @foreach($groupPhrases as $groupPhrase)
                                    <option data-suggested-reply="{{ $groupPhrase->suggested_reply }}" value="{{ $groupPhrase->id }}">{{ $groupPhrase->value }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="value">Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Select</option>
                        @foreach($allCategoryList as $cat)
                            <option value="{{$cat['id']}}">{{$cat['text']}}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="form-group">
                        <strong>Reply:</strong>
                        <textarea name="reply" id="autochat-reply" class="form-control"></textarea>
                    </div>
                    <div class="form-group list-of-reply">

                    </div>
                    <div class="form-group">
                        <label for="value">Push to</label>
                        <select name="erp_or_watson" id="erp_or_watson" class="form-control">
                            <option value="watson">Watson</option>
                            <option value="erp">ERP</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="value">Auto Approve</label>
                        <select id="auto_approve" name="auto_approve" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary" onclick="createGroupPhrase()">Create</button>
                </div>
            </form>
        </div>

    </div>
</div>


