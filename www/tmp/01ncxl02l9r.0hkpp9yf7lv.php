<form enctype="multipart/form-data" method="post" action="<?php echo $PARAMS[0]; ?>" onSubmit="return validate(this)" id="inputForm">
	<p><input type="file" name="file" class="btn btn-choose"></p>
	<input type="submit" value="Submit" class="btn btn-submit">
</form>	
<p><a href="/fileslist" class="btn btn-list"><i class="icon-th-list icon-black"></i> Last uploaded files</a></p>