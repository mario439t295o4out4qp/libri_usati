<?php

require_once "../../lib/funzioni.php";
require "../../lib/check.php";

$ID = $_POST[ 'ID' ];
$prezzo = $_POST[ 'prezzo' ];
if (isset($_POST['note'])) $note = "descrizione='". str_replace("'","\'",$_POST[ 'note' ]) . "',"; else $note="";
connect_DB( $mysqli );
$query = "update magazzino set prezzo=$prezzo, $note postupd=1 where id=$ID"; 
$mysqli->query( $query );
//dbg_file($query);
mysqli_close( $mysqli );
echo " ";

?>