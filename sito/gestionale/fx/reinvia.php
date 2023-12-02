<?php
//configurazione//
require_once "../../lib/funzioni.php";

$imageSite = getSite() . "/imgs/headermail.jpg";
$urlLanding = getSite();
$titoloLanding = "Libri Usati Crema";

$mancaNome = "Nome Mancante o Errato";
$mancaEmail = "Email Mancante o Errata";
$mancaPrivacy = "Consenso Privacy Mancante";
$noInvio = "Invio non riuscito";
// fine configurazione// 

//Get the name that is being searched for.
$email = isset($_POST['mail']) ? trim($_POST['mail']) : '';

connect_DB( $mysqli );

$query = $mysqli->prepare("SELECT u.ID FROM utenti u inner join users us on u.ID=us.ID_utenti WHERE u.mail = ? and us.attivo=1");
$query->bind_param('s',$email);
$query->execute();
$result = $query->get_result();
if ($result->num_rows == 0) {
    echo 'Indirizzo email non trovato o associato ad utente disabilitato.';
    exit;
}
 
$res = $result->fetch_assoc();
$ID = $res[ 'ID' ];
 
//Create a secure token for this forgot password request.
$token = openssl_random_pseudo_bytes(16);
$token = bin2hex($token);
 
//The SQL statement.
$insertSql = "INSERT INTO pwd_request (id_utenti, date_req, token) VALUES (?, ?, ?)";
$query = $mysqli->prepare($insertSql);
$dt=date("Y-m-d H:i:s");
$query->bind_param('sss',$ID,$dt,$token);
$query->execute();
 
$passwordRequestId = $last_id = $mysqli->insert_id;
 
$verifyScript = getSiteGestione() . '/fx/riattivazione.php';
 
//The link that we will send the user via email.
$linkToSend = $verifyScript . '?uid=' . $ID . '&id=' . $passwordRequestId . '&t=' . $token;
 
// echo $linkToSend;
$urlpost = $urlLanding;
$dataInvio = date( "d-m-Y H:i" );

if ( isset( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] ) ) {
	if ( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] == "" ) {
		$ipnumb = getenv( "REMOTE_ADDR" );
	} else {
		$ipnumb = getenv( "HTTP_X_FORWARDED_FOR" );
	}
} else {
	$ipnumb = getenv( "REMOTE_ADDR" );
}

$mailTo = array( $email );
$error = 0;
if ( $error == 0 ) {
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
						E' stata attivata la procedura per il cambio password dell'utente associato a questa mail.<br />
						<a href='$linkToSend'>Clicca qui per confermare il reinvio della credenziali del profilo</a><br />
					</p>
					<p style='text-align:center;'>
					<br>Se invece ricevi questa mail, ma non hai attivato tu la modifica, contatta per favore <br>".getMail()."<br> 
					per segnalare l'eventuale abuso della tua casella di posta.</p>
				</div>
			</body>
		</html>";
	$oggetto = "Richiesta reinvio credenziali " . $titoloLanding;

/*
	include( "../hmm/htmlMimeMail.php" );
	$mail = new htmlMimeMail();
	$mail->setFrom( 'Libri Usati Crema <' . getMail().'>' );
	$mail->setSubject( $oggetto );
	$mail->setHtml( str_replace( "\\n", "<br />", str_replace( "\\r", "", $messaggio ) ) );
	if ( @$mail->send( $mailTo ) ) {
		$error = 0;
	} else {
		$error = 1;
		$typeError = $noInvio;
	} */
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
}
if ( $error == 1 ) {
	echo $typeError;
	echo $messaggio;
} else {
	$redirect = getSiteUtente() . "/pwdrequest.php";
	echo "<script>window.location.href = '$redirect';</script>";
}

?>