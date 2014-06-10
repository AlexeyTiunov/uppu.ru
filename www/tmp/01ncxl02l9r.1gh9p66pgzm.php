<a href="/" class="btn btn-home"><i class="icon-home icon-black"></i> На главную страницу</a>
<table width="600" cellpadding="5" class="table table-hover table-bordered">
	<thead>
		<tr>
			<th scope="col">Name</td>
			<th scope="col">Date</td>
			<th scope="col">Size</td>
			<th scope="col">Download</td>
		</tr>
	</thead>	
	<tbody>
	<?php foreach (($list?:array()) as $file): ?>
		<tr>
			<td><?php echo $file['title']; ?></td>
			<td><?php echo $file['timestamp']; ?></td>
			<td><?php echo $file['size']; ?></td>
			<td><a href="/<?php echo $UPLOADS; ?><?php echo $file['title']; ?>" class="btn btn-download" download><i class="icon-download icon-black"></i> Скачать</a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>