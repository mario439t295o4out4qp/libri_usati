<?php
	require_once "../../lib/funzioni.php";
	require "../../lib/check.php";

	$idp = $_POST[ 'idp' ];
	connect_DB( $mysqli );
	$query="UPDATE prenotati set stato='rinuncia',id_mag=null,priorita=-9999 where id=$idp";
	$res = $mysqli->query( $query );
	$data = array("ok"); //
	$mysqli->close();
	echo json_encode( $data );
?>