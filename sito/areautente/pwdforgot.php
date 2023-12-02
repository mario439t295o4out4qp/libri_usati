<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">
<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body id="index">
<?php 
	require_once "../lib/funzioni.php";

	if (isOnline() && isDebug() ) {
?>
		<div class="container-fluid visore">
		<div class="titoli hidden-xs hidden-sm hidden-md">
			<h2>#LibriUsati</h2>
			<h3>Mercatino dei Libri Usati di Crema</h3>
			<h3>Servizio disponibile a presto...</h3>
		</div>
		</div>
<?php
	}
	else {
		chkTest();
?>		

	<div class="login">
		<img src="imgs/logo.png">
		<form action="../gestionale/fx/reinvia.php" method="post">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><div class="hidden-xs">Mail</div><i class="fa fa-user hidden-sm hidden-md hidden-lg" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="mail" aria-describedby="basic-addon1">
			</div>
			<input type="submit" class="btn btn-default" value="Reset password">
		</form><br>
		<p>Un messaggio con la procedura di cambio verrà spedita alla casella di posta indicata.</p>
	</div>
<?php
	}
?>
	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>