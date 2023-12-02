<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<?php 
	include("elements/head.php"); 
	require_once "lib/funzioni.php"
?>

<body>

	<?php include("elements/nav.php"); ?>

	<div class="container-fluid visoreInt" style="background-image: url('imgs/visore-02.jpg')">
		<div class="titoli hidden-xs">
			<h2>#LibriUsati</h2>
			<h3>Mercatino dei Libri Usati di Crema</h3>
		</div>
		<div class="barra1 visible-xs">
			<a href="#" class="hvr-sweep-to-top">Visualizza il catalogo</a>
		</div>
	</div>

	<?php include("elements/barra.php"); ?>

	<div class="container-fluid corpo">
		<div class="container">
			<h1>Registrati</h1>
<?php
	if ( isOnline() &&  !registrazioni_aperte() ) {
?>
			<p>&nbsp;Registrazioni al sito momentaneamente chiuse. Saranno attive <?php echo registrazioni_aperte_str()?></p>
<?php
	}
	else {
?>		
			<p> 
				Se vuoi prenotare i libri per l'anno scolastico <?php echo getAnnoSc()?>, ma non hai ancora il tuo profilo online, puoi registrarti compilando il seguente form e procedere
				con la prenotazione.
				Riceverai una mail di conferma della registrazione, rispondendo alla quale ti verrà attivato il profilo.<br><br>
				<strong>Hai già portato dei libri al mercatino quest'anno?</strong> Se ci hai lasciato la tua mail controlla la tua casella di posta, ti abbiamo già inviato 
				le credenziali per il login! Se non hai ancora ricevuto nessuna mail (e se non è stata messa per sbaglio nello Spam) invia un messaggio attraveso 
				i moduli "Contattaci" che trovi nella Home del sito e faremo in modo di risolvere il problema.<br><br>				
				<strong>Una volta confermata per mail la registrazione esegui il login all'<a target="blank" href="/areautente/">Area Utente</a></strong><br><br>				
			</p>
			
			
			<form id="formUtente" method="post" class="utente" action="gestionale/fx/inserisci.php">
				<input type="none" name="tabella" value="utenti" hidden>
				<input type="none" name="campi" value="nome, cognome, telefono, mail, scuola, data" hidden>
				<input type="none" name="redirect" value="/areautente/" hidden>
				<input type="none" name="data" value="<?php echo date(" Y-m-d H:i:s ");?>" hidden>
				<input type="text" class="form-control" name="nome" placeholder="Nome" aria-describedby="basic-addon1" required>
				<input type="text" class="form-control" name="cognome" placeholder="Cognome" aria-describedby="basic-addon1" required>
				<input type="tel" class="form-control" name="telefono" placeholder="Telefono" aria-describedby="basic-addon1">
				<input type="mail" class="form-control" name="mail" placeholder="E-Mail" aria-describedby="basic-addon1" required>
				<select placeholder="Scuola" name="scuola">
					<option value="nulla" selected disabled>Seleziona la tua Scuola</option>
					<option>I.I.S. Galileo Galilei</option>
					<option>I.I.S. Sraffa-Marazzi</option>
					<option>I.I.S. Luca Pacioli</option>
				</select>
				<div class="form-group">
					<input class="magic-checkbox" type="checkbox" name="PrivacyBox" id="check" value="y" required>
					<label for="check">Spunta la casella come presa visione della <a href="./">Privacy Policy</a></label>
				</div>
				<input class="form-control invio" type="submit" value="INVIA">
			</form>
<?php
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
				<div class="item">
					<img src="imgs/sost-05.png">
				</div>
				<div class="item">
					<img src="imgs/sost-04.png">
				</div>
			</div>
		</div>
	</div>

<?php include("elements/footer.php"); ?>

</body>
</html>