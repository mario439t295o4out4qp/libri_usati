<?php

$ID = $_GET[ 'ID' ];
$cod = $_GET[ 'cod' ];

include( "../../lib/funzioni.php" );
connect_DB( $mysqli );
$query = $mysqli->prepare("select nickname, password, ID_utenti FROM standbyusers WHERE ID=? AND cod=?");
$query->bind_param('ss',$ID,$cod);
$query->execute();
$res = $query->get_result();
if ($res->num_rows > 0) {					
	$row = $res->fetch_assoc();
	$nick = $row[ 'nickname' ];
	$pass = $row[ 'password' ];
	$IDu = $row[ 'ID_utenti' ];
	mysqli_close( $mysqli );
	connect_DB( $mysqli );
	insertMultiQuery( $mysqli, "users", "ID_utenti, nickname, password, privilegi,attivo", "'$IDu', '$nick', '$pass', 1,1" );
//	mysqli_close( $mysqli );
	connect_DB( $mysqli );
	deleteTab( $mysqli, "standbyusers", $ID );
//	mysqli_close( $mysqli );
	header( "location: ".getSiteUtente() );
}

?>