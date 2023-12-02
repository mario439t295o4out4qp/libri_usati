<?php
	require_once "../../lib/funzioni.php";
	require "../../lib/check.php";

	$sc = $_POST[ 'sc' ];
	$cl = $_POST[ 'cl' ];
	$ind = $_POST[ 'ind' ];
	$aa=getAnno();
	connect_DB( $mysqli );
	$query="SELECT c.ID, c.ISBN, c.titolo, c.casa_editrice, c.autore, a.classe, ";
	$query.="(select count(*) from magazzino m where c.ID=m.Id_catalogo and m.anno=$aa) nn, ";
	$query.="(select count(*) from prenotati p where c.ID=p.Id_catalogo and p.anno=$aa) pp ";
	$query.="FROM catalogo AS c INNER JOIN adozioni AS a ON a.ID_catalogo=c.ID WHERE a.anno=$aa and scuola='$sc' and indirizzo='$ind' and classe='$cl' ";
	$query.="ORDER BY c.titolo ASC";
	$res = $mysqli->query( $query );
	$data = array(); //
	$i=0;
	while ( $row = $res->fetch_assoc() ) {
		$data[$i] = array( $row['ID'],$row['ISBN'],$row['titolo'], $row['casa_editrice'], $row[ 'autore' ],$row[ 'classe' ],$row[ 'nn' ],$row[ 'pp' ] );
		$i++;
	}
	$mysqli->close();
	echo json_encode( $data );
?>