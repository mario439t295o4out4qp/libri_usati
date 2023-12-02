<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>
<style>
#catalogo #listCatalogo li span.other { background-color: gold; }
#catalogo #listCatalogo li span.pacio { background-color: darkcyan; color: white; }
</style>
<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="catalogo" class="container corpo">

			<h1>Catalogo</h1>

			<div class="strumenti">
				<i id="refresh" class="fa fa-refresh ico" aria-hidden="true"></i>
				<!-- Filtro -->
				<div id="filtra" class="input-group">
					<input type="text" class="form-control" placeholder="Cerca nel catalogo">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Titolo <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" class="active">Titolo</li>
							<li id="2">ISBN</li>
							<li id="3">Materia</li>
							<li id="4">Classe</li>
						</ul>
					</div>
				</div>
				<!-- Ordinamento 
				<div id="ordina" class="input-group">
					<span class="input-group-btn">
            <button class="btn btn-default" id="go" type="button"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i></button>
          </span>
				
					<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Titolo <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li id="1" data-type="t" class="active">Titolo</li>
							<li id="2" data-type="t">ISBN</li>
							<li id="3" data-type="t">Materia</li>
							<li id="4" data-type="t">Ind</li>
						</ul>
					</div>
				</div>-->
			</div>

			<ul id="listCatalogo" class="list-group">

<?php

	function disp( $ID ) {
		connect_DB( $mysqli );
		$result = $mysqli->query( "SELECT COUNT(*) AS n FROM magazzino WHERE anno=" . getAnno() . " and ID_catalogo=$ID AND ID_vendita IS NULL" );
		$row = $result->fetch_assoc();
		echo $row[ 'n' ];
		mysqli_close( $mysqli );
	}

	function classi( $ID, & $indirizzo, & $classe, & $scuola ) {
		$indirizzo = array();
		$classe = array();
		$anno = array();
		$scuola = array();
		connect_DB( $mysqli );
		$mysqli->real_query( "SELECT indirizzo, classe,anno,scuola FROM adozioni WHERE anno=".getAnno()." and ID_catalogo=$ID order by indirizzo,classe" );
//					$mysqli->real_query( "SELECT indirizzo, classe, anno FROM adozioni WHERE ID_catalogo=$ID order by anno desc,indirizzo,classe" );
		$res = $mysqli->use_result();
		$i = 0;
		while ( $row = $res->fetch_assoc() ) {
			$indirizzo[ $i ] = $row[ 'indirizzo' ];
			$classe[ $i ] = $row[ 'classe' ];
			$anno[ $i ] = $row[ 'anno' ];
			$scuola[ $i ] = $row[ 'scuola' ];
			$i++;
		}
		mysqli_close( $mysqli );
		$classi = "";
		$anno_attuale = getAnno();
		$flag=1;
		for ( $i = 0; $i < count( $indirizzo ); $i++ ) {
			if ( $flag==1 && $anno[ $i ] == $anno_attuale) $flag = 0;
			if ( $flag==0 && $anno[ $i ] != $anno_attuale) $classe[ $i ] = "";
			if ( $flag==1 && $anno[ $i ] != $anno_attuale) $indirizzo[ $i ] = "oldadoz";
			else if ($scuola[$i] == "I.I.S. Luca Pacioli") $indirizzo[ $i ] = "pacio";
			else if ( $indirizzo[ $i ] == "BIOTECNOLOGIE AMBIENTALI" || $indirizzo[ $i ] == "BIOTECNOLOGIE SANITARIE" || $indirizzo[ $i ] == "CHIMICA E MATERIALI" || $indirizzo[ $i ] == "CHIMICA,MATERIALI E BIOTECNOLOGIE - BIENNIO COMUNE" )$indirizzo[ $i ] = "chim";
			else if ( $indirizzo[ $i ] == "INFORMATICA" || $indirizzo[ $i ] == "INFORMATICA E TELECOMUNICAZIONI - BIENNIO COMUNE" || $indirizzo[ $i ] == "TELECOMUNICAZIONI" )$indirizzo[ $i ] = "info";
			else if ( $indirizzo[ $i ] == "ENERGIA" || $indirizzo[ $i ] == "MECCANICA E MECCATRONICA" || $indirizzo[ $i ] == "MECCANICA, MECCATRONICA E ENERGIA - BIENNIO COMUNE" )$indirizzo[ $i ] = "mec";
			else if ($indirizzo[ $i ] == "LICEO SCIENTIFICO - OPZIONE SCIENZE APPLICATE" ) $indirizzo[ $i ] = "liceo";
			else $indirizzo[ $i ] = "other";

			$classi .= $classe[ $i ] . " ";
		}
		return; // $classi;
	}

	connect_DB( $mysqli );
	$aa = getAnno();
	$campi = "ID, titolo, ISBN, materia, prezzo";
