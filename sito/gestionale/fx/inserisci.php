<?php
require_once "../../lib/funzioni.php";

$tabella = $_POST[ 'tabella' ];
$anno=getAnno();

if ( $tabella == "utenti" ) {
	$campi = $_POST[ 'campi' ];
	getPost( $campi, $valori );
	$valori = implode( ", ", $valori );

	$nome = ucfirst(trim($_POST[ 'nome' ]));
	$cognome = strtoupper(trim($_POST[ 'cognome' ] ));
	$mail = strtolower(trim($_POST[ 'mail' ]));
	$redirect = isset( $_POST[ 'redirect' ]) ? $_POST[ 'redirect' ] : "";

	if (isOnline() ) $next="../../errProfilo.php"; else $next="/libri/errProfilo.php";
	if ($nome == "" || $cognome=="")
		header("location: $next?motivo=nodata&redirect=$redirect");		
	//se è stata inserita una mail inizializzazione profilo online altrimenti solo registrazione utente
	else if ( isset( $_POST[ 'mail' ] ) && $_POST[ 'mail' ] != "" ) {
		$nick = strtolower( str_replace(' ', '', trim($_POST[ 'nome' ])) . "." . str_replace(' ', '', trim($_POST[ 'cognome' ] )));
		$mail = str_replace(' ', '', $_POST[ 'mail' ]);

		connect_DB( $mysqli );
		
		// controllo esistenza profilo in users
		$query = $mysqli->prepare("SELECT ut.nickname,u.mail FROM utenti u inner join users ut on u.ID=ut.ID_utenti WHERE nickname = ? AND mail = ?");
		$query->bind_param('ss',$nick,$mail);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			mysqli_close( $mysqli );
			header("location: $next?motivo=exist&mail=$mail&nick=$nick&redirect=$redirect");
		}
		else {
			// controllo esistenza profilo in standbyusers
			$query = $mysqli->prepare("SELECT ut.nickname,u.mail FROM utenti u inner join standbyusers ut on u.ID=ut.ID_utenti WHERE nickname = ? AND mail = ?");
			$query->bind_param('ss',$nick,$mail);
			$query->execute();
			$result = $query->get_result();
			if ($result->num_rows > 0) {
				mysqli_close( $mysqli );
				header("location: $next?motivo=standby&mail=$mail&nick=$nick&redirect=$redirect");
			}
			else {
				// controllo esistenza nickname
				$query = $mysqli->prepare("SELECT nickname FROM users where nickname=? union select nickname from standbyusers WHERE nickname = ?");
				$query->bind_param('ss',$nick,$nick);
				$query->execute();
				$result = $query->get_result();
				if ($result->num_rows > 0) {
					mysqli_close( $mysqli );
					header("location: $next?motivo=duplicate&mail=$mail&nick=$nick&redirect=$redirect");
				}
				else {
					// controllo anagrafica
					$query = $mysqli->prepare("SELECT nome,cognome FROM utenti where nome like ? and cognome like ?");
					$query->bind_param('ss',$nome,$cognome);
					$query->execute();
					$result = $query->get_result();
					if ($result->num_rows > 0) {
						mysqli_close( $mysqli );
				//		echo $valori[1] . $valori[2];
				//		exit();
						header("location: $next?motivo=found&nome=$nome&cognome=$cognome&redirect=$redirect");
					}
					else { // ok utente nuovo				
						$password = newPwd();
						$cod = random_string( 6 );
						$redirect = $_POST[ 'redirect' ];
						$pass = md5( $password );
						$valoriReg = valuesRegistro($nick);

						$query = "INSERT INTO registro(data, utente, descrizione) VALUES ($valoriReg, 'ins-$tabella'); 
								  INSERT INTO utenti($campi, ID_registro) VALUES ($valori, LAST_INSERT_ID());
								  INSERT INTO standbyusers(ID_utenti, cod, nickname, password) VALUES (LAST_INSERT_ID(), '$cod', '$nick', '$pass')";
						mysqli_multi_query( $mysqli, $query );
						mysqli_close( $mysqli );
						header( "location: inviaConferma.php?nickname=$nick&password=$password&cod=$cod&mail=$mail&redirect=$redirect" );
					}
				}
			}
		}
	} else {
		connect_DB( $mysqli );
		$query = $mysqli->prepare("SELECT nome,cognome FROM utenti where nome like ? and cognome like ?");
		$query->bind_param('ss',$nome,$cognome);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0) {
			mysqli_close( $mysqli );
			header("location: $next?motivo=found&nome=$nome&cognome=$cognome&nick=&mail=&redirect=$redirect");
		}
		else {		
			insertMultiQuery( $mysqli, $tabella, $campi, $valori );
			$redirect = $_POST[ 'redirect' ];
			header( "location: $redirect" );
		}
	}	
}
else {
	include "../../lib/check.php";

	if ( $tabella == "ritiro" ) {
		$ID = $_POST[ 'ID' ];
		$valori = explode( '; ', $_POST[ 'valori' ] );
		connect_DB( $mysqli );
		insertRP( $mysqli, "ritiro", "ID_catalogo, prezzo, descrizione, dove", $ID, $valori );
		
	//	connect_DB( $mysqli );
	//	$mysqli->real_query( "SELECT max(numero) as nn FROM ricevute WHERE year(quando)=" . $anno . " and tipo='R' and utente=" . $ID );
	//	$res = $mysqli->use_result()->fetch_assoc();
		echo " "; //$res['nn'];
	//	mysqli_close( $mysqli );

	} 
	else  if ( $tabella == "vendita" ) {
		$ID = $_POST[ 'ID' ];
		$valori = explode( '; ', $_POST[ 'valori' ] );
		
		connect_DB( $mysqli );
		$tot = updateVP( $mysqli, "vendita", $valori, $ID );
		
	//	connect_DB( $mysqli );
	//	$mysqli->real_query( "SELECT max(numero) as nn FROM ricevute WHERE year(quando)=" . $anno . " and tipo='V' and utente=" . $ID );
	//	$res = $mysqli->use_result()->fetch_assoc();
		echo $tot; // $res['nn'];
	//	mysqli_close( $mysqli );
	}
}
?>