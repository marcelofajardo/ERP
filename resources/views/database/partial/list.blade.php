<table class="table table-bordered page-template-{{ $page }}">
<thead>
  <tr>
    <th>Name</th>
    <th>Size</th>
    <th>Created At</th>
  </tr>
</thead>
<tbody>
<?php if (!empty($databaseHis)) {?>
    <?php foreach ($databaseHis as $pam) {?>
        <tr>
          <td><a href="{{ action('DatabaseTableController@index', $pam->id) }}">{{ $pam->database_name }}</td>
          <td>{{ $pam->size }}</td>
          <td>{{ $pam->created_at }}</td>
        </tr>
      <?php }?>
  <?php }?>
</tbody>
<tfoot>
  <tr>
    <td colspan="3"><?php echo $databaseHis->appends(request()->except("page"))->links(); ?></td>
  </tr>
</tfoot>
</table>