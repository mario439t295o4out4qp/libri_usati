<?php

// carica le prenotazioni di un utente sulla pagina della vendita

require_once "../../lib/funzioni.php";
include "../../lib/check.php";

	$ID = $_POST[ 'ID' ];
	connect_DB( $mysqli );
	$res = $mysqli->use_result();
	$anno = getAnno();
	$query = "select a.ISBN, a.titolo, mm.ID idMM, mm.prezzo, mm.ID_catalogo idC from (select c.id, pti.id idP, c.ISBN, c.titolo,
(select id from magazzino m where m.ID_catalogo=c.id and isnull(m.ID_vendita) and m.anno=$anno limit 1) idM
from prenotazione pne inner join prenotati pti on pne.ID = pti.ID_prenotazione
inner join catalogo c on pti.ID_catalogo=c.ID
where pne.ID_utente=$ID and pti.stato <>'') a
inner join magazzino mm on a.idM=mm.ID";

	$query = "select c.ISBN, c.titolo, mm.ID idMM, mm.prezzo, mm.ID_catalogo idC, pne.IdAnno idP 
from prenotazione pne inner join prenotati pti on pne.ID = pti.ID_prenotazione
inner join catalogo c on pti.ID_catalogo=c.ID
inner join magazzino mm on pti.ID_mag = mm.ID
where pne.ID_utente=$ID and pti.stato <>'' and mm.anno=$anno and isnull(mm.ID_vendita)";
		
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();
	if ( !$res ) {
		$data = array( 0, "Nessuna prenotazione" );
		echo json_encode( $data );
	} else {
		$data = array();
		$i=0 ;
		while ( $row = $res->fetch_assoc() ) {
			$data[$i] = array( $row['ISBN'], $row['titolo'], $row[ 'prezzo' ], $row['idC'], $row['idMM'], $row['idP'] );
			$i++;
		}
		echo json_encode( $data );
} 

?>