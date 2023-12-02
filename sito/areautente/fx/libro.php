<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">
<head>
</head>
<body>

<?php
	$isbn = $_GET[ 'ISBN' ];
	if(file_exists("../../imgs/libri/".$isbn.".jpg")) $limg="../../imgs/libri/".$isbn.".jpg";
	else $limg="../../imgs/libri/barcode.png";
?>
<p style="text-align:center;">
<img src="<?php echo $limg; ?>" width="300px"><br>
<small>ISBN: <?php echo $isbn; ?></small></p>
</body>
</html>