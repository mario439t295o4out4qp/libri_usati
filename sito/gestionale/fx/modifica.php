<?php

include "../../lib/funzioni.php";
/*
if(isset($_GET['tabella']) && $_GET['tabella']=="prenotati"){
	$value=$_GET['value'];
	$ID=$_GET['ID'];
	connect_DB( $mysqli );
	if($value==0) $mysqli->query( "UPDATE prenotati SET stato='' WHERE ID=$ID" );
	else $mysqli->query( "UPDATE prenotati SET stato='assegnabile' WHERE ID=$ID" );
	mysqli_close( $mysqli );
	header( "location: ../prenotati.php" );
	exit();
}
if(isset($_GET['tabella']) && $_GET['tabella']=="prenotazione"){
	$value=$_GET['value'];
	$ID=$_GET['ID'];
	connect_DB( $mysqli );
	if($value==0) $mysqli->query( "UPDATE prenotazione SET caparra='' WHERE ID=$ID" );
	else $mysqli->query( "UPDATE prenotazione SET caparra='15' WHERE ID=$ID" );
	mysqli_close( $mysqli );
	header( "location: ../ricevute.php" );
	exit();
}
*/
$tabella = $_POST[ 'tabella' ];
if(isset($_POST['campi'])) {
	$campi = $_POST[ 'campi' ];
	getPost( $campi, $valori );
	$valori = implode( ", ", $valori );
}
if ( $tabella == "utenti" ) {
	$ID = $_POST[ 'ID' ];
	connect_DB( $mysqli );
	update( $mysqli, $tabella, $campi, $valori, $ID );
	header( "location: ../utenti.php" );
} 
else if ( $tabella == "users" ) {
	$ID = $_POST[ 'ID' ]; //ID User
	$IDu = $_POST[ 'IDu' ]; //ID Utente
	$nick = $_POST[ 'nickname' ];
	if (ckSede( $_POST[ 'sede' ])) {
		if ($nick != 'admin' || ($nick == 'admin' && $ID == 1 ) ) { // nessuno può attribuirsi nick==admin
			connect_DB( $mysqli );
			update( $mysqli, $tabella, $campi, $valori, $ID );
			if(isset($_POST['password']))
				if ( $_POST['password'] != "" ) {
					connect_DB( $mysqli );
					update( $mysqli, $tabella, "password", "'".md5($_POST['password'])."'", $ID );
				}
		}
	}
	header( "location: ../utente.php?ID=$IDu" );
} 
else if ( $tabella == "prenotati" ) {
	$ID = $_POST[ 'ID' ]; //ID prenotazione
	$nuovo = $_POST[ 'nuovo' ]; // nuovo libro da associare
	$swap = $_POST[ 'swap' ]; // prenotazione con cui fare cambio

//	$anno = getAnno();
	connect_DB( $mysqli );
	$query = "SELECT id_mag into @precedente FROM prenotati WHERE ID=$ID;";
	$query.= "UPDATE prenotati SET stato='assegnabile', id_mag=$nuovo WHERE ID=$ID;";
	if ($swap) $query.= "UPDATE prenotati SET id_mag=@precedente WHERE ID=$swap";
	$res = queryMultipla($mysqli,$query);
	mysqli_close( $mysqli );
	echo json_encode("ok");
}


?>