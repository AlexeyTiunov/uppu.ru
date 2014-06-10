<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?php echo $BASE.'/'.$UI; ?>"></base>
	<title><?php echo $site; ?></title>
	<link href="/ui/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script>
		function validate(form){
			if(form.file.value == ""){
				alert("Не выбран файл");
				return false;
			}
		}
	</script>
</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<h1><?php echo $page_head; ?></h1>
		</div>
