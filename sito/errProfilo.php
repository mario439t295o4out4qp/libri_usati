<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<?php
  include("elements/head.php"); 
  require_once "lib/funzioni.php"
?>
<style>
 .fa-arrow-left:hover {
    color: #ff8080 !important;
    cursor: pointer; }

</style>
<body>

	<?php include("elements/nav.php"); ?>

	<div class="container-fluid visoreInt" style="background-image: url('imgs/visore-02.jpg')">
		<div class="titoli hidden-xs">
			<h2>#LibriUsati</h2>
			<h3>Mercatino dei Libri Usati di Crema</h3>
		</div>
	</div>

	<?php include("elements/barra.php"); ?>

	<div class="container-fluid corpo">
		<div class="container">
			<h1>Errore profilo</h1>
<?php
	$motivo = $_GET[ 'motivo' ];
	if (isset( $_GET[ 'mail' ])) $mail = $_GET[ 'mail' ]; else $mail="";
	if (isset( $_GET[ 'nick' ])) $nick = $_GET[ 'nick' ]; else $nick="";
	if (isset( $_GET[ 'redirect' ])) $redirect = $_GET[ 'redirect' ]; else $redirect="/areautente/";
	if ( $redirect != "/areautente/") $gestione=1; else $gestione=0;
	if ($gestione) $msg2 = "Torna ad area utenti <i class=\"fa fa-arrow-left\" onclick=\"javascript:history.go(-1)\"></i>";

	if ( $motivo == "exist" ) {
		$msg = "Il nickname e la mail specificata esistono già nel database utenti.<br>";
		if (! $gestione) {
			$msg2 = "Se sei il possessore di <b>". $mail . "</b><br>";
			$msg2.= "puoi attivare la funzione di recupero password dall'<strong><a target=\"blank\" href=\"/areautente/\">Area Utente</a></strong>";
		}
		echo "<p>" . $msg . $msg2 . "</p>"; 
	}
	else if ( $motivo == "standby" ) {
		$msg = "Esiste un profilo in sospeso per <br>";
		$msg.= "<b>$nick</b> mail <b>$mail</b><br>";
		if (! $gestione) {
			$msg2 = "Se sei tu cerca nella posta in arrivo (o verifica se è stata messa per sbaglio nello Spam)<br>";
			$msg2.= "<strong>Una volta confermata la registrazione esegui il login all'<a target=\"blank\" href=\"/areautente/\">Area Utente</a></strong>";
		}
		echo "<p>" . $msg . $msg2 . "</p>"; 
	}
	else if ( $motivo == "duplicate" ) {
		$msg = "Il nickname <b>$nick</b> esiste già associato ad un'altra mail <br>";
		if (! $gestione) {
			$msg2 = "Inserisci la mail corretta o prova con un alias<br>";
			$msg2 = "<strong><a target=\"blank\" href=\"registrati.php\">Torna</a></strong>";
		}
		echo "<p>" . $msg . $msg2 . "</p>"; 
	} 
	else if ( $motivo == "found" ) {
		$msg = "L'utente esiste già nella anagrafica del sito <br>	";
		echo "<p>" . $msg . $msg2 . "</p>"; 
	}
	else if ( $motivo == "nodata" ) {
		$msg = "Inserire almeno nome e cognome <br>";
		echo "<p>" . $msg . $msg2 . "</p>"; 
	} 
?>
		</div>
	</div>

	<div class="container-fluid slider-photo">
		<div id="photogallery" class="col-md-12 owl-carousel owl-theme">
			<div class="item">
				<img src="imgs/photo-01.jpg">
			</div>
			<div class="item">
				<img src="imgs/photo-02.jpg">
			</div>
			<div class="item">
				<img src="imgs/photo-03.jpg">
			</div>
		</div>
	</div>

	<div class="container-fluid mappa">
		<div id="map">
		</div>
	</div>

	<div class="container-fluid sostenitori dottedSlider">
		<div class="container">
			<div id="sostenitori" class="col-md-12 owl-carousel owl-theme">
				<div class="item">
					<img src="imgs/sost-01.png">
				</div>
				<div class="item">
					<img src="imgs/sost-02.png">
				</div>
				<div class="item">
					<img src="imgs/sost-03.png">
				</div>
			</div>
		</div>
	</div>

<?php include("elements/footer.php"); ?>

</body>
</html>