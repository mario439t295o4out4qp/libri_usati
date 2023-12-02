<?php

//Manipolazione stringhe
function creaUpdate( $campi, $valori ) {
	$s = "";
	$campi = explode( ", ", $campi );
	$valori = explode( ", ", $valori );
	for ( $i = 0; $i < count( $campi ); $i++ ) {
		$s = $s . $campi[ $i ] . "=" . $valori[ $i ] . ", ";
	}
	$s = substr( $s, 0, strlen( $s ) - 2 ) . " ";
	//echo $s;
	return $s;
}

function cleanString( $s ) {
	$s = trim( $s );
	$s = htmlspecialchars_decode( htmlentities( $s, ENT_NOQUOTES, 'UTF-8' ), ENT_NOQUOTES );
	return $s;
}

function getPost( $campi, & $values ) {
	//echo $campi;
	$campi = explode( ", ", $campi );
	for ( $i = 0; $i < count( $campi ); $i++ )
		$values[ $i ] = "'" . $_POST[ $campi[ $i ] ] . "'";
}

function random_string( $length ) {
	$string = "";
	// genera una stringa casuale che ha lunghezza
	// uguale al multiplo di 32 successivo a $length
	for ( $i = 0; $i <= ( $length / 32 ); $i++ )
		$string .= md5( time() + rand( 0, 99 ) );
	// indice di partenza limite
	$max_start_index = ( 32 * $i ) - $length;
	// seleziona la stringa, utilizzando come indice iniziale
	// un valore tra 0 e $max_start_point
	$random_string = substr( $string, rand( 0, $max_start_index ), $length );
	return $random_string;
}

function isOnline() {  // usato per differenziare il DB 
	return false;  // localhost
//	return true;   // sito esterno
}

function backdoor(){
	return ($_SESSION['nickname'] === "davide.pagliarini") ;
}

function isDebug() {  // mette la scritta fuori servizio per inibire accesso areautente e gestionale
	return false;
}

function isTest() {
	return false;
//	return false;
}

function chkTest() {  // in fase di test - obbliga a inserire chiave ?pwd=dproc nell'url di chiamata
	if (isTest()) {
		$pwd = $_GET[ 'pwd' ];
		if ($pwd != 'dproc') { 
			header( "location: ../index_static.php" );
			exit();
		}
	}
	return true;
}

function prenotazioni_aperte() {
	return ($_SESSION['nickname']=="admin") or (date("Y-m-d")>="2023-08-05" && date("Y-m-d")<="2023-08-20") ;
}

function registrazioni_aperte() {
	return date("Y-m-d")>="2023-07-01" && date("Y-m-d")<="2023-08-20";
}
function registrazioni_aperte_str() {
	return "dal 1 luglio al 20 agosto 2023";
}
function ritiro_str() {
	return "Il 30 giugno, il 1, 7, 8, 14, 15 e 21 luglio	presso l’Istituto Galilei dalle 8:30 alle 12:30";
}
function prenotazioni_aperte_str() {
	return "5 agosto 2023";
}
function assegnazione_str() {
	return "il 20 agosto";
}
function consegna_str() {
	return "25 agosto 2023 ";
}
function consegna_orario_str() {
	return " dalle 8:30 alle 12:30";
}
function vendita_str() {
	return "dal 23 agosto al 26 agosto dalle 8.30 alle 12.30<br>dal 28 agosto al 2 settembre dalle 8.30 alle 12.30";
}
function disponibili(){
	return date("Y-m-d")>="2023-08-23";
}
function disponibili_str() {
	return "23 Agosto ";
}
function restituzione_str(){
	return "dal 7 al 9 settembre ";
}
function restituzione_orario_str(){
	return " dalle 08:30 alle 12:30 ";
}
function getAnnoSc() {
	return "2023/2024";
}

function getAnno() {
	return "2023";
}

function newPwd() {
	return "Gal-" . rand( 1000, 9999 );
}

function getGuadagno(){
	return 0.40;
}

function getVendita( $idCat){  
	if ($idCat==0) return 0.33;  // libri fuori catalogo
	else return 0.40;
}
/* In caso di modifica delle percentuali di vendita modificare anche la funzione
		function gR (idCat) {
				return ( idCat == 0 ? 0.40 : 0.50 );
		}
	nel file script.js e le query nei file dashboard.php e riassunto.php
*/
function getRicavo( $idCat){ // in caso di variazione cambiare anche la function SQL nel database
	if ($idCat==0) return 0.40; // libri fuori catalogo
	else return 0.50;
}

function getSede( $sede) {
	$dove = "Galilei";  //  == 1 Galilei
	if (! is_null($sede)) {
		if ( $sede == 3 ) $dove = "Pacioli";
		if ( $sede == 7 ) $dove = "Consultorio";
	}
	return $dove;
}

function ckSede( $sede) {
	return ( $sede==1 || $sede==3 || $sede==7) ;
}

function getSite(){
	if (!isOnline()) return "/libri";
	else return "https://www.libriusaticrema.it"; // "https://libriusati.galileicrema.org";
}
function getSiteUtente(){
	if (!isOnline()) return "http://localhost/areautente";
	else return "https://www.libriusaticrema.it/areautente"; // "https://libriusati.galileicrema.org/areautente";
}
function getSiteGestione(){
	if (!isOnline()) return "http://localhost/gestionale";
	else return "https://www.libriusaticrema.it/gestionale"; 
}
function getMail(){
	return "info@libriusaticrema.it";
}
function accountMail(){
	return array('mail.tophost.it','libriusaticrema.it','');
}
//Database
function connect_DB( & $mysqli ) {
	if (!isOnline()) 
		$mysqli = new mysqli("localhost", "root","","libriexp");
	else
		$mysqli = new mysqli("sql.libriusaticrema.it", "","","");
	if ( $mysqli->connect_errno ) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
}

