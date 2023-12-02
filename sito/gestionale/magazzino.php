<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php 
include "elements/head.php"; 
require_once "../lib/funzioni.php"
?>

<body>
<script>
$(function () {	
	$('#tabMagazzino').on("click", ".vedi", function () {
		var isbn=$(this).parents('tr').find('td:eq(1)').html();
		txt = "<img src='../../imgs/libri/" + isbn + ".jpg' width='300px'><br>" + "ISBN: " + isbn;
		$('body').append("<div class=\"alert alert-info\">" + txt + "</div>");
		$('.alert').fadeOut(6000, function () {
			$('.alert').remove();
		});
	});	
	$('#tabMagazzino').on("click", ".modificaPrezzo", function () {
		var ID = $(this).parents('tr').find('td:eq(0)').html();
		var tit = $(this).parents('tr').find('td:eq(2)').html();
		var note = $(this).parents('tr').find('td:eq(4)');
		var nota = note.html();
		var prezzo = $("#m"+ID).html();
		$('#titolo').html(ID+": "+tit);
		$('#note').val(nota);
		$('#prezzo').val(prezzo);
		$('#idm').val(ID);
//		prezzo=prompt('Nuovo prezzo per il libro di codice: '+ID,prezzo);
		$('#modifica').modal({backdrop:false});
	});	
	$('#salva').click(function () {
		nota = $('#note').val();
		prezzo = $('#prezzo').val();
		idm = $('#idm').val();
		if (prezzo != "" )
			prz = Number(prezzo);
		else prz = NaN;
		if (!isNaN(prz)) {
			var data = "ID=" + idm + "&prezzo=" + prz + "&note=" + nota;
			$.ajax({
				method: "POST",
				url: "fx/modificaPrezzo.php",
				data: data,
			}).done(function (data) {
				$("#m"+idm).html(prz);
				$("#m"+idm).next().html(nota);
			}).fail(function () {
				printAlert("danger", "<b>Attenzione!</b><br> C'è stato un errore nel salvataggio dei dati, riprova!");
			});
		}
		$('#modifica').modal('hide');
	});
});	
</script>	
	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="magazzino" class="container corpo">

			<h1>Magazzino</h1>

			<div class="strumenti">
				<i id="refresh" class="fa fa-refresh ico" aria-hidden="true"></i>
				<!-- FILTRO -->
				<div id="filtra" class="input-group">
					<input type="text" class="form-control" placeholder="Cerca nel magazzino">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ISBN <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1">Libro</li>
							<li id="2" class="active">ISBN</li>
							<li id="3">Titolo</li>
							<li id="4">Prz.</li>
							<li id="5">Desc.</li>
							<li role="separator" class="divider"></li>
							<li id="6">R.RIT</li>
							<li id="7">R.PRN</li>
							<li id="8">R.VEN</li>
						</ul>
					</div>
				</div>
				<!-- ORDINAMENTO -->
				<div id="ordina" class="input-group">
					<span class="input-group-btn">
            <button class="btn btn-default" id="go" type="button"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i></button>
          </span>
				
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Libro <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" data-type="n" class="active">Libro</li>
							<li id="2" data-type="t">ISBN</li>
							<li id="3" data-type="t">Titolo</li>
							<li id="4" data-type="n">Prz.</li>
							<li id="5" data-type="t">Desc.</li>
							<li role="separator" class="divider"></li>
							<li id="6" data-type="n">R.RIT</li>
							<li id="7" data-type="n">R.PRN</li>
							<li id="8" data-type="n">R.VEN</li>
						</ul>
					</div>
				</div>
			</div>

			<table class="table" id="tabMagazzino">
				<thead id="head">
					<td style="width:5%">Libro</td>
					<td style="width:15%">ISBN</td>
					<td style="width:45%">Titolo</td>
					<td style="width:5%">Prz.</td>
					<td style="width:20%">Descriz.</td>
					<td style="width:5%">R.RIT</td>
					<td style="width:5%">R.PRN</td>
					<td style="width:5%">R.VEN</td>
					<td style="width:50px"></td>
				</thead>
				<tbody>
					<?php
					connect_DB( $mysqli );
					$anno = getAnno(); 
					$campi = "m.ID, c.ISBN, c.titolo, m.ID_catalogo, m.descrizione, m.ID_ritiro, m.ID_vendita, m.prezzo, r.idanno as progR, r.ID_utente as IdUr, v.idanno as progV, v.ID_utente as IdUv, p.idanno idpr ";
					$query = "SELECT $campi
                  FROM magazzino AS m
                  left JOIN catalogo AS c ON m.ID_catalogo = c.ID 
				  left join vendita v on m.ID_vendita=v.ID 
				  left join ritiro r on m.ID_ritiro=r.ID 
				  left join prenotati pr on m.ID=pr.ID_mag
				  left join prenotazione p on p.id=pr.id_prenotazione
				  where m.anno=".$anno." ORDER BY m.ID DESC";
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					echo "\n";
					while ( $row = $res->fetch_assoc() ) {
						if ( $row[ 'ID_catalogo' ] != 0) {
							$isbn = $row[ 'ISBN' ];
							$tit = $row[ 'titolo' ];
							$des = $row[ 'descrizione' ];
						}
						else {
							$n = strpos($row[ 'descrizione' ],"::");
							if ( $n !== false) {
								$isbn =substr($row[ 'descrizione' ],$n+2);
								$tit = substr($row[ 'descrizione' ],0,$n);
								$des = "fuori catalogo";
							}
						}
						// if ($row[ 'progV' ]) $modificaPrezzo=""; else   // MODIFICA 04.09.23 da fare
						$modificaPrezzo="modificaPrezzo";
						if ($row[ 'idpr' ]) $prenotato=$row[ 'idpr' ]; else $prenotato="";
						echo "\t\t\t<tr>\n";
						echo "\t\t\t\t<td>" . $row[ 'ID' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $isbn . "</td>\n";
						echo "\t\t\t\t<td>" . $tit . "</td>\n";
						echo "\t\t\t\t<td id='m".$row['ID']."'>" . $row[ 'prezzo' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $des . "</td>\n";
						echo "\t\t\t\t<td><a target=\"blank\" href=\"fx/ricevute.php?type=ritiro&IdU=". $row[ 'IdUr' ] . "&ID=" . $row[ 'ID_ritiro' ] . "\">" . $row[ 'progR' ] . "</a></td>\n";
						echo "\t\t\t\t<td>$prenotato</td>\n";
						echo "\t\t\t\t<td><a target=\"blank\" href=\"fx/ricevute.php?type=vendita&IdU=". $row[ 'IdUv' ] . "&ID=" . $row[ 'ID_vendita' ] . "\">" . $row[ 'progV' ] . "</a></td>\n";
						echo "\t\t\t\t<td><i class='fa fa-eye vedi'></i>&nbsp;<i class=\"fa fa-pencil $modificaPrezzo\" aria-hidden=\"true\"></i>&nbsp;";
						if($privilegi==3) echo "<a target=\"blank\" href=\"fx/elimina.php?tabella=magazzino&redirect=../magazzino.php&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-times-circle cancellaRiga\" aria-hidden=\"true\"></i></a>";
						echo "</td>\n";
						echo "\t\t\t</tr>\n";
					}
					mysqli_close( $mysqli );
					?>
				</tbody>

			</table>

		</div>
	</div>

	<div id="modifica" class="modal fade" role="dialog">
  		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modifica libro </h4><span id="titolo" class="text-primary font-weight-bold"></span>
			</div>
			<div class="modal-body">
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Prezzo copertina</span>
				  <input type="text" class="form-control" id="prezzo">
				</div>
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Descrizione/note</span>
				  <input type="text" class="form-control" id="note" >
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="salva" data-dismiss="modal">Salva</button>
				<input type="hidden" id="idm" value="">
			</div>
			</div>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>