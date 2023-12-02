<?php

require_once "../../lib/funzioni.php";
$tabella = $_POST[ 'tabella' ];

if($tabella == "catalogo") {
	$ISBN = $_POST[ 'ISBN' ];
	connect_DB( $mysqli );
	if (strlen($ISBN."")==4) $wh= "like '%" . $ISBN . "'";
	else $wh = "='" . $ISBN . "'";
	$sql = "SELECT x.isbn,x.prezzo,x.titolo,x.id,group_concat(scuola separator '<br>') scuole FROM (";
	$sql .= "SELECT distinct c.*,scuola FROM catalogo AS c inner join adozioni a on c.id=a.id_catalogo WHERE c.ISBN " . $wh . " and a.anno=" . getAnno().") x group by isbn,prezzo,titolo,id";
//	$mysqli->real_query( $sql);
//	$res = $mysqli->use_result();
	$res = $mysqli->query($sql);
//	$num_rows = $res->num_rows;
	$data = array();
	$i=0;
	while ( $row = $res->fetch_assoc() ){
	//	if($row['ISBN']==$ISBN){
//	if ( $row = $res->fetch_assoc() ){		
			array_push($data,array( $row[ 'id' ], $row[ 'titolo' ], $row[ 'prezzo' ], $row['isbn'],$row['scuole'] ));
//			echo json_encode( $data );
//			exit();
		//}
		$i++;
	}
	if (! $i) array_push($data, array( 0, "Libro non registrato", 0, $ISBN ));
	echo json_encode( $data );
	exit();
} 
else if($tabella == "magazzino") {
	$codLib = $_POST[ 'codLib' ];
	connect_DB( $mysqli );
	$anno = getAnno();
	$query = "SELECT c.ISBN, c.titolo, m.ID_vendita, m.prezzo, m.ID_catalogo, m.descrizione, m.ID,pr.IdAnno idp FROM 
				magazzino m LEFT JOIN catalogo c ON c.ID=m.ID_catalogo LEFT JOIN prenotati p ON m.id=p.ID_mag and m.anno=p.anno
				LEFT JOIN prenotazione pr ON p.ID_prenotazione=pr.id
				WHERE m.ID=$codLib and m.anno=$anno";
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();
	if ( !$res ) {
		$data = array( 0, "Libro non registrato" );
		echo json_encode( $data );
	} else
		if ( $row = $res->fetch_assoc() ) {
			if($row[ 'ID_vendita' ]==NULL){
				if ( $row[ 'ID_catalogo' ] != 0) {
					$isbn = $row[ 'ISBN' ];
					$tit = htmlentities($row[ 'titolo' ], ENT_QUOTES, "ISO-8859-1");
				}
				else {
					$n = strpos($row[ 'descrizione' ],"::");
					if ( $n !== false) {
						$isbn =substr($row[ 'descrizione' ],$n+2);
						$tit = "(fuori cat.) " . substr($row[ 'descrizione' ],0,$n);
					}
				}
				$data = array( $isbn, $tit, $row[ 'prezzo' ], $row[ 'ID_catalogo' ], $row[ 'idp' ]);
				echo json_encode( $data );
				exit;
			}
			else{
				$data = array( -1, "Libro gi&agrave; venduto" );
				echo json_encode( $data );
				exit;
			}
		}
} 


?>