<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="riassunto" class="container corpo">

			<h1>Riassunto vendite</h1>

			<div id="ID" style="display: none"><?php echo $ID ?></div>
			
			<select id="sede2" placeholder="sede2" name="sede2">
				<option value="" selected disabled>Sede</option>
				<option value="1" >Galilei</option>
				<option value="3" >Pacioli</option>
				<option value="7" >Consultorio</option>
			</select>

				<?php
					$aa = getAnno();
			/*		$query="SELECT distinct dove, DATE_FORMAT(data,'%d/%m/%Y') dd FROM vendita v inner join magazzino m on v.ID=m.ID_vendita where anno=$aa ORDER BY dove, data";
					
					connect_DB( $mysqli );
					
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					while ( $row = $res->fetch_assoc() ) echo "<option data-dove=\"".$row['dove']."\">".$row['dd']."</option>";
			*/	?>
			
			<table class="table" id="elencoVendite">
				<thead>
					<td width="20%" >Sede</td>
					<td width="15%">Data</td>
					<td width="20%">Ricevute</td>
					<td width="20%">Libri</td>
					<td width="10%">Totale</td>
					<td width="10%"></td>
				</thead>
				<tbody>
					<?php
					$query="select dove,dd, dt,count(id) nric, sum(ricavo) totale, sum(nlibri) num from (
							select m.dove, DATE_FORMAT(data,'%d/%m/%Y') dd, DATE_FORMAT(data,'%Y-%m-%d') dt, v.id,sum(round( m.prezzo * if(m.ID_catalogo=0,0.4,0.5),2)) ricavo, count(m.id) nlibri from magazzino m
							inner join vendita v on v.id=m.ID_vendita
							where m.anno=$aa group by m.dove, dd, dt, id ) rr group by dove, dd,dt order by dove, dt desc";
					$mysqli->real_query( $query );
					$res = $mysqli->use_result();

					echo "\n";
					while ( $row = $res->fetch_assoc() ) {
						echo "<tr data-dove=\"". $row[ 'dove' ] ."\" data-dd=\"". $row[ 'dd' ] ."\">\n";

						echo "<td>" . getSede($row[ 'dove' ]) . "</td>\n";
						echo "<td>" . $row[ 'dd' ] . "</td>\n";
						echo "<td>" . $row[ 'nric' ] . "</td>\n";
						echo "<td>" . $row[ 'num' ] . "</td>\n";
						echo "<td>" . $row[ 'totale' ] . " &euro;</td>\n";
						echo "<td><span class=\"fa fa-eye vediDD\" aria-hidden=\"true\"></td>\n";
						echo "</tr>\n";
					}
					
					?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>

</body>

</html>