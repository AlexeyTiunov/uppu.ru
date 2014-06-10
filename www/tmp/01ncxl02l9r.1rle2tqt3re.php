<h3><?php echo $POST['title']; ?></h3>
Published: <?php echo $POST['timestamp']; ?>
<p>Size: <?php echo $POST['size']; ?> b</p>
<?php if (isset($img)): ?>
<img src="/image/<?php echo $POST['id']; ?>">
<?php endif; ?>
<p><a href="<?php echo $path; ?>" class="btn btn-download" download><i class="icon-download icon-black"></i> Скачать</a></p>
<a href="/" class="btn btn-home"><i class="icon-home icon-black"></i> На главную страницу</a>