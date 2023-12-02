<?php 
include "fx/session.php"; 
require_once "../lib/funzioni.php"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<script>
	function spinOff() {
		var attesa = document.getElementById("attesa");
		attesa.remove();
		var scuola = document.getElementById("scuola");
		scuola.classList.remove("hidden");
	}
</script>
<body onload="spinOff()">

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div id='attesa' class='alert'><div id='ppp' class='fa fa-spinner fa-spin'></div></div>

	<div class="container-fluid box">
		<div id="prenotazione" class="container corpo">
			<h1>Prenota</h1>
<?php
	if ( !backdoor() && isOnline() && 
	!prenotazioni_aperte()) {
?>
			<p>Le prenotazioni sono chiuse. Servizio disponibile dal <?php echo prenotazioni_aperte_str()." al ".assegnazione_str() ?>.</p>
<?php
	}
	else {
?>	
			<div id="ID" style="display: none"><?php echo $ID ?></div>
			
			<select id="scuola" class="hidden" placeholder="Scuola" name="scuola">
				<option value="" selected disabled>Scuola</option>
				<?php
					$aa = getAnno();
					$query="SELECT distinct scuola FROM adozioni where anno=$aa ORDER BY scuola";
					
					connect_DB( $mysqli );
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option>".$row['scuola']."</option>";
				?>
			</select>

			<select id="indirizzo" class="hidden" placeholder="Indirizzo" name="indirizzo">
				<option value="" selected disabled>Corso</option>
				<?php
					$query="SELECT distinct indirizzo,scuola FROM adozioni where anno=$aa ORDER BY indirizzo";
					
					connect_DB( $mysqli );
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option data-scuola=\"".$row['scuola']."\">".trim($row['indirizzo'])."</option>";
				?>
			</select>
			
			<select id="classe" class="hidden" placeholder="Classe" name="classe">
				<option value="" selected disabled>Classe</option>
				<?php
					$query="SELECT distinct classe, indirizzo, scuola FROM adozioni where anno=$aa ORDER BY classe";
					
					connect_DB( $mysqli );
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option data-scuola=\"".$row['scuola']."\" data-indirizzo=\"".trim($row['indirizzo'])."\">".$row['classe']."</option>";
				
				?>
			</select>
			
			<table class="table" id="sceltaLibri">
				<thead>
					<td width="5%" >Cod.</td>
					<td width="15%">ISBN</td>
					<td width="35%">Titolo</td>
					<td width="10%">Casa Editrice</td>
					<td width="15%">Autore</td>
					<td width="5%">Disp.</td>
					<td width="5%">Pren.</td>
					<td width="10%"></td>
				</thead>
				<tbody>
					<?php
					$query="SELECT c.ID, c.ISBN, c.titolo, c.casa_editrice, c.autore, a.scuola, a.indirizzo, a.classe, ";
					$query.="(select count(*) from magazzino m where c.ID=m.Id_catalogo and m.anno=$aa) nn, ";
					$query.="(select count(*) from prenotati p where c.ID=p.Id_catalogo and p.anno=$aa) pp ";
					$query.="FROM catalogo AS c INNER JOIN adozioni AS a ON a.ID_catalogo=c.ID WHERE a.anno=$aa ORDER BY a.scuola, a.indirizzo ASC, a.classe ASC, c.titolo ASC";
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

				//	$campi = "ID, nome, cognome, data";
				//	$campi = explode( ", ", $campi );
					echo "\n";
					while ( $row = $res->fetch_assoc() ) {
						$nnn = $row[ 'nn' ] - $row[ 'pp' ] ;
						echo "<tr data-scuola=\"". $row[ 'scuola' ] ."\" data-indirizzo=\"". trim($row[ 'indirizzo' ])."\" data-classe=\"". $row[ 'classe' ] ."\">\n";

						echo "<td class=\"ID\" >" . $row[ 'ID' ] . "</td>\n";
						echo "<td>" . $row[ 'ISBN' ] . "</td>\n";
						echo "<td>" . $row[ 'titolo' ] . "</td>\n";
						echo "<td>" . $row[ 'casa_editrice' ] . "</td>\n";
						echo "<td>" . $row[ 'autore' ] . "</td>\n";
						echo "<td class=\"nn\" >" . $row[ 'nn' ] . "</td>\n";
						echo "<td class=\"nn\" >" . $row[ 'pp' ] . "</td>\n";
					//	echo "<td><a href=\"fx/libro.php?ISBN=" .$row[ 'ISBN' ] ."\" target=_blank><i class=\"fa fa-eye\" aria-hidden=\"true\"></i></a>";
						echo "<td><span class=\"fa fa-eye vedi\" aria-hidden=\"true\"></span>";
						if ($nnn>0) echo "<i class=\"fa fa-cart-plus cart\" aria-hidden=\"true\"></i>\n";
						else echo "<i class=\"fa fa-times nocart\" aria-hidden=\"true\"></i>\n";
						echo "</td>\n";

						echo "</tr>\n";
					}
					
					?>
				</tbody>
			</table>
			
			
			<div class="prenotati">
				<h3>Libri prenotati</h3>

				<table class="table" id="nuoviLibri">
					<thead>
						<td width="5%">Cod.</td>
						<td width="15%">ISBN</td>
						<td width="35%">Titolo</td>
						<td width="10%">Casa Editrice</td>
						<td width="15%">Autore</td>
						<td width="5%"></td>
						<td width="5%"></td>
					</thead>
					<tbody></tbody>
				</table>
				<button class="btn btn-default regLibri" type="button">Prenota i libri scelti</button>
				
				<div class="carrello hvr-sweep-to-top"><i class="fa fa-shopping-cart" aria-hidden="true"></i></i></div>
			</div>
<?php 
	}
?>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>