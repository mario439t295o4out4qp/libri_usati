<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<script>
$(function () {
	function printAlert(type, text) {
		$('body').append("<div class=\"alert alert-" + type + "\">" + text + "</div>");
		$('.alert').fadeOut(6000, function () {
			$('.alert').remove();
		});
	}

	$('.scegliLibro').click(function () {
//		var ID = $(this).prev().text();
		tit = $(this).parents('tr').find('td:eq(1)').text();
		pre = $(this).parents('tr').find('td:eq(2)').text()
		$('#titolo').html(tit+"<br><i>prenot. "+pre);
		var ID = $(this).parents('tr').attr('idp');
		var attuale =$(this).parents('tr').find('td:eq(5)').text();
		$('#scelta').html(ID);
		$.ajax({
			method: "POST",
			url: "fx/listavolumi.php",
			data: "ID=" + ID,
			dataType: "json"
		}).done(function (data) {
			$('#tab1').find("tr:gt(0)").remove();
			data.forEach(function(riga) {		// 0 idm, 1 prezzo, 2 ID_vendita, 3 rritiro, 4 idp, 5 stato
				if (riga[2]) {
					venduto = "idv="+riga[2]; btn=""; stato="venduto";
				}
				else {
					venduto = "---"; btn = "<button class='libro'>Scegli</button>"; stato=riga[5];
				}
				if (riga[0] == attuale) { questo = "attuale"; btn="";} else questo="";
				$('#tab1').find('tbody').append($("<tr class='"+questo+"' idp='"+riga[4]+"'>")
					.append($("<td>").html(riga[0]))
					.append($("<td>").html(riga[3]))
					.append($("<td>").html(riga[1]))
					.append($("<td>").html(riga[4]))
					.append($("<td>").html(stato))
					.append($("<td>").html(venduto))
					.append($("<td>").html(btn)));
			});  
			$('#elenco').modal({backdrop:false});
		}).fail(function () {
			printAlert("warning", "<b>Spiacente</b><br>Elenco volumi non recuperabile");
		});
	});	
	$('#tab1').on("click", ".libro", function () {
		nuovo = $(this).parents('tr').find('td:eq(0)').text();
		ID = $('#scelta').html();
		pre = $(this).parents('tr').attr('idp');
		$.ajax({
			method: "POST",
			url: "fx/modifica.php",
			data: "tabella=prenotati&ID=" + ID + "&nuovo="+nuovo+"&swap="+pre,
			dataType: "json"
		}).done(function (data) {
			window.location.href = 'prenotati.php';
		}).fail(function (err) {
			printAlert("warning", "<b>Spiacente</b><br>Cambio impossibile");
		});			
		$('#elenco').modal('hide');
	});
});
</script>
<style>
	#tab1 { width:100%}
	#tab1 tr {border-bottom : 1px solid lightgray;}
	#tab1 td {padding: 3px;}
	.attuale { background-color: lightyellow;}
	.scroll {
      overflow-y: scroll;
      height: auto;
      max-height: 400px; }
	 .close {
      position: absolute;
      top: 20px;
      right: 20px;
      color: grey;
      font-size: 40px;
      line-height: 40px; }	  
