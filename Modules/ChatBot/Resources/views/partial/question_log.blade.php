{{--  <div class="spinner-border" ></div>  --}}
<style>
    .question-log-dialog .modal-dialog {
        max-width: 80%;
        width:100%
    }

    .question-log-dialog .modal-dialog .modal-content {
        padding : 20px 20px 10px;
    }
</style>
<div class="modal question-log-dialog" id="question-log-dialog" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div><h3>Chatbot Error Log</h3></div>
        <table id="dtBasicExample question_log_table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
            <thead>
              <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Message</th>
                <th class="th-sm">Status</th>
                <th class="th-sm">Type</th>
                <th class="th-sm">Action</th>
              </tr>
            </thead>
            <tbody id="question_log_table_body">
              
            </tbody>
          </table>
      </div>
    </div>
  </div>
  