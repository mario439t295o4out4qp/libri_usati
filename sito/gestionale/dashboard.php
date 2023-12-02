<?php include "fx/session.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it">

<!-- HEAD -->
<?php include "elements/head.php"; ?>

<body>

	<!-- NAVBAR -->
	<?php include "elements/navbar.php"; ?>

	<div class="container-fluid box">
		<div id="dashboard" class="container">

			<h1>Dashboard</h1>

<?php
	$anno = getAnno();

	connect_DB( $mysqli );
	$mysqli->real_query( "SELECT count(*) as nlibri from magazzino where anno=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$nlibri = $res[ 'nlibri' ];

	$mysqli->real_query( "SELECT count(*) as utenti from utenti" );
	$res = $mysqli->use_result()->fetch_assoc();
	$utenti = $res[ 'utenti' ];

	$mysqli->real_query( "SELECT count(*) as ricevute from ritiro where year(data)=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute = $res[ 'ricevute' ];
	$mysqli->real_query( "SELECT count(*) as ricevute from vendita where year(data)=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute += $res[ 'ricevute' ];
	$mysqli->real_query( "SELECT count(*) as ricevute from prenotazione where year(data)=".$anno);
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute += $res[ 'ricevute' ];
	
	$mysqli->real_query( "SELECT count(*) as ricevute from ritiro WHERE stato='' and year(data)=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute1 = $res[ 'ricevute' ];
	$mysqli->real_query( "SELECT count(*) as ricevute from vendita WHERE stato='' and year(data)=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute1 += $res[ 'ricevute' ];
	$mysqli->real_query( "SELECT count(*) as ricevute from prenotazione WHERE stato='' and year(data)=".$anno);
	$res = $mysqli->use_result()->fetch_assoc();
	$ricevute1 += $res[ 'ricevute' ];

	$mysqli->real_query( "SELECT sum(pre) prenota FROM (SELECT count(*) pre,anno,stato FROM prenotati GROUP by anno,stato HAVING anno=$anno and (isnull(stato) or stato<>'rinuncia')) a");
	$res = $mysqli->use_result()->fetch_assoc();
	$pre = $res[ 'prenota' ];

	$mysqli->real_query( "SELECT count(*) as venduti from magazzino WHERE ID_vendita IS NOT NULL and anno=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$venduti = $res[ 'venduti' ];

	$sql = "select sum(round( m.prezzo * if(m.ID_catalogo=0,0.4,0.5),2)) tot, sum(round( m.prezzo * if(m.ID_catalogo=0,0.07,0.1),2)) guad from magazzino m where not isnull(id_vendita) and anno=".$anno;
	$mysqli->real_query( $sql);
	$res = $mysqli->use_result()->fetch_assoc();
	$profitto = $res[ 'guad' ];
	$tot = $res[ 'tot' ];
?>

			<ul class="list-group">
				<li class="list-group-item">
					<span id="nsede" class="badge new"></span>
					<span id="sede" class="badge">
						<?php echo getSede($_SESSION['sede']); ?>
					</span>
					Sede di lavoro
				</li>
				<li class="list-group-item">
					<span id="nlibri" class="badge new"></span>
					<span id="libri" class="badge">
						<?php echo $nlibri; ?>
					</span>
					Libri
					<a href="magazzino.php" ></a>
				</li>
				<li class="list-group-item">
					<span id="nutenti" class="badge new"></span>
					<span id="utenti" class="badge">
						<?php echo $utenti; ?>
					</span>
					Utenti
					<a href="utenti.php" ></a>
				</li>
				<li class="list-group-item">
					<span id="nricevute" class="badge new"><?php if($ricevute1!=0) echo $ricevute1; ?></span>
					<span id="ricevute" class="badge">
						<?php echo $ricevute; ?>
					</span>
					Ricevute
					<a href="ricevute.php" ></a>
				</li>
			</ul>

			<ul class="list-group">
				<li class="list-group-item">
					<span id="nvend" class="badge new">
						<?php echo $pre; ?>
					</span>
					<span id="vend" class="badge"></span> Prenotazioni
					<a href="prenotati.php" ></a>
				</li>
				<li class="list-group-item">
					<span id="nvend" class="badge new">
						<?php echo $venduti; ?>
					</span>
					<span id="vend" class="badge"></span> Libri Venduti
					<a href="magazzino.php" ></a>
				</li>
				<li class="list-group-item">
					<span id="nprofitto" class="badge new">
						<?php echo sprintf( "%01.2f", $profitto )." &euro;"; ?>
					</span>
					<span id="nprofitto" class="badge">
						<?php echo sprintf( "%01.2f", $tot ); ?>
					</span>
					<span id="profitto" class="badge"></span> Ricavo/Profitto
					<a href="riassunto.php"></a>
				</li>
			</ul>

			<!--<canvas id="grafico-linea"></canvas>-->

		</div>
	</div>

	<!-- FOOTER -->
	<?php include "elements/footer.php"; ?>
</body>
</html>