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
			<h3>Prenotazione libri</h3>
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
		<form action="fx/login.php" method="post">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><div class="hidden-xs">Utente</div><i class="fa fa-user hidden-sm hidden-md hidden-lg" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="nickname" aria-describedby="basic-addon1">
			</div>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><div class="hidden-xs">Password</div><i class="fa fa-key hidden-sm hidden-md hidden-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="password" aria-describedby="basic-addon1">
			</div>
			<input type="submit" class="btn btn-default" value="Accedi">
		</form><br>
		<p><strong>&Egrave; il tuo primo accesso?</strong> <br>Controlla la tua casella di posta, ti abbiamo inviato il link di conferma e le credenziali per il login.
		Ricordati di <strong>confermare </strong>via mail la registrazione per attivare il profilo.</p>
		<p><strong>Password dimenticata? </strong>Invia richiesta di <strong><a href="pwdforgot.php">cambio password</a></strong>
	</div>
<?php
	}
?>
	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>