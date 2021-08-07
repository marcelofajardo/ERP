<table class="table table-bordered page-template-{{ $page }}">
<thead>
  <tr>
    <th>Name</th>
    <th>Size</th>
    <th>Database Name</th>
    <th>Created At</th>
  </tr>
</thead>
<tbody>
<?php if (!empty($databaseHis)) {?>
    <?php foreach ($databaseHis as $pam) {?>
        <tr>
          <td>{{ $pam->database_name }}</td>
          <td>{{ $pam->size }}</td>
          <td>{{ $pam->database_id }}</td>
          <td>{{ $pam->created_at }}</td>
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