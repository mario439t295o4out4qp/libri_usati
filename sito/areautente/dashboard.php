<?php 
include "fx/session.php"; 
require_once "../lib/funzioni.php"
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<script>
$(function () {
	$('.fa-times').click(function () {
		var txt = $(this).parents("tr").attr("id");
		prenot = $(this).parents("li").find("span").html();
		$("#idp").val(txt);
		lib = $('#'+txt).find("td:eq(1)").html();
		cod = $('#'+txt).find("td:eq(0)").html()
		$("#testo").html(prenot+"<br><br>RINUNCIA al cod."+cod+"<br>"+lib);
		$("#myModal").modal({keyboard: true, backdrop: true});
	});
	$('#conferma').on('click', function() {
    	$("#myModal").modal("hide");
		idp = $('#idp').val().substr(1);
		$.ajax({
			method: "POST",
			url: "fx/rinuncia.php",
			data: "idp=" + idp,
			dataType: "json"
		}).done(function (data) {
			var txt = $('#idp').val();
			$('#'+txt).find("td:eq(3)").html("rinuncia");
			$('#'+txt).find("td:eq(4) > i").css("display","none")
		}).fail(function () {
			alert("Spiacente<br> Errore in operazione di rinuncia");
		});
	})
});
</script>
<style>
.fa-times:hover {
    color: #ff8080 !important;
    cursor: pointer; }

</style>
<body>
	<!-- NAVBAR -->
<?php include "elements/navbar.php"; ?>
<?php
	connect_DB( $mysqli );
	$idu=$_SESSION['ID'];
	$query = "SELECT * FROM ritiro r inner join magazzino m on r.id= m.ID_ritiro where id_utente=$idu and postupd=1";
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();
	if ( $row = $res->fetch_assoc() ) {
		$msg = "ATTENZIONE: ci sono aggiornamenti nei prezzi di uno o più libri che hai consegnato.<br>Ristampa le relative ricevute di ritiro";
	}
	else $msg="";
	mysqli_close( $mysqli );
?>
	<div class="container-fluid box">
		<div id="dashboard" class="container corpo">

			<h1>Pannello utente</h1>
			
			<p><strong>Benvenuto!</strong><br>
			Prenota i libri per l'anno scolastico <?php echo getAnnoSc()?>, è facile! <br>
			Dal <b><?php echo prenotazioni_aperte_str() ?></b> basta andare nella pagina <a href="<?php echo getSiteUtente()."/prenotazione.php" ?>">"Prenota!"</a>, selezionare la scuola, il corso, la classe e scegliere 
			(cliccando sul carrello a lato di ogni libro) i libri che si desidera prenotare.<br> 
			Ultimata la prenotazione è possibile visualizzare in questa pagina i libri che sono stati prenotati e
			la ricevuta di prenotazione, che deve essere stampata e consegnata allo staff del mercatino nei giorni di vendita:<br>  
			<ul><?php echo vendita_str() ?></ul>
			In caso di problemi usa il modulo <a href="<?php echo getSite() ?>">"Contattaci"</a>.</p>
			<p>Dopo <?php echo assegnazione_str() ?>, nelle <span style='background-color: rgba(223, 37, 137, 0.5)'>ricevute di prenotazione</span> qui sotto potrai verificare lo stato di assegnazione dei libri richiesti:<br>
			- <i>prenotato</i>: il libro è stato prenotato on-line<br>
			- <i>assegnabile</i>: una copia del libro è disponibile e si ha priorità sufficiente per l'acquisizione<br>
			- <i>venduto</i>: il volume è stato assegnato all'utente<br>
			</p>

			<p class="msg"><?php echo $msg; ?></p>
			<ul id="riassuntoUtente" class="list-group">
