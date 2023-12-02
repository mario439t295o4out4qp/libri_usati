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
			<a href="#" class="hvr-sweep-to-top">Ricerca in magazzino</a>
		</div>
	</div>

	<?php include("elements/barra.php"); ?>

	<div class="container-fluid corpo">
		<div class="container" id="catalogo">
			<h1>Ricerca libri disponibili</h1>
			<select id="scuola" placeholder="Scuola" name="scuola">
				<option value="" selected disabled>Scuola</option>
				<?php
					$anno = getAnno();
					$query="SELECT distinct scuola FROM adozioni where anno=".$anno." ORDER BY scuola ASC";
					
					connect_DB( $mysqli );
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option>".$row['scuola']."</option>\n";
				?>
			</select>

			<select id="indirizzo" class="hidden" placeholder="Indirizzo" name="indirizzo">
				<option value="" selected disabled>Corso</option>
				<?php
					$query="SELECT distinct indirizzo,scuola FROM adozioni where anno=$anno ORDER BY indirizzo";
					
					connect_DB( $mysqli );
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option data-scuola=\"".$row['scuola']."\">".$row['indirizzo']."</option>\n";
				?>
			</select>
			
			<select id="classe" class="hidden" placeholder="Classe" name="classe">
				<option value="" selected disabled>Classe</option>
				<?php
					$query="SELECT distinct classe, indirizzo, scuola FROM adozioni where anno=$anno ORDER BY classe";
					
					connect_DB( $mysqli );
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option data-scuola=\"".$row['scuola']."\" data-indirizzo=\"".trim($row['indirizzo'])."\">".$row['classe']."</option>\n";
				?>
			</select>
			
			<table class="table" id="sceltaLibri">
				<thead>
					<td width="15%">Materia</td>
					<td width="10%">ISBN</td>
					<td width="45%">Titolo</td>
					<td width="10%">Disponibili</td>
					<td width="10%">Prenotati</td>
					<td width="10%">Venduti</td>
				</thead>
				<tbody>
					<?php
					$query = "SELECT c.ID, c.ISBN, c.titolo, c.vol, a.scuola, a.indirizzo, a.classe, a.materia,
(select count(*) from magazzino m where c.ID=m.Id_catalogo and m.anno=$anno) nn,
(select count(*) from prenotati p where c.ID=p.Id_catalogo and p.anno=$anno) pp,
(select count(*) from magazzino v where c.ID=v.ID_catalogo and v.anno=$anno and not v.ID_vendita is null) vv
FROM catalogo AS c INNER JOIN adozioni AS a ON a.ID_catalogo=c.ID WHERE a.anno=$anno";
				//	$query="SELECT c.ID, c.ISBN, c.titolo, c.casa_editrice, c.autore, c.vol, a.scuola, a.indirizzo, a.classe, a.materia, a.da_acquistare FROM catalogo AS c INNER JOIN adozioni AS a ON a.ID_catalogo=c.ID";
				//	$query.=" WHERE a.anno=".$anno." ORDER BY a.scuola, a.indirizzo ASC, a.classe ASC, a.da_acquistare DESC, a.materia ASC";
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					echo "\n";
					while ( $row = $res->fetch_assoc() ) 
					  if ($row[ 'nn' ] > 0) {
						if ( $row['vol'] != "") $vv = " (" . $row['vol'] . ")"; else $vv="";
						echo "\t\t\t<tr data-scuola=\"". $row[ 'scuola' ] ."\" data-indirizzo=\"". trim($row[ 'indirizzo' ])."\" data-classe=\"". $row[ 'classe' ] ."\">\n";

						echo "\t\t\t\t<td class=\"ID\">" . $row[ 'materia' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'ISBN' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'titolo' ] . $vv . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'nn' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'pp' ] . "</td>\n";
						echo "\t\t\t\t<td>" . $row[ 'vv' ] . "</td>\n";
						echo "\t\t\t</tr>\n";
						
					}
					
					?>
				</tbody>
			</table>
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
				<!--div class="item">
					<img src="imgs/sost-04.jpg">
				</div-->
			</div>
		</div>
	</div>

	<?php include("elements/footer.php"); ?>

</body>

</html>