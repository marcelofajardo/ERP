<div class="col-md-12">
  <div><h3>Chatbot Reply</h3></div>
  <table id="dtBasicExample question_log_table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" style="table-layout:fixed;">
      <thead>
        <tr>
          <th class="th-sm">StoreWebsite</th>
          <th class="th-sm">Message</th>
        </tr>
      </thead>
      <tbody>
          @foreach($reply as $r)
          <tr>
              <td>{{($r->storeWebsite) ? $r->storeWebsite->title : ""}}</td>
              <td>{{$r->suggested_reply}}</td>
          </tr>
          @endforeach
      </tbody>
    </table>
</div>  