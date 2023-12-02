<?php

require_once "../../lib/funzioni.php";
require "../../lib/check.php";

$tabella = $_POST[ 'tabella' ];

if ( $tabella == "prenotazione" ) {
	$ID = $_POST[ 'ID' ];
	$valori = explode( '; ', $_POST[ 'valori' ] );
	connect_DB( $mysqli );
	echo insertRP( $mysqli, "prenotazione", "ID_catalogo", $ID, $valori );
}


?>