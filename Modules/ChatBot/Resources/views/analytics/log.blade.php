@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'Dialog | Chatbot')

@section('content')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/dialog-node-editor.css">

<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">Dialog Log | Chatbot</h2>
	</div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb" style="margin-bottom: 10px;">
        <div class="pull-left">
          <div class="form-inline">
              <form method="get">
                  <?php echo Form::text("search",request("search",null),["class" => "form-control", "placeholder" => "Enter input here.."]); ?>      
                  <button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
                      <img src="/images/search.png" style="cursor: default;">
                  </button>
              </form>
          </div>
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-btn-rest">Create</button>
                <button type="button" class="btn btn-secondary ml-3" id="create-dialog-folder-btn-rest">Create Folder</button>
        	</div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="table-responsive-lg">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="15%">User input</th>
            <th width="30%">Bot Replied</th>
            <th width="10%">Matched Intents</th>
            <th width="10%">Matched Keyword</th>
            <th width="30%">Warning</th>
            <th width="5%">Requested At</th>
            <th width="5%">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($eLog as $k => $el) { ?>
              <tr>
                <td><?php echo $el["user_input"]; ?></td>
                <td><?php echo $el["bot_response"]; ?></td>
                <td><span class="label label-default"><?php echo implode(",",$el["intents"]); ?></span></td>
                <td><span class="label label-info"><?php echo implode(",",$el["entities"]); ?></span></td>
                <td><?php echo $el["warning"]; ?></td>
                <td><?php echo $el["requested_at"]; ?></td>
                <td>
                    <a href="javascript:;" data-message="{{ $el['user_input'] }}" class="btn btn-xs btn-image create-dialog" title="Add Dialog"><img src="/images/add.png" alt=""></a>
                </td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
          <?php if(!empty(request("cursor"))) { ?>
            <li class="page-item"><a class="page-link" onclick="return window.history.back();" tabindex="-1">Previous</a></li>
          <?php } ?>
          <?php if(!empty($nextUrl)) { ?>
            <li class="page-item"><a class="page-link" href="<?php echo route('chatbot.analytics.list',['cursor' => $nextUrl] + request()->all()); ?>">Next</a></li>
          <?php } ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
<div class="modal fade" id="leaf-editor-model" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save-dialog-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php include_once(app_path()."/../Modules/ChatBot/Resources/views/dialog/includes/template.php"); ?>
<script src="/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/js/jsrender.min.js"></script>
<script type="text/javascript" src="/js/dialog-build.js"></script>
<script type="text/javascript">

    window.buildDialog = {};
    window.pageLocation = "autoreply";
    
    $(document).on("click", ".create-dialog",function() {

          $("#leaf-editor-model").modal("show");

          var myTmpl = $.templates("#add-dialog-form");
          var question = $(this).data("message");
          var assistantReport = [];
              assistantReport.push({"response" : "" , "condition_sign" : "" , "condition_value" : "" , "condition" : "","id" : 0});
          var json = {
              "create_type": "intents_create",
              "intent"  : {
                  "question" : question,
              },
              "assistant_report" : assistantReport,
              "response" :  "",
              "allSuggestedOptions" : JSON.parse('<?php echo json_encode(\App\ChatbotDialog::allSuggestedOptions()) ?>')
          };
          var html = myTmpl.render({
              "data": json
          });

          window.buildDialog = json;

          $("#leaf-editor-model").find(".modal-body").html(html);
          $("[data-toggle='toggle']").bootstrapToggle('destroy')
          $("[data-toggle='toggle']").bootstrapToggle();
          $(".search-alias").select2({width : "100%"});

          var eleLeaf = $("#leaf-editor-model");
          searchForIntent(eleLeaf);
          searchForCategory(eleLeaf);
          searchForDialog(eleLeaf);
          previousDialog(eleLeaf);
          parentDialog(eleLeaf);

    });

</script>

@endsection