<?php
	connect_DB( $mysqli );
	$ricevute = array( 'ritiro', 'vendita', 'prenotazione' );
	$annocorr = getAnno();
	for ( $i = 0; $i < count( $ricevute ); $i++ ) {
		$campi = "o.ID, u.nome, u.cognome, o.data, o.stato, o.idAnno";
		$query = "SELECT $campi FROM " . $ricevute[ $i ] . " AS o INNER JOIN utenti AS u ON o.ID_utente = u.ID WHERE u.ID=$idu ORDER BY o.ID DESC";
		$mysqli->real_query( $query );
		$res = $mysqli->use_result();
		echo "\n";
		while ( $row = $res->fetch_assoc() ) {
			echo "\t\t\t<li class=\"$ricevute[$i] list-group-item\">\n";
			echo "\t\t\t\t<span class=\"ric\">Ricevuta ". $ricevute[ $i ] . " n° <b>". $row['idAnno'] ."</b> / ". substr($row['data'],0,4)."</span>\n";
			echo "<i class=\"fa fa-angle-down riduci\" aria-hidden=\"true\"></i>\n";
			if ($annocorr == substr($row['data'],0,4))
				echo "<a target='blank' class='print' href='../gestionale/fx/ricevute.php?type=$ricevute[$i]&IdU=" . $IDu . "&ID=" . $row[ 'ID' ] . "'><i class='fa fa-print'></i></a>\n";
?>
				<table class="table descrCat">
					<thead id="head">
						<td width="5%">Cod.</td>
						<td width="45%">Titolo</td>
						<td width="20%">ISBN</td>
						<td width="25%">Stato</td>
						<td width="5%"></td>
					</thead>
<?php
			if ( $ricevute[$i] == "prenotazione" ) {
				$query = "SELECT p.ID, c.ISBN, c.titolo, p.stato, p.id_mag
						FROM $ricevute[$i] AS o
						INNER JOIN prenotati AS p ON o.ID = p.ID_$ricevute[$i]
						INNER JOIN catalogo AS c ON p.ID_catalogo = c.ID
						WHERE p.ID_$ricevute[$i]=". $row['ID'];
			} else {
				$query = "SELECT m.ID, c.ISBN, c.titolo, m.descrizione as stato, m.ID_catalogo, m.ID_vendita, m.postupd
						FROM $ricevute[$i] AS o
						INNER JOIN magazzino AS m ON o.ID = m.ID_$ricevute[$i]
						LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID
						WHERE m.ID_$ricevute[$i]=". $row['ID'];
			}
			connect_DB( $mysqli1 );
			$mysqli1->real_query( $query );
			$res1 = $mysqli1->use_result();
			$campi1 = array( 'ID', 'titolo', 'ISBN', 'stato');
			while ( $row1 = $res1->fetch_assoc() ) {
				$cancella="";
				$rr = substr($ricevute[$i],0,1);
				echo "<tr id=".$rr.$row1["ID"].">";
				if ( $ricevute[$i] != "prenotazione" ) { 
					if ( $row1[ 'ID_catalogo' ] == 0) {
						$n = strpos($row1[ 'stato' ],"::");
						if ( $n !== false) {
							$row1['ISBN'] = substr($row1[ 'stato' ],$n+2);
							$row1['titolo'] = "(fuori cat.) " . substr($row1[ 'stato' ],0,$n);
							$row1['stato'] = "";
						}
					}
					if ($row1['ID_vendita'] != NULL)
						if ( $ricevute[$i] == "ritiro" ) $row1['stato'] = "venduto"; else $row1['stato'] = "acquisito";
					else 
						if ($row1['postupd']==1) $row1['stato'] = "<span class='msg'>revisione prezzo</span>"; else $row1['stato'] = "in magazzino";
				}
				else  // prenotazione
					if ($row1['stato'] == "") $row1['stato'] = "prenotato";
					else if ($annocorr == substr($row['data'],0,4) && $row1['stato'] == "assegnabile") $cancella = "<i class='fa fa-times'></i>"; 
//				for($t=0; $t<4; $t++) echo "<td>".$row1[$campi1[$t]]."</td>";
				echo "<td>".$row1["ID"]."</td>";	
				echo "<td>".$row1["titolo"]."</td>";	
				echo "<td>".$row1["ISBN"]."</td>";	
				echo "<td>".$row1["stato"]."</td>";	
				echo "<td>".$cancella."</td>";	
				echo "</tr>";
			}
			mysqli_close( $mysqli1 );
?>
				</table>
<?php
			echo "\t\t\t</li>\n";
		}
	}
?>
			</ul>
		</div>
	</div>

	<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <p><span id="testo"></span> </p>
        </div>
        <div class="modal-footer"><input type="hidden" id="idp">
		<button id="conferma" type="button" class="btn btn-danger pull-left" data-dismiss="modal">Conferma</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
        </div>
      </div>
    </div>
  </div>
	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>
</html>