</style>	
<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="prenotati" class="container corpo prenota">

			<h1>Prenotati</h1>

			<div class="strumenti">
				<i id="refresh" class="fa fa-refresh ico" aria-hidden="true"></i>
				<!-- FILTRO -->
				<div id="filtra" class="input-group">
					<input type="text" class="form-control" placeholder="Cerca tra i prenotati">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ISBN <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" class="active">ISBN</li>
							<li id="2">Titolo</li>
							<li id="3">Prenot.</li>
							<li id="4">Pr.tà</li>
							<li id="5">Q.tà</li>
							<li id="6">Cod.</li>
							<li id="7">Stato</li>
						</ul>
					</div>
				</div>
				<!-- ORDINAMENTO -->
				<div id="ordina" class="input-group">
					<span class="input-group-btn">
					<button class="btn btn-default" id="go" type="button"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i></button>
					</span>
				
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ISBN <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" data-type="n" class="active">ISBN</li>
							<li id="2" data-type="t">Titolo</li>
							<li id="3" data-type="n">Prenot.</li>
							<li id="4" data-type="n">Pr.tà</li>
							<li id="5" data-type="n">Q.tà</li>
							<li id="6" data-type="n">Cod.</li>
							<li id="7" data-type="t">Stato</li>
						</ul>
					</div>
				</div>
			</div>

			<table class="table" id="tabPrenotati"	>
				<thead id="head">
					<!--td style="width:5%">ID</td-->
					<td style="width:15%">ISBN</td>
					<td >Titolo</td>
					<td style="width:5%">Prenot.</td>
					<td style="width:5%">Pr.tà</td>
					<td style="width:5%">Q.tà</td>
					<td style="width:5%">Cod.</td>
					<td style="width:20%">Stato</td>
					<td style="width:5%">Cambia</td>
					<?php if($privilegi==3) echo "<td style=\"width:5%\"></td>";?>
				</thead>
				<tbody>
					<?php
					connect_DB( $mysqli );
					$anno=getAnno();
					$campi = "p.ID, c.ISBN, c.titolo, p.ID_prenotazione, p.stato, pr.IdAnno, p.priorita, ifnull(p.ID_mag,0) idM,u.cognome, u.nome,";
					$campi .= "(select count(*) from magazzino m where c.ID=m.Id_catalogo and m.anno=$anno) nn, ";
					$campi .= "(select id_vendita from magazzino m where m.id=idM) idV ";
					$query = "SELECT $campi FROM prenotati AS p INNER JOIN catalogo AS c ON p.ID_catalogo = c.ID inner join prenotazione pr on pr.ID=p.ID_prenotazione inner join utenti u on pr.ID_utente=u.ID where year(pr.data)= $anno ";
					$query .= "ORDER BY pr.IdAnno, p.priorita DESC, c.ISBN";
//					$query .= "ORDER BY c.ISBN, p.priorita DESC, pr.IdAnno";
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					echo "\n";
					while ( $row = $res->fetch_assoc() ) {
						if ($row[ 'stato' ]==="assegnabile" || $row[ 'stato' ]==="rinuncia") $nome= " (" . $row[ 'cognome' ] . " " . substr($row['nome'],0,1) . ".)";
						else $nome="";
						$prior = ($row[ 'priorita' ] < 0) ?  0 : $row[ 'priorita' ];
						echo "\t\t\t<tr idp='".$row[ 'ID' ]."'>\n";
						echo "\t\t\t\t<td>" . $row[ 'ISBN' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'titolo' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'IdAnno' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $prior . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'nn' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'idM' ] . "</td>\n";
						if ($row["idV"]) $stato="venduto (idv=".$row["idV"].")"; else $stato=$row[ 'stato' ] . $nome ;
						echo "\t\t\t\t<td style='text-align:left'>" . $stato . "</td>\n";
//						if($row[ 'stato' ]=="") echo "\t\t\t\t<td><a href=\"fx/modifica.php?tabella=prenotati&value=1&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-check cambiaLibro\" aria-hidden=\"true\"></i></a></td>\n";
//						else echo "\t\t\t\t<td><a href=\"fx/modifica.php?tabella=prenotati&value=0&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-times cambiaLibro\" aria-hidden=\"true\"></i></a></td>\n";
						if ($row["idV"] || $row[ 'stato' ]=="rinuncia") $fa="fa-times"; 
						else if($row[ 'stato' ] != "") $fa = "fa-check scegliLibro"; else $fa = "fa-times scegliLibro";
						echo "\t\t\t\t<td><i class='hidden'>". $row[ 'ID' ] . "</i><i class='fa " . $fa . "' ></i></a></td>\n";

						if($privilegi==3) echo "\t\t\t<td><a target=\"blank\" href=\"fx/elimina.php?tabella=prenotati&redirect=../prenotati.php&ID=" . $row[ 'ID' ] . "\">
						<i class=\"fa fa-times-circle cancellaRiga\" aria-hidden=\"true\"></i></a></td>";

						echo "\t\t\t</tr>\n";
					}
					mysqli_close( $mysqli );
					?>
				</tbody>

			</table>

		</div>
	</div>

	<div class="modal fade" id="elenco" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" >Elenco volumi del testo</h5>
					<span id="titolo" class="pl-4 text-primary font-weight-bold"></span>
					<button type="button" class="close" data-dismiss="modal" >
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body scroll"><span id="scelta" style="display:none"></span>
					<table id="tab1">
						<thead>
							<th>Cod.</th>
							<th>R.Ritiro</th>
							<th>Prezzo</th>
							<th>R.Prenot.</th>
							<th>Stato</th>
							<th>Vendita</th>
							<th>Cambia in</th>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>