<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<?php 
  include("elements/head.php"); 
  require_once "lib/funzioni.php"
?>

  <body>

    <?php include("elements/nav.php"); ?>
    
    <div class="container-fluid visoreInt" style="background-image: url('imgs/visore-03.jpg')">
      <div class="titoli hidden-xs">
        <h2>#LibriUsati</h2>
        <h3>Mercatino dei Libri Usati di Crema</h3>
      </div>
      <div class="barra1 visible-xs">
        <a href="#" class="hvr-sweep-to-top">Visualizza il catalogo adozioni</a>
      </div>
    </div>

    <?php include("elements/barra.php"); ?>

    <div class="container-fluid corpo">
      <div class="container">
        <h1>Servizi</h1>
        <div class="col-sm-4 col-xs-12 servizi">
          <ul>
            <li class="servizio hvr-sweep-to-top" id="ritiro">Ritiro</li>
            <li class="servizio hvr-sweep-to-top" id="prenotazione">Prenotazione</li>
            <li class="servizio hvr-sweep-to-top" id="vendita">Vendita</li>
            <li class="servizio hvr-sweep-to-top" id="restituzione">Restituzione</li>
          </ul>
        </div>
        <div class="col-sm-8 col-xs-12 testi">
          <div id="ritiro">
            <p>
              <b>Quando?</b><br>
              <?php echo ritiro_str() ?><br>
              <p class="btn-danger"> SERVIZO DI RITIRO (data EXTRA) il 23 agosto dalle 8.30 alle 12.30</p><br>
              <b>Come funziona?</b> <br>Ogni libro usato ancora in adozione dalle scuole superiori di Crema aderenti al servizio, 
			  puo' essere lasciato in contovendita al Mercatino, presso l'Aula Ristoro, avendo la garanzia che 
			  se lo stesso verrà venduto, il 40% del prezzo di copertina verrà restituito al fornitore del libro <?php echo restituzione_str()?> presso il Galilei (vedi restituzione).
            </p>
          </div>
          <!--div class="active" id="ritiro1">
            <p>
              <b>Quando?</b><br>
              I venerdì e sabato mattina di luglio presso l’Istituto Galilei dalle 8:30 alle 12:30<br>
              <b>Come funziona?</b> Pensi di non riuscire a consegnare i tuoi libri in nessuna data prevista? Nessun problema, scarica il modulo seguente, compilalo e lascialo 
			  con i tuoi libri direttamente al personale presente nell'atrio del Galilei (portali raccolti in un sacchetto o in uno scatolone). 
			  E' semplice, devi solo inserire le tue referenze così che potremo contattarti! Nella tabella riporta le ultime 4 cifre dei codici ISBN che trovi sul retro dei libri
			  che vuoi consegnare.<br>
				<a href="file/moduloritiro.pdf" download><img src="imgs/pdf.png" width="50px" style="margin-right:10px;"> Scarica il modulo</a>
            </p>
          </div-->
          <div id="prenotazione">
             <p>
                <b>Come?</b><br>Comodamente da casa. <br><br>
                <b>Quando?</b><br>
				Disponibile online il <b><a href="registrati.php">form per la registrazione</a>&nbsp;<?php echo registrazioni_aperte_str() ?>
				</b>
				e <br>dal <b>&nbsp;<?php echo prenotazioni_aperte_str() ?></b> la <b><a href="/areautente/">piattaforma di prenotazione</a>! </b>
				<br><br>
                <b>Come funziona?</b><br> Chiunque può registrarsi e prenotare dal sito. 
				La consegna dei libri prenotati avverrà entro il giorno <?php echo consegna_str().consegna_orario_str() ?> presso l'aula ristoro del Galilei; non è garantito il mantenimento della prenotazione 
				oltre  questa data. In caso di impossibilità per il ritiro dei libri il <?php echo consegna_str() ?>, scrivici attraverso i moduli "Contattaci" che trovi nella home del sito
				e concorderemo insieme un'altra data.<br><br>
                <strong>Le prenotazioni non sono garanzia dell'effettiva presenza dei libri. Al termine del periodo di prenotazione sarà possibile verificare la disponibilità
				dei libri prenotati nell'area utente. Nel caso in cui ci arrivassero in seguito altri libri da voi richiesti vi contatteremo via telefono per segnalarvelo.</strong><br>
				<br>
           		Per chiarimenti o maggiori informazioni inviaci una messaggio attraverso i moduli "Contattaci" che trovi nella Home e del sito!		
            </p>
          </div>
		  <div id="vendita">
             <p>
              <b>Quando?</b><br>
                Presso il Galilei (aula ristoro)<br>
                <?php echo vendita_str() ?>
              <br><br>
              <b>Come funziona?</b> <br>Conoscendo la classe della quale si stanno cercando i libri è possibile comprarli al Mercatino, presso l'Aula Ristoro, 
			  al 50% del prezzo di copertina. Il 40% verrà restituito al fornitore del libro, mentre il 10% verrà utilizzato per comprare testi e 
			  materiale scolastico per ragazzi in difficoltà segnalati dai singoli istituti.
            </p>
          </div>
          <div id="restituzione">
             <p>
              <b>Quando?</b><br>
              <?php echo restituzione_str().restituzione_orario_str() ?><br>
              <br>
              <b>Come funziona?</b> <br>Chiunque avesse lasciato libri in contovendita durante il periodo di ritiro, è tenuto a presentarsi nelle giornate dedicate
			  per la restituzione del ricavato dalla vendita ed eventuali libri invenduti.
          </p>
          </div>
        </div>        
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