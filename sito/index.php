<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<?php include("elements/head.php"); ?>

<body>
<?php 
	require_once "lib/funzioni.php";
	chkTest();
?>
	<?php include("elements/nav.php"); ?>

	<div class="container-fluid visore">
		<div id="novita" class="owl-carousel owl-theme">
			<div class="item">
				<h3>Portaci i tuoi libri!</h3>
				<!--p>Non serve disdire la vacanza: scegli tra le date sotto riportate per consegnare i libri.</p-->
				<p>SERVIZIO DI RITIRO EXTRA: il giorno 23 agosto sarà disponibile una ulteriore postazione per il ritiro dei libri</p>
				<a href="servizi.php" class="hvr-sweep-to-top">Scopri di più <i class="fa fa-arrow-right hidden-xs" aria-hidden="true"></i></a>
			</div>
			<div class="item">
				<h3>Prenota i libri per l'Anno 2023/24!</h3>
				<p>Le prenotazioni sono aperte dal 5 al 20 Agosto, non fartele scappare!</p>
				<a href="servizi.php" class="hvr-sweep-to-top">Scopri di più <i class="fa fa-arrow-right hidden-xs" aria-hidden="true"></i></a>
			</div>
			<div class="item">
				<h3>Verifica la disponibilità</h3>
				<p>Dal <?php echo disponibili_str() ?> controlla quali libri sono ancora disponibili</p>
				<a href="magazzino.php" class="hvr-sweep-to-top">Scopri di più <i class="fa fa-arrow-right hidden-xs" aria-hidden="true"></i></a>
			</div>
		</div>
		<div class="titoli hidden-xs hidden-sm hidden-md">
			<h2>#LibriUsati</h2>
			<h3>Mercatino dei Libri Usati di Crema</h3>
		</div>
	</div>

	<?php include("elements/barra.php"); ?>

	<div class="container-fluid corpo">
		
		<div class="container slider">
			<div id="news" class="col-md-12 owl-carousel owl-theme">
				<div class="item">
					<img src="imgs/news_01.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_02.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_03.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_04.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_05.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_06.jpg">
				</div>
				<div class="item">
					<img src="imgs/news_07.jpg">
				</div>

			</div>
		</div>

		<div class="container slogan">
			“I libri si rispettano usandoli, non lasciandoli stare”<br>- Umberto Eco -
		</div>

		<div class="container dottedSlider hidden-xs hidden-sm">
			<div id="scuole" class="owl-carousel owl-theme">
				<div class="item">
					<div class="col-md-4 col-sm-6 col-xs-12 img text-center">
						<img src="imgs/galilei.png">
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12 info">
						<p>I.I.S. Galileo Galilei</p>
						<address>Tel. +39 0373 256939</address>
						<a href="https://www.galileicrema.edu.it" target="blank" title="Sito Internet"><i class="fa fa-globe" aria-hidden="true"></i></a>
						<a href="/file/galilei.pdf" target="_blank" title="Adozioni"><i class="fa fa-book" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="item">
					<div class="col-md-4 col-sm-6 col-xs-12 img text-center">
						<img src="imgs/sraffa.png">
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12 info">
						<p>I.I.S. Sraffa-Marazzi</p>
						<address>Tel. +39 0373/257802-202814</address>
						<a href="https://www.sraffacrema.edu.it/" target="blank" title="Sito Internet"><i class="fa fa-globe" aria-hidden="true"></i></a>
						<a href="#" target="_blank" title="Adozioni"><i class="fa fa-book" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="item">
					<div class="col-md-4 col-sm-6 col-xs-12 img text-center">
						<img src="imgs/pacioli.png">
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12 info">
						<p>I.I.S. Luca Pacioli</p>
						<address>Tel. +39 0373/80828-86044</address>
						<a href="https://www.pacioli.net/" target="blank" title="Sito Internet"><i class="fa fa-globe" aria-hidden="true"></i></a>
						<a href="/file/pacioli.pdf" target="_blank" title="Adozioni"><i class="fa fa-book" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!--<div class="container-fluid slider-photo">
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
	</div>-->

	<?php include("elements/contatto.php"); ?>

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