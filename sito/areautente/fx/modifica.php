<?php
require_once "../../lib/funzioni.php";
require "../../lib/check.php";

$campi = $_POST[ 'campi' ];
$tabella = $_POST[ 'tabella' ];
$_POST[ 'password' ] = md5( $_POST[ 'password' ] );
getPost( $campi, $valori );
$valori = implode( ", ", $valori );
$ID = $_POST[ 'ID' ];

if ( $tabella == "utenti" ) {
	connect_DB( $mysqli );
	update( $mysqli, $tabella, $campi, $valori, $ID );
	header( "location: ../utente.php" );
} else if ( $tabella == "users" ) {
	$ID = $_POST[ 'ID' ];
	$vpassword = md5( $_POST[ 'vpassword' ] );

	connect_DB( $mysqli );
	$query = "SELECT * FROM users WHERE ID=$ID AND password='$vpassword'";
	$res = $mysqli->query( $query );
	$row = $res->fetch_assoc();
	if ( $row ) {
//		connect_DB( $mysqli );
		update( $mysqli, $tabella, $campi, $valori, $ID );
		header( "location: ../utente.php" );
	} else header( "location: ../utente.php?error=pw" );
}


?>