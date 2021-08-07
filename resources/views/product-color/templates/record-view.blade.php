<?php if(!empty($productCategory)) {  ?>
	<?php foreach($productCategory as $pc) { ?>
		<tr>
			<td><?php echo $pc->id; ?></td>
			<td><?php echo $pc->new_cat_name; ?></td>
			<td><?php echo $pc->old_cat_name; ?></td>
			<td><?php echo $pc->user_name; ?></td>
			<td><?php echo $pc->created_at; ?></td>
		</tr>
	<?php } ?>
<?php } ?>


