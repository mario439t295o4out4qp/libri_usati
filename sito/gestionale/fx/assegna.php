<?php

require_once "../../lib/funzioni.php";
include "../../lib/check.php";

$anno=getAnno();
$ut = $_GET[ 'ut' ];
if ($ut == 1) {
	connect_DB( $mysqli0 );
	$valoriReg = valuesRegistro($_SESSION['nickname']);
	$query = "INSERT INTO registro(data, utente, descrizione) VALUES ($valoriReg, 'assegna-prenotazioni'); ";
	$query .= "UPDATE prenotati set stato='assegnabile'  where id>0 and id in (select ID from
		(select p.ID, p.ID_catalogo, p.priorita n, maxp, m from prenotati p inner join (
		select ID_catalogo, max(priorita) maxp from prenotati  where anno=$anno group by ID_catalogo) pr_max 
		on p.ID_catalogo=pr_max.ID_catalogo inner join (
		select ID_catalogo, count(*) m from magazzino where magazzino.anno=$anno and isnull(magazzino.ID_vendita) group by ID_catalogo) conta 
		on p.ID_catalogo=conta.ID_catalogo left join magazzino mag on p.ID_mag=mag.ID where p.anno=$anno and isnull(mag.id_vendita)) s
		where s.n > (s.maxp - s.m))";
	$mysqli0->multi_query( $query );
	$mysqli0->close();
}
else {
	connect_DB( $mysqli );
	connect_DB( $mysqli1 );

	$query = "SELECT pr.Id,pr.ID_catalogo IdC, pr.priorita FROM prenotati pr left join magazzino mag on pr.ID_mag=mag.ID where pr.anno = $anno and pr.stato='assegnabile' and isnull(mag.id_vendita) ORDER BY IdC, priorita DESC";
	$res = $mysqli->query( $query );
//	$res = $mysqli->use_result();
//	echo $query."<br>";
//	echo "cursore: ".var_dump($res)."<br>";
	$IdC = -1;
	$res1 = null;
	$sql = "";
	while ( $row = $res->fetch_assoc() ) {
//		echo "cursore: ".$res->field_count."<br>";
		if ($IdC != $row['IdC']) {
			$IdC = $row['IdC'];
			$query1 = "SELECT Id from magazzino where ID_catalogo=$IdC and anno=$anno and isnull(ID_vendita) order by Id"; 
			if ($res1) $res1->free();
			$res1 = $mysqli1->query( $query1);
		}
		if ($row1 = $res1->fetch_assoc()) {
			$IdM = $row1['Id'];
			$Id = $row['Id'];
			$sql .= "UPDATE prenotati SET ID_mag=$IdM WHERE ID=$Id;";
//			echo $sql."<br>";
		}
	}
	$mysqli->close();
	$mysqli1->close();
	connect_DB( $mysqli2 );
	$mysqli2->multi_query($sql);
//	echo var_dump($mysqli2)."<br>";
	$mysqli2->close();
}
header( "location: ../prenotati.php" );

?>