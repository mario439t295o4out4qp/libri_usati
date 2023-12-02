<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<script>
$(function () {
	$('.ricevute').click(function () {
		$('.vendita').addClass('hidden');
		$('.prenotazione').addClass('hidden');
		$('.ritiro').addClass('hidden');
		if ($(this).hasClass('r-vendita')) $('.vendita').removeClass('hidden');
		if ($(this).hasClass('r-prenotazione')) $('.prenotazione').removeClass('hidden');
		if ($(this).hasClass('r-ritiro')) $('.ritiro').removeClass('hidden');		 
	});

	$('.saldo').click(function(){
//		alert($(this).attr('idu'));
		window.open('fx/ricevute.php?type=saldo&ID=0&IdU='+$(this).attr('idu'));
	});
});
</script>
<style>
.fa-newspaper {
	color:#ccc;
}
.fa-newspaper-o:hover {
	color:darkgreen;
	cursor: pointer;
}
</style>
<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="shadow">
		<div class="jumbotron" id="jumPre">
			<h3>Modifica prezzo</h3>
			<i class="fa fa-times close chiudiJum" aria-hidden="true"></i>
			<form id="formPrezzo" method="post" >
				<div class="form-group datiUtente">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Cod.Libro</span>
						<input type="text" class="form-control smallImp" name="ID" id="IdL" aria-describedby="basic-addon1" readonly>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">ISBN</span>
						<input type="text" class="form-control" name="ISBN" aria-describedby="basic-addon1" readonly>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Titolo</span>
						<input type="text" class="form-control" name="Titolo" aria-describedby="basic-addon1" readonly>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">ID_vendita</span>
						<input type="text" class="form-control" name="ID_vendita" aria-describedby="basic-addon1" readonly>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Prezzo</span>
						<input type="text" class="form-control" name="prezzo" id="prezzo" aria-describedby="basic-addon1">
					</div>
				</div>
				<button class="btn btn-default regPrezzo" id="btnSalvaPr" type="button">Aggiorna</button>
			</form>
		</div>
	</div>
	
	<div class="container-fluid box">
		<div id="ricevute" class="container corpo">

			<h1>Ricevute</h1>
			<div class="text-center">
				<button class="btn ricevute r-vendita" style="background-color:#009ee0; width:150px">Vendita</button>
				<button class="btn ricevute r-prenotazione" style="background-color:#df2589; width:150px">Prenotazione</button>
				<button class="btn ricevute r-ritiro" style="background-color:#88bf67; width:150px">Ritiro</button>
				<button class="btn ricevute r-vendita r-ritiro r-prenotazione" style="width:150px">Tutte</button>
			</div>
			<table class="table" id="tabRicevute">
				<thead id="head">
					<td style="width:4%">Progr.</td>
					<td style="width:25%">Utente</td>
					<td style="width:25%">E-Mail</td>
					<td style="width:10%">Telefono</td>
					<td style="width:15%">Data</td>
					<td style="width:2%"></td>
					<?php if($privilegi==3) echo "<td style=\"width:2%\"></td>";?>
					<td style="width:2%"></td>
				</thead>
				<tbody>
	<?php
		if (isset($_GET['ID'])) { 
			$u_ID=$_GET['ID']; $whereutente = "and o.ID_utente=$u_ID ";
		}
		else $whereutente = "";

					connect_DB( $mysqli );
				//	$ricevute = array( 'ritiro', 'vendita', 'prenotazione' );
					$ricevute = array( 'vendita', 'prenotazione','ritiro' );
					for ( $i = 0; $i < count( $ricevute ); $i++ ) {
						$campi = "o.ID, o.IdAnno, u.nome, u.cognome, u.telefono, u.mail, o.data, o.stato, o.ID_utente";
						if($ricevute[$i]=="prenotazione") $campi = "o.ID, o.IdAnno, u.nome, u.cognome, u.telefono, u.mail, o.data, o.stato, o.caparra, o.ID_utente";
						$query = "SELECT $campi FROM " . $ricevute[ $i ] . " AS o INNER JOIN utenti AS u ON o.ID_utente = u.ID where year(o.data)=".getAnno()." $whereutente ORDER BY o.ID DESC";
						$mysqli->real_query( $query );
						$res = $mysqli->use_result();

				//		$campi = "IdAnno, nome, cognome, data";
				//		$campi = explode( ", ", $campi );
						echo "\n";
						while ( $row = $res->fetch_assoc() ) {
							echo "\t\t\t<tr class=\"$ricevute[$i]\">\n";

							echo "\t\t\t\t<td>" . $row[ 'IdAnno' ] . "</td>\n";
							echo "\t\t\t\t<td>" . $row[ 'nome' ] . " " . $row[ 'cognome' ] . "</td>\n";
							echo "\t\t\t\t<td>" . $row[ 'mail' ] . "</td>\n";
							echo "\t\t\t\t<td>" . $row[ 'telefono' ] . "</td>\n";
							echo "\t\t\t\t<td>" . $row[ 'data' ] . "</td>\n";
							if ( $row[ 'stato' ] == "" )
								echo "\t\t\t\t<td><a target=\"_blank\" href=\"fx/ricevute.php?type=$ricevute[$i]&IdU=". $row['ID_utente'] . "&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-print\" style=\"color:red!important;\"aria-hidden=\"true\"></i></a></td>\n";
							else 
								echo "\t\t\t\t<td><a target=\"_blank\" href=\"fx/ricevute.php?type=$ricevute[$i]&IdU=". $row['ID_utente'] . "&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a></td>\n";
							if ( $privilegi == 3 )echo "\t\t\t<td><a target=\"_blank\" href=\"fx/elimina.php?tabella=" . $ricevute[ $i ] . "&redirect=../ricevute.php&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-times-circle cancellaRiga\" aria-hidden=\"true\"></i></a></td>";
							echo "\t\t\t\t<td><i class=\"fa fa-angle-down riduci\" aria-hidden=\"true\"></i></td>\n";
							echo "\t\t\t</tr>\n";
?>
<?php
							if ( $ricevute[ $i ] == "ritiro" ) {
?>
					<tr class="descRicevuta" >
						<td class="tdDR" colspan="<?php if($privilegi > 1) echo 8; else echo 7;?>">
							<table class="table">
								<thead id="head">
									<td style="width:5%">Libro</td>
									<td style="width:15%">ISBN</td>
									<td style="width:44%">Titolo</td>
									<td style="width:5%">ID Vend.</td>
									<td style="width:10%">Prezzo</td>
									<td style="width:10%">Guad. <i class="fa fa-newspaper-o saldo" idu="<?php echo $row['ID_utente'] ?>"></i></td>
									<td style="width:1%"></td>
								</thead>
								<tbody>
									<?php
									connect_DB( $mysqli1 );
									$query1 = "SELECT m.ID, c.ISBN, c.titolo, m.prezzo, m.ID_vendita, m.ID_catalogo, m.descrizione FROM magazzino AS m LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID WHERE m.ID_ritiro=" . $row[ 'ID' ] . " ORDER BY m.ID ASC";
									$mysqli1->real_query( $query1 );
									$res1 = $mysqli1->use_result();
//									$guadagno = getGuadagno();
									echo "\n";
									$totale = 0;
									while ( $row1 = $res1->fetch_assoc() ) {
										if ( $row1[ 'ID_catalogo' ] != 0) {
											$isbn = $row1[ 'ISBN' ];
											$tit = $row1[ 'titolo' ];
										}
										else {
											$n = strpos($row1[ 'descrizione' ],"::");
											if ( $n !== false) {
												$isbn =substr($row1[ 'descrizione' ],$n+2);
												$tit = "(fuori cat.) " . substr($row1[ 'descrizione' ],0,$n);
											}
										}
										$gV = getVendita($row1[ 'ID_catalogo' ]);

										echo "\t\t\t<tr>\n";
										$id = $row1[ 'ID' ];
										echo "\t\t\t\t<td>" . $id . "</td>\n";
										echo "\t\t\t\t<td>" . $isbn . "</td>\n";
										echo "\t\t\t\t<td>" . $tit . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'ID_vendita' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'prezzo' ] . "</td>\n";
										if ( $row1[ 'ID_vendita' ] != NULL ) {
											echo "\t\t\t\t<td>" . $row1[ 'prezzo' ] * $gV . "</td>\n";
											$totale += $row1[ 'prezzo' ] * $gV;
										} else echo "\t\t\t\t<td></td>\n";
										if($privilegi > 1) {
											echo "\t\t\t\t<td><span id=m" . $id . "></span>&nbsp;";
											echo "<i class='fa fa-pencil apriPre' aria-hidden='true'></i>";
											echo "</td>\n";
										}
										else echo "\t\t\t\t<td></td>\n";
										echo "\t\t\t</tr>\n";
									}
									mysqli_close($mysqli1);
									echo "\t\t\t<tr>\n";
									echo "\t\t\t<td colspan=\"4\"></td>\n";
									echo "\t\t\t\t<td class=\"totale\" >Totale</td>\n";
									echo "\t\t\t\t<td>" . $totale . " &euro;</td>\n";
									echo "\t\t\t</tr>\n";
?>
								</tbody>
							</table>
						</td>
					</tr>
<?php
							} else if ( $ricevute[ $i ] == "vendita" ) {
						?>
					<tr class="descRicevuta">
						<td class="tdDR" colspan="<?php if($privilegi==3) echo 8; else echo 7;?>">
							<table class="table">
								<thead id="head">
									<td style="width:10%">Libro</td>
									<td style="width:15%">ISBN</td>
									<td style="width:50%">Titolo</td>
									<td style="width:10%">Prezzo</td>
									<td style="width:10%">Vend.</td>
									<?php if($privilegi==3) echo "<td></td>";?>
								</thead>
								<tbody>
<?php
									$totale = 0;
									connect_DB( $mysqli1 );
									$query1 = "SELECT m.ID, c.ISBN, c.titolo, m.prezzo, m.ID_catalogo, m.descrizione FROM magazzino AS m LEFT JOIN catalogo AS c ON m.ID_catalogo = c.ID WHERE m.ID_vendita=" . $row[ 'ID' ] . " ORDER BY m.ID ASC";
									$mysqli1->real_query( $query1 );
									$res1 = $mysqli1->use_result();

									echo "\n";
									$totale = 0;
									while ( $row1 = $res1->fetch_assoc() ) {
										if ( $row1[ 'ID_catalogo' ] != 0) {
											$isbn = $row1[ 'ISBN' ];
											$tit = $row1[ 'titolo' ];
										}
										else {
											$n = strpos($row1[ 'descrizione' ],"::");
											if ( $n !== false) {
												$isbn =substr($row1[ 'descrizione' ],$n+2);
												$tit = "(fuori cat.) " . substr($row1[ 'descrizione' ],0,$n);
											}
										}
										echo "\t\t\t<tr>\n";

										echo "\t\t\t\t<td>" . $row1[ 'ID' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $isbn . "</td>\n";
										echo "\t\t\t\t<td>" . $tit . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'prezzo' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'prezzo' ]*0.5 . "</td>\n";
										$totale += $row1[ 'prezzo' ] * 0.5;
										if($privilegi==3) echo "\t\t\t\t<td><a target=\"blank\" href=\"fx/elimina.php?tabella=venduto&redirect=../ricevute.php&ID=" . $row1[ 'ID' ] . "\"><i class=\"fa fa-times-circle cancellaRiga\" aria-hidden=\"true\"></i></a></td>\n";
										echo "\t\t\t</tr>\n";
									}
									mysqli_close($mysqli1);
									echo "\t\t\t<tr>\n";
									echo "\t\t\t<td colspan=\"3\"></td>\n";
									echo "\t\t\t\t<td class=\"totale\" >Totale</td>\n";
									echo "\t\t\t\t<td>" . $totale . " &euro;</td>\n";
									echo "\t\t\t</tr>\n";
									?>
								</tbody>
							</table>
						</td>
					</tr>

<?php
							} else if ( $ricevute[ $i ] == "prenotazione" ) {
?>
					<tr class="descRicevuta">
						<td class="tdDR" colspan="<?php if($privilegi==3) echo 8; else echo 7;?>">
							<table class="table">
								<thead id="head">
									<td style="width:10%">ID</td>
									<td style="width:20%">ISBN</td>
									<td style="width:50%">Titolo</td>
									<td style="width:10%">Stato<!--span class="caparra">(cap.) <?php // echo $row['caparra']; ?> &euro;</span--></td>
									<?php
									//	if($row[ 'caparra' ]=="" || $row[ 'caparra' ]==0) echo "<a href=\"fx/modifica.php?tabella=prenotazione&value=1&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-money\" aria-hidden=\"true\"></i></a>\n";
									//	else echo "<a href=\"fx/modifica.php?tabella=prenotazione&value=0&ID=" . $row[ 'ID' ] . "\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>\n";
									?>
								</thead>
								<tbody>
									<?php
									connect_DB( $mysqli1 );
									$query1 = "SELECT p.ID, c.ISBN, c.titolo, p.stato FROM prenotati AS p INNER JOIN catalogo AS c ON p.ID_catalogo = c.ID WHERE p.ID_prenotazione=" . $row[ 'ID' ] . " ORDER BY p.ID ASC";
									$mysqli1->real_query( $query1 );
									$res1 = $mysqli1->use_result();

									echo "\n";
									$totale = 0;
									$t=0;
									while ( $row1 = $res1->fetch_assoc() ) {
										$t++;
										$stato = $row1[ 'stato' ];
										if ( $row1[ 'stato' ] != "" )
											$totale++;
										else $stato="prenotato";
										echo "\t\t\t<tr>\n";
										echo "\t\t\t\t<td>" . $row1[ 'ID' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'ISBN' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $row1[ 'titolo' ] . "</td>\n";
										echo "\t\t\t\t<td>" . $stato . "</td>\n";
										echo "\t\t\t</tr>\n";
									}
									mysqli_close($mysqli1);
									echo "\t\t\t<tr>\n";
									echo "\t\t\t<td colspan=\"3\"></td>\n";
									echo "\t\t\t\t<td class=\"totale\">" . $totale . "/". $t ."</td>\n";
									echo "\t\t\t</tr>\n";
									?>
								</tbody>
							</table>
						</td>
					</tr>

					<?php
							} else echo "<tr class=\"descRicevuta\"></tr>";
						}
					}
					mysqli_close( $mysqli );
					?>
				</tbody>

			</table>

		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>