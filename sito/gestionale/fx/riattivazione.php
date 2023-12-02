<?php
//configurazione//
require_once "../../lib/funzioni.php";
$imageSite = getSite(). "/imgs/headermail.jpg";
$urlLanding = getSite();
$titoloLanding = "Libri Usati Crema";

$mancaNome = "Nome Mancante o Errato";
$mancaEmail = "Email Mancante o Errata";
$mancaPrivacy = "Consenso Privacy Mancante";
$noInvio = "Invio non riuscito";
// fine configurazione// 

$urlpost = $urlLanding;
$dataInvio = date( "d-m-Y H:i" );

$userId = isset($_GET['uid']) ? trim($_GET['uid']) : '';
$token = isset($_GET['t']) ? trim($_GET['t']) : '';
$passwordRequestId = isset($_GET['id']) ? trim($_GET['id']) : '';
 
$sql = "SELECT id,date_req FROM pwd_request WHERE id = ? AND token = ? AND id_utenti = ?";

connect_DB( $mysqli );

$query = $mysqli->prepare($sql);
$query->bind_param('sss',$passwordRequestId,$token,$userId);
$query->execute();
$result = $query->get_result();
if ($result->num_rows == 0) {
    echo 'Richiesta non valida.';
    exit;
}

$res = $result->fetch_assoc();
$dt = new DateTime($res[ 'date_req' ]);
$dt1 = new DateTime('now');

$interval = date_diff($dt1, $dt);
date_add($dt,new DateInterval('PT180S'));

if ( $dt1 > $dt) {
	echo "Richiesta scaduta.";
	exit;
}
 
$idd = $res['id'];
$query = "DELETE FROM pwd_request WHERE ID=$idd";
$mysqli->query( $query );

if ( isset( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] ) ) {
	if ( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] == "" ) {
		$ipnumb = getenv( "REMOTE_ADDR" );
	} else {
		$ipnumb = getenv( "HTTP_X_FORWARDED_FOR" );
	}
} else {
	$ipnumb = getenv( "REMOTE_ADDR" );
}

$query = "SELECT mail, nickname FROM utenti u inner join users us on u.ID=us.ID_utenti WHERE u.ID=$userId";
$mysqli->real_query( $query );
$res = $mysqli->use_result()->fetch_assoc();

$nickname = $res[ 'nickname' ]; 
$email = $res[ 'mail' ];
$password = newPwd();

$pass = md5( $password );
$query = "UPDATE users SET password='$pass' WHERE ID_utenti=$userId";
$mysqli->query( $query );

$mailTo = array( $email );
$error = 0;
$messaggio = "
		<html>
			<head>
				<title>Messaggio da " . $titoloLanding . "</title>
			</head>
			<style type='text/css'>
				a {color: #000; text-decoration: underline;}
				a:hover {color: #900; text-decoration: none ;}
				a:visited {color: #900;}
				a:active {color: #000;}
			</style>
				<body style='text-align:center; width:465px; margin:0 auto; font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px;'>
				<div style='border:1px solid #600; margin:16px 0 0 0; background:#fff; width:465px;'>
					<div style=' margin-bottom:16px; text-align:left;'><img src='" . $imageSite . "' border='0' width='465' height='60'></div>
					<p style='padding:0 6px; text-align:left;'>
						<em style='font-size:9px; color:#888'>Messaggio da " . $titoloLanding . "</em><br /><br />
						Il tuo profilo su Libri Usati Galilei Crema e' stato modificato. Le credenziali di accesso aggiornate sono:<br /><br />
						<strong>Utente</strong>: $nickname<br />
						<strong>Password</strong>: $password<br /><br />
					</p>
				</div>
			</body>
		</html>";


	$oggetto = "Conferma cambio password " . $titoloLanding;
/*
	include( "../hmm/htmlMimeMail.php" );
	$mail = new htmlMimeMail();
	$mail->setFrom( 'Libri Usati Crema '. getMail() );
	$mail->setSubject( $oggetto );
	$mail->setHtml( str_replace( "\\n", "<br />", str_replace( "\\r", "", $messaggio ) ) );
	if ( @$mail->send( $mailTo ) ) {
		$error = 0;
	} else {
		$error = 1;
		$typeError = $noInvio;
	}
*/
	require_once '../../vendor/autoload.php';
	$acc = accountMail();
	$transport = (new Swift_SmtpTransport($acc[0], 587))
		->setUsername($acc[1])
		->setPassword($acc[2]);
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($oggetto))
	->setFrom(getMail())
	->setTo($mailTo)
	->setContentType('text/html')
	->setBody(str_replace( "\\n", "<br />", str_replace( "\\r", "", $messaggio )))
	;
	$result = $mailer->send($message);	
	if ($result == 1) $error=0;
	else {
		$error = 1;
		$typeError = $noInvio;
	}

if ( $error == 1 ) {
	echo $typeError;
	echo $messaggio;
} else {
	$redirect = getSiteUtente() . "/pwdreset.php";
	echo "<script>window.location.href = '$redirect';</script>";
}

?>