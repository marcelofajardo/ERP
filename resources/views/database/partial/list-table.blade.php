<table class="table table-bordered page-template-{{ $page }}">
<thead>
  <tr>
    <th>Name</th>
    <th>Size</th>
    <th>Database Name</th>
    <th>Created At</th>
    <th width="5%">Action</th>
  </tr>
</thead>

<tbody>
<?php if (!empty($databaseHis)) {?>
    <?php foreach ($databaseHis as $pam) {?>
        <tr>
          <td>{{ $pam->database_name }}</td>
          <td>{{ $pam->size }}</td>
          <td>{{ $pam->database }}</td>
          <td>{{ $pam->created_at }}</td>
          <td><button class="btn btn-image view-list" data-name="{{ $pam->database_name }}" data-id="{{ $pam->database_id }}"><i class="fa fa-info-circle"></i></button></td>
        </tr>
      <?php }?>
  <?php }?>
</tbody>
<tfoot>
  <tr>
    <td colspan="4"><?php echo $databaseHis->appends(request()->except("page"))->links(); ?></td>
  </tr>
</tfoot>
</table>