function insert( $mysqli, $tabella, $campi, $valori ) {
	$valori = cleanString( $valori );
	$query = "INSERT INTO $tabella($campi) VALUES ($valori)";
	$mysqli->query( $query );
	mysqli_close( $mysqli );
	return $query;
}

function update( $mysqli, $tabella, $campi, $valori, $ID ) {
	$valori = cleanString( $valori );
	$s = creaUpdate( $campi, $valori );
	$query = "UPDATE $tabella SET $s WHERE ID=$ID";
//	echo $query;
	$mysqli->query( $query );
	mysqli_close( $mysqli );
	return $query;
}

function deleteTab( $mysqli, $tabella, $ID ) {
	$mysqli->query( "DELETE FROM $tabella WHERE ID=$ID" );
	mysqli_close( $mysqli );
//	return $query;
}

//Funzioni gestionale
function valuesRegistro($nick) {
	$dateTime = date( "Y-m-d H:i:s" );
//	session_start();
	$utente = $nick; // $_SESSION[ 'nickname' ];
	$valori = "'" . $dateTime . "', '" . $utente . "'";
	return $valori;
}

function insertMultiQuery( $mysqli, $tabella, $campi, $valori ) {
	$valori = cleanString( $valori );
	$valoriReg = valuesRegistro('');
	$query = "INSERT INTO registro(data, utente, descrizione) VALUES ($valoriReg, 'ins-$tabella'); 
              INSERT INTO $tabella($campi, ID_registro) VALUES ($valori, LAST_INSERT_ID())";
	mysqli_multi_query( $mysqli, $query );
	mysqli_close( $mysqli );
	return $query;
}

function insertRP( $mysqli, $tabella, $campi, $utente, $valori ) {
	$anno=getAnno();
	for ( $i = 0; $i < count( $valori ); $i++ ) $valori[ $i ] = cleanString( $valori[ $i ] );
	$valoriReg = valuesRegistro($utente);
	$query = "INSERT INTO registro(data, utente, descrizione) VALUES ($valoriReg, 'ins-$tabella');
			  SELECT @n:=ifnull(max(idanno),0)+1 FROM $tabella where year(data)=$anno;	
              INSERT INTO $tabella(data, ID_utente, ID_registro,IdAnno) VALUES ('" . date( "Y-m-d H:i:s" ) . "', $utente, LAST_INSERT_ID(), @n); SET @ID_operazione = LAST_INSERT_ID()";
	$tipo = strtoupper(substr($tabella,0,1));
	for ( $i = 0; $i < count( $valori ); $i++ )
		if ( $tabella == "ritiro" ) $query .= ";" . " INSERT INTO magazzino($campi, ID_ritiro, anno) VALUES (" . $valori[ $i ] . ", @ID_operazione, $anno)";
		else {
			$query .= ";" . " INSERT INTO prenotati($campi, ID_prenotazione, priorita, anno) VALUES (" . $valori[ $i ] . ", @ID_operazione, 1, $anno)";
// ad ogni nuova prenotazione incrementa la priorità di chi ha già prenotato il libro			
			$query .= ";" . " UPDATE prenotati SET priorita=priorita+1 WHERE ID_catalogo=" . $valori[ $i] ." AND id_prenotazione<>@ID_operazione AND anno=" . $anno ;
		}
	$query .= "; INSERT INTO ricevute(utente,numero,quando,tipo) VALUES ($utente,@ID_OPERAZIONE,now(),'".$tipo."')";
	mysqli_multi_query( $mysqli, $query );
	mysqli_close( $mysqli );
}

function updateVP( $mysqli, $tabella, $ID, $utente ) {
	$anno=getAnno();
	$valoriReg = valuesRegistro($utente);
	$query = "INSERT INTO registro(data, utente, descrizione) VALUES ($valoriReg, 'ins-$tabella'); 
			  SELECT @n:=ifnull(max(idanno),0)+1 FROM $tabella where year(data)=$anno;	
              INSERT INTO $tabella(data, ID_utente, ID_registro,IdAnno) VALUES ('" . date( "Y-m-d H:i:s" ) . "', $utente, LAST_INSERT_ID(), @n); SET @ID_operazione = LAST_INSERT_ID()";
	$tipo = strtoupper(substr($tabella,0,1));	
	for ( $i = 0; $i < count( $ID ); $i++ ) {
		$query .= "; " . "UPDATE magazzino SET ID_vendita=@ID_operazione WHERE ID=" . $ID[ $i ];		
	}
	$query .= "; INSERT INTO ricevute(utente,numero,quando,tipo) VALUES ($utente,@ID_OPERAZIONE,now(),'".$tipo."');";
	if ($tabella == "vendita") 
		$query .= "SELECT round(sum(m.prezzo*getRicavo(m.id)),2) tot FROM vendita AS o INNER JOIN magazzino AS m ON o.ID = m.ID_vendita WHERE o.ID=@ID_operazione;";
	else
		$query .= "SELECT 0 tot ;";
	$res = queryMultipla( $mysqli, $query );
	$row = $res->fetch_assoc();
	mysqli_close( $mysqli );
	return $row['tot'];
}

function queryMultipla($mysqli, $sql){ // il tipo del valore di ritorno dipende dall'ultima query inserita bool se INSERT, result se SELECT
	$mysqli->multi_query($sql);
	while ($mysqli->more_results()) {
		$mysqli->store_result(); $mysqli->next_result();
	}
	$res = $mysqli->store_result();	
	return $res;
}

?>