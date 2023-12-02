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
		<br>
		<p class="text-center">Mail con nuove credenziali inviata.<br> Controlla la tua posta in arrivo.</p>
		<p class="text-center"><strong>Poi accedi di nuovo all'<a href="<?php echo getSiteUtente() ?>">area utente</a>
	</div>
<?php
	}
?>
	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>