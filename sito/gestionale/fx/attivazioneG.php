<?php
require "../../lib/check.php";
$ID = $_GET[ 'ID' ];
include( "../../lib/funzioni.php" );

//Recupero dati
connect_DB( $mysqli );
$query = "SELECT nome, cognome FROM utenti WHERE ID='$ID'";
$mysqli->real_query( $query );
$res = $mysqli->use_result()->fetch_assoc();
$nick = strtolower($res[ 'nome' ].".".$res[ 'cognome' ]);
$pass = md5('Galilei');
$IDu = $ID;
mysqli_close( $mysqli );

//Inserimento user
connect_DB( $mysqli );
insertMultiQuery( $mysqli, "users", "ID_utenti, nickname, password, privilegi, attivo", "'$IDu', '$nick', '$pass', 1, 1" );

//Eliminazione eventuali standyusers
connect_DB( $mysqli );
$mysqli->query( "DELETE FROM standbyusers WHERE ID_utenti=$ID" );
mysqli_close( $mysqli );

header( "location: ../utente.php?ID=$ID" );


?>
