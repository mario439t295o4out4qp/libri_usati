<?php
	require_once "../../lib/funzioni.php";

//configurazione//
	$imageSite =  getSite() . "/imgs/headermail.jpg";
	$urlLanding = getSite();
	$titoloLanding = "Libri Usati Crema";

	$mancaNome = "Nome Mancante o Errato";
	$mancaEmail = "Email Mancante o Errata";
	$mancaPrivacy = "Consenso Privacy Mancante";
	$noInvio = "Invio non riuscito";
// fine configurazione// 

	$urlpost = $urlLanding;
	$dataInvio = date( "d-m-Y H:i" );

	if ( isset( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] ) ) {
		if ( $_SERVER[ "HTTP_X_FORWARDED_FOR" ] == "" ) {
			$ipnumb = getenv( "REMOTE_ADDR" );
		} else {
			$ipnumb = getenv( "HTTP_X_FORWARDED_FOR" );
		}
	} 
	else {
		$ipnumb = getenv( "REMOTE_ADDR" );
	}

	$nickname = $_GET[ 'nickname' ];
	$password = $_GET[ 'password' ];
	$email = $_GET[ 'mail' ];
	$cod = $_GET[ 'cod' ];

//if ($email != "dav.pagliarini@gmail.com")
//	exit;

connect_DB( $mysqli );

$query = $mysqli->prepare("SELECT ID FROM standbyusers WHERE nickname = ? AND cod = ?");
$query->bind_param('ss',$nickname,$cod);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
	$res = $result->fetch_assoc();
	$ID = $res[ 'ID' ];

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
					<div style='margin-bottom:16px; text-align:left;'><img src='" . $imageSite . "' border='0' width='465' height='60'></div>
					<p style='padding:0 6px; text-align:left;'>
						<em style='font-size:9px; color:#888'>Messaggio da " . $titoloLanding . "</em><br /><br />
						Ciao, sei stato registrato su Libri Usati Galilei Crema, ora puoi monitorare le tue ricevute e prenotare i libri direttamente dal nostro 
						<a href='" . getSite(). "'> sito internet</a>. 
						Clicca sul link sottostante per confermare la registrazione e attivare il tuo profilo, le credenziali di accesso saranno:<br /><br />
						<strong>Utente</strong>: $nickname<br />
						<strong>Password</strong>: $password<br /><br />
						
						<a href='" . getSiteGestione() . "/fx/attivazione.php?ID=$ID&cod=$cod'>Clicca qui per confermare la registrazione e attivare il profilo</a><br />
					</p>
				</div>
			</body>
		</html>";

//		include( "../hmm/htmlMimeMail.php" );
		$oggetto = "Conferma registrazione " . $titoloLanding;
/*		$mail = new htmlMimeMail();
		$mail->setFrom( 'Libri Usati Galilei <'.getMail().'>' );
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
		$redirect = $_GET[ 'redirect' ];
		echo "<script>window.location.href = '$redirect';</script>";
	}
}
else {
	echo "impossibile inviare conferma via mail";
}
?>