<?php

// elenco di tutti i libri dello stesso tipo di prenotato, con il loro stato

require_once "../../lib/funzioni.php";
require "../../lib/check.php";

$ID = $_POST[ 'ID' ];  // id prenotato
$anno= getAnno();
connect_DB( $mysqli );
$query = "SELECT ID_catalogo into @idc from prenotati where id=$ID;";

$query.= "SELECT m.id idm,m.prezzo,m.ID_vendita,r.idanno rritiro,p.idanno idp, p.stato from 
            magazzino m inner join ritiro r on m.ID_ritiro=r.id 
            left join (select prn.idanno, pr.stato, pr.ID_mag from prenotati pr inner join prenotazione prn on pr.ID_prenotazione=prn.ID) p on m.id=p.ID_mag           
            where m.ID_catalogo=@idc and m.anno=$anno;";
$res = queryMultipla($mysqli,$query);
$data = array();
$i=0 ;
while ( $row = $res->fetch_assoc() ) {
    $data[$i++] = [ $row['idm'], $row['prezzo'], $row['ID_vendita'], $row['rritiro'], $row['idp'], $row['stato'] ];
}
echo json_encode( $data ); 
mysqli_close( $mysqli );

?>