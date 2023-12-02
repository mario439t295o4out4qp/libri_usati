<?php

require_once "../../lib/funzioni.php";
require "../../lib/check.php";

$ID = $_GET[ 'ID' ];
$tabella = $_GET[ 'tabella' ];
$redirect = $_GET['redirect'];

// cancella il record attuale

if($tabella!="venduto"){
	if ($tabella=="utenti" && $ID=="1"); // non cancella mai admin
	else {
		connect_DB( $mysqli );
		if($tabella=="prenotati"){
			$aa = getAnno();
			$query="select * from prenotati WHERE ID=$ID";
							
			$mysqli->real_query( $query );
			$res = $mysqli->use_result();
			$query="";
			if ( $row = $res->fetch_assoc() ) {
				$ID_catalogo = $row[ 'ID_catalogo' ];				
				$priorita = $row[ 'priorita' ];				
				$query .= "update prenotati set priorita=priorita-1 where id_catalogo=$ID_catalogo and priorita>$priorita and anno=$aa;"; 
				$query .= "DELETE FROM prenotati WHERE ID=$ID;";
			}
//			dbg_file($query );
			mysqli_close( $mysqli );	
			connect_DB( $mysqli );
			mysqli_multi_query( $mysqli, $query );
			mysqli_close( $mysqli );
			header( "location: $redirect" );			
		}
		else
			deleteTab( $mysqli, $tabella, $ID );
	}
}


// cancella record correlati

if($tabella=="prenotazione"){
	connect_DB( $mysqli );
	$aa = getAnno();
	$query="select * from prenotati WHERE ID_prenotazione=$ID";
							
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();
	$query="";
	while ( $row = $res->fetch_assoc() ) {
		$ID_catalogo = $row[ 'ID_catalogo' ];				
		$priorita = $row[ 'priorita' ];				
		$query .= "update prenotati set priorita=priorita-1 where id_catalogo=$ID_catalogo and priorita>$priorita and anno=$aa;"; 
	}
	$query .= "DELETE FROM prenotati WHERE ID_prenotazione=$ID;";
	mysqli_multi_query( $mysqli, $query );
	mysqli_close( $mysqli );
}
if($tabella=="ritiro"){
	connect_DB( $mysqli );
	$mysqli->query( "DELETE FROM magazzino WHERE ID_ritiro=$ID" );
	mysqli_close( $mysqli );
}
if($tabella=="utenti"){
	if ($ID != 1) {  // non cancella mai admin
		connect_DB( $mysqli );
		$mysqli->query( "DELETE FROM users WHERE ID_utenti=$ID" );
		$mysqli->query( "DELETE FROM standbyusers WHERE ID_utenti=$ID" );
		mysqli_close( $mysqli );
	}
}

// aggiorna il magazzino in caso di vendita/venduto

if($tabella=="vendita"){
	connect_DB( $mysqli );
	$mysqli->query( "UPDATE magazzino SET ID_vendita=NULL WHERE ID_vendita=$ID" );
	mysqli_close( $mysqli );
}
if($tabella=="venduto"){
	connect_DB( $mysqli );
	$mysqli->query( "UPDATE magazzino SET ID_vendita=NULL WHERE ID=$ID" );
	mysqli_close( $mysqli );
}

//header( "location: $redirect" );

echo "<script language=\"Javascript\"> window.close(); </script>";

?>