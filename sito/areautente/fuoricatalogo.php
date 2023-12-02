<?php 
include "fx/session.php"; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="dashboard" class="container corpo">

			<h1>Libri fuori catalogo</h1>
			
			<p>Testi NON presenti negli elenchi adozioni, ma disponibili, al 40% del prezzo di copertina, presso il punto ritiro 
			 perché molto simili a libri in adozione (versioni recenti, volumi unificati, ...)</p>


				<?php

				connect_DB( $mysqli );
				$anno = getAnno();
				$query = "SELECT ID,descrizione as stato,prezzo,dove FROM magazzino WHERE anno=$anno and ID_catalogo=0 and isnull(ID_vendita) ORDER BY descrizione";
				$mysqli->real_query( $query );
				$res = $mysqli->use_result();
?>
				<table class="table ">
					<thead id="head">
						<td width="5%">Cod.</td>
						<td width="20%">ISBN</td>
						<td width="50%">Titolo</td>
						<td width="10%">Prz.Cop.</td>
						<!--td width="15%">Posizione</td-->
					</thead>

<?php
					while ( $row = $res->fetch_assoc() ) {			
					
						$n = strpos($row[ 'stato' ],"::");
						if ( $n !== false) {
									$isbn = substr($row[ 'stato' ],$n+2);
									$titolo = substr($row[ 'stato' ],0,$n);
								}
						else {
							$isbn=""; $titolo = $row[ 'stato' ];
						}		
						echo "<tr>";
						echo "\t\t<td>" . $row['ID'] . "</td>";
						echo "\t\t<td>" . $isbn . "</td>";
						echo "\t\t<td style='text-align:left;'>&nbsp;" . $titolo . "</td>";
						echo "\t\t<td>" . $row['prezzo'] . "</td>";
					//	echo "\t\t<td>" . getSede($row['dove']) . "</td>";
						
						echo "</tr>";
					}
					mysqli_close( $mysqli );
					?>

				</table>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>