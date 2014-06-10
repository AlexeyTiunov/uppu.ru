<table>
	<thead>
		<tr>
			<td>Name</td>
			<td>Date</td>
			<td>Size</td>
			<td>Download</td>
		</tr>
	</thead>	
	<tbody>
	<?php foreach (($list?:array()) as $file): ?>
		<tr>
			<td><?php echo $file['title']; ?></td>
			<td><?php echo $file['timestamp']; ?></td>
			<td><?php echo $file['size']; ?></td>
			<td><a href="<?php echo $UPLOADS; ?><?php echo $file['title']; ?>" download>Скачать</a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>