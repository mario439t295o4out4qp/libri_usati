<?php
//configurazione//
include("../../lib/funzioni.php");
//require_once '../../vendor/autoload.php';

$secret = "6LfL98cmAAAAANstFW4w4fUruEAGKfukU8t6g_sX";
// empty response

if(isset($_POST['g-recaptcha-response'])){
	$captcha=$_POST['g-recaptcha-response'];
  }
  if(!$captcha){
	echo '<h2>Per favore controlla i dati del form.</h2>';
	exit;
  }
  $ip = $_SERVER['REMOTE_ADDR'];
  // post request to server
  $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret) .  '&response=' . urlencode($captcha);
  $response = file_get_contents($url);
  $responseKeys = json_decode($response,true);
  // should return JSON with success as true
  if( ! $responseKeys["success"]) {
	die( "" );
 }
$imageSite = getSite() . "/imgs/headermail.jpg";
$urlLanding = getSite();
$titoloLanding = "Libri Usati Crema";
$mailTo = array( getMail() );

$mancaNome = "Nome Mancante o Errato";
$mancaEmail = "Email Mancante o Errata";
$mancaPrivacy = "Consenso Privacy Mancante";
$noInvio = "Invio non riuscito";
// fine configurazione// 

function pulisci( $queryban ) {
	$queryban = htmlentities( $queryban, ENT_QUOTES, 'UTF-8' );
	return $queryban;
}

function controlla_post( $var ) {
	$var = strip_tags( $var );
	return $var;
}

function is_email( $var ) {
	if ( isset( $var ) ) {
		return (filter_var(trim($var), FILTER_VALIDATE_EMAIL));
	} else {
		return false;
	}
}

if ( !isset( $_POST[ 'nome' ] ) && !isset( $_POST[ 'email' ] ) && !isset( $_POST[ 'PrivacyBox' ] ) ) {
	die( "" );
}
$error = 0;
$typeError = "";

$nom = pulisci( $_POST[ 'nome' ] );
if ( !isset( $nom )OR trim( $nom ) == "" ) {
	$error = 1;
	echo $mancaNome;
	die( "" );
}
$em = pulisci( $_POST[ 'email' ] );
if ( !isset( $em )OR trim( $em ) == ""
	OR!is_email( $em ) ) {
	$error = 1;
	echo $mancaEmail;
	die( "" );
}

$prv = pulisci( $_POST[ 'PrivacyBox' ] );
if ( !isset( $prv )OR trim( $prv ) != "y" ) {
	$error = 1;
	echo $mancaPrivacy;
	die( "" );
}
$com = pulisci( $_POST[ 'messaggio' ] );
if ( trim( $com ) == "" ) {
	$com = "--";
}

$cognome = pulisci( $_POST[ 'cognome' ] );
if ( trim( $cognome ) == "" ) {
	$cognome = "--";
}

$tel = pulisci( $_POST[ 'telefono' ] );
$telefono = "";
if ( trim( $tel ) == "" ) {
	$tel = "--";
} else {
	$telefono = $tel;
}

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
					<div style='margin-bottom:16px; text-align:left;'><img src='" . $imageSite . "' border='0' width='465' height='60'></div>
					<p style='padding:0 6px; text-align:left;'>
						<em style='font-size:9px; color:#888'>Messaggio da " . $titoloLanding . "</em><br /><br />
						<strong>Nome</strong>: $nom<br />
						<strong>Cognome</strong>: $cognome<br />
						<strong>Email</strong>: $em<br />
						<strong>Telefono</strong>: $tel<br /><br />
						<strong>Url invio</strong>: $urlpost<br />
						<strong>IP</strong>: $ipnumb<br />
						<strong>Data</strong>: $dataInvio<br /><br />
						<strong>Messaggio</strong>: $com
					</p>
				</div>
			</body>
		</html>";

/*
	include( "../hmm/htmlMimeMail.php" );
	$oggetto = "Messaggio da " . $titoloLanding;
	$mail = new htmlMimeMail();
	$mail->setFrom( $titoloLanding . getMail() );
	$mail->setSubject( $oggetto );
	$mail->setHtml( str_replace( "\\n", "<br />", str_replace( "\\r", "", $messaggio ) ) );
	$mailTo[1]=$em;
	if ( @$mail->send( $mailTo ) ) {
		$error = 0;
	} else {
		$error = 1;
		$typeError = $noInvio;
	}
	*/
	$mailTo[1]=$em;
	$oggetto = "Messaggio da " . $titoloLanding;

	require_once '../../vendor/autoload.php';
	$acc = accountMail();
	$transport = (new Swift_SmtpTransport($acc[0], 587))
		->setUsername($acc[1])
		->setPassword($acc[2]);
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($oggetto))
	  ->setFrom([getMail() => $titoloLanding])
	  ->setTo($mailTo)
	  ->setContentType('text/html')
	  ->setBody($messaggio)
	  ;
	$result = $mailer->send($message);	
	if ($result == 2) $error=0;
}
if ( $error == 1 ) {
	echo $typeError;
} else {
	echo "RICHIESTA DI CONTATTO INVIATA<br>";
	echo "Copia del messaggio viene inviata anche al mittente  $em<br>";
}

echo "<br><a href=\"../../index.php\">Torna al sito</a>";
?>