//	$query = "SELECT $campi FROM catalogo ORDER BY ISBN";
	$query = "select distinct c.ID, titolo, ISBN, prezzo from catalogo c inner join adozioni a on c.ID=a.ID_catalogo where a.anno=$aa order by ISBN";
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();

	$campi = explode( ", ", $campi );
	$ISBN = 0;
	$scuola = "";
//	$classe = "";
	while ( $row = $res->fetch_assoc() ) {
		if ( $row[ 'ISBN' ] != $ISBN ) {
			if(file_exists("../imgs/libri/".$row[ 'ISBN' ].".jpg")) $limg="/imgs/libri/".$row[ 'ISBN' ].".jpg";
			else $limg="/imgs/libri/barcode.png";
?>

				<li class="list-group-item">
					<div class="visibile">
						<img src="<?php echo $limg; ?>" class="codBarre p-1">
						<span class="badge"><?php echo disp($row[$campi[0]]); ?></span>
						<i class="fa fa-angle-down ico riduci" aria-hidden="true"></i>
						<small class="Titolo"><?php echo $row['titolo']; ?></small>
						<small class="ISBN"><?php echo $row['ISBN']; ?></small> &bull;
						<small class="Classe">*</small>
						<small class="Prezzo"><?php echo $row['prezzo']; ?>&euro;</small><br>
<?php
			classi($row[$campi[0]], $indirizzo, $classe, $scuola);
			echo "\t";
			for ( $j = 0; $j < count( $indirizzo ); $j++ )
				if ($classe[$j] != "")
				echo "<span class='" . $indirizzo[ $j ] . "'>" . $classe[ $j ] . "</span>";
			echo "\n";
?>
					</div>

					<table class="table descrCat">
						<thead id="head">
							<td width="50px">Cod. Libro</td>
							<td width="50px">Prezzo</td>
							<td width="90px">Deposito</td>
							<td width="150px">Note</td>
							<td width="80px">R.RIT.</td>
							<td width="80px">R.VEN.</td>
						</thead>

<?php 
			connect_DB($mysqli1);
			$sqlcampi="m.ID, prezzo, dove, descrizione, r.IdAnno ricR, v.IdAnno ricV";
			$campi="ID, prezzo, dove, descrizione, ricR, ricV";
			
			$query="SELECT $sqlcampi FROM magazzino m inner join ritiro r on m.ID_ritiro=r.ID left join vendita v on m.ID_vendita=v.id";
			$query.=" WHERE anno=".getAnno()." and ID_catalogo=".$row['ID'];
			$result1=$mysqli1->query($query);
			$campi=explode(", ", $campi);
			while ($row1 = $result1->fetch_assoc()){
			echo "<tr>";
			for($t=0; $t<count($row1); $t++)
				if ($t == 2) echo "<td>".getSede($row1[$campi[$t]]);
				else echo "<td>".$row1[$campi[$t]];
			echo "</tr>";}
			mysqli_close($mysqli1);
?>

					</table>
				</li>

<?php
		}
		$ISBN = $row[ 'ISBN' ];
	}
?>
			</ul>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>