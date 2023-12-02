<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<?php 
	include("elements/head.php"); 
	require_once "lib/funzioni.php"
?>

<body>
	<?php include("elements/nav.php"); ?>

	<div class="container-fluid visoreInt" style="background-image: url('imgs/visore-01.jpg')">
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
			<h1>Chi siamo</h1>
			<h4 class="sottoTit">La Nostra Storia</h4>
			<p><i>
				Il Mercatino dei Libri Usati del Galilei nasce nell'estate 2014, quando un gruppo di ragazzi delle classi 2e, del Liceo e dell'ITIS, si unirono per trovare una 
				soluzione alla spesa dei libri di testo, che ogni anno ricade come un macigno sulle famiglie. Dopo un piccolo periodo di "briefing", dall'idea iniziale si passò 
				ai fatti quando l'allora vicepreside, Enrico Fasoli, autorizzò l'iniziativa. Con una dozzina di ragazzi alla prima esperienza e con strumenti quasi rudimentali, 
				iniziò nell'Aula Ristoro, il primo anno di servizio alla comunità scolastica del Galilei. 
				Tra ritiro e vendita dei libri di testo sono state coinvolte 250 famiglie per un totale di 450 libri venduti e generando un potenziale risparmio per le 
				famiglie di 9.000€. Dopo il grande successo della prima edizione si decise di continuare l'anno successivo, anno ricco di miglioramenti nel campo organizzativo e 
				gestionale, il quale a ha portato a coinvolgere più di 400 famiglie per un totale di 850 libri venduti e un risparmio stimato per le famiglie di 18.000€.
				Un grande successo che testimonia come ormai il Mercatino di Libri Usati sia entrato a far parte della "routine" del Galilei. Il supporto della nuova dirigenza, 
				insieme alla dedizione e al lavoro del gruppo di ragazzi anche durante l'anno scolastico, ha consentito miglioramenti all'aspetto gestionale ed organizzativo, così 
				da dover impiegare un tempo ridotto per ogni singolo fruitore.
			</i></p>
			<br><br>
			<h4 class="sottoTit">Il nuovo Mercatino</h4>
			<p>
				Dal 2018 il Mercatino del Galilei si trasforma nel <b>Mercatino dei libri usati di Crema</b> grazie alla collaborazione tra gli studenti dell’Istituto Galilei e il <b>Consultorio 
				diocesano "Insieme per la famiglia"</b>, con il patrocinio della Consulta giovani e dell’Assessorato alle Politiche Giovanili del Comune di Crema, l’opportunità 
				del mercatino viene estesa agli studenti di tutti gli istituti scolastici cittadini.<br>
				Grazie al fondamentale contributo dello studente Pietro Donelli viene messo a punto il gestionale on-line con servizio di consultazione, prenotazione e gestionale.<br>
				Dopo il primo anno sperimentale, viene confermato nel 2019 il servizio per le scuole di Crema che hanno aderito ed hanno messo a disposizione spazi e studenti per la gestione.
				<br>Nonostante la pandemia, nel 2020 il servizio viene faticosamente tenuto attivo, ma il successivo perdurare della emergenza nel 2021 e 2022 non ne ha permesso l'organizzazione.
			</p>
			<br><br>
			<h4 class="sottoTit">Il Mercatino Rinasce!</h4>
			<p>
				Nel 2023, il mercatino rinasce grazie alla volontà dei rappresentanti degli studenti del Galilei, sempre con la gestione a cura del <b>Consultorio 
				diocesano "Insieme per la famiglia"</b>, con il patrocinio della Consulta giovani e dell’Assessorato alle Politiche Giovanili del Comune di Crema
				<br>
			<b>Non potete mancare: vi aspettiamo!!</b>
			</p>
			<br><br><p>Qui sotto alcune delle foto "storiche" ...</p>
			<img src="imgs/chi_siamo.jpg" class="img-responsive">
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