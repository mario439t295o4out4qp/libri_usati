<?php
	require_once "lib/funzioni.php";

//	include("lib/funzioni.php");
	connect_DB( $mysqli );
	$anno = getAnno();
	$mysqli->real_query( "SELECT count(*) as nlibri from magazzino where anno=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$nlibri = $res[ 'nlibri' ];

	$mysqli->real_query( "SELECT count(*) as utenti from utenti" );
	$res = $mysqli->use_result()->fetch_assoc();
	$utenti = $res[ 'utenti' ];

	$mysqli->real_query( "SELECT count(*) as venduti from magazzino WHERE ID_vendita IS NOT NULL and anno=".$anno );
	$res = $mysqli->use_result()->fetch_assoc();
	$venduti = $res[ 'venduti' ];

	/*$nlibri=0;
	$venduti=0;
	$utenti=0;*/
?>	

<div class="container-fluid barra hidden-xs">
	<div class="container"><a href="magazzino.php">
		<div class="col-md-6 dati hidden-sm hidden-xs">
			<div class="col-md-4 dato"><i class="over fa fa-arrow-down" aria-hidden="true"></i><i class="under fa fa-book" aria-hidden="true"></i>
				<span>
					<?php echo $nlibri ?>	
				</span>
			</div>
			<div class="col-md-4 dato"><i class="over fa fa-arrow-up" aria-hidden="true"></i><i class="under fa fa-book" aria-hidden="true"></i>
				<span>
					<?php echo $venduti ?>
				</span>
			</div>
			<div class="col-md-4 dato"><i class="fa fa-user" aria-hidden="true"></i><i class="under fa fa-user" aria-hidden="true"></i>
				<span>
					<?php echo $utenti ?>
				</span>
			</div>
		</a></div>
		<div class="col-md-6 ricerca">
			<div class="input-group">
				<input type="text" class="form-control" aria-label="..." placeholder="Cerca ISBN (ultime quattro cifre)">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Scuola <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="#">Galilei</a></li>
						<li><a href="#">Sraffa</a></li>
						<li><a href="#">Pacioli</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="risultati container">
	<table>
	</table>
</div>