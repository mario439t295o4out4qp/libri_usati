<?php
	$ID = "";
	session_start();
    if(empty($_SESSION['nickname'])){
		header("location: ../index.php");
		exit();
	}
	else{
		$nickname=$_SESSION['nickname'];
		
		require_once "../lib/funzioni.php";
		connect_DB( $mysqli );

		$sql = "SELECT u.ID, u.nome, u.cognome, u.mail, u.telefono, u.scuola, us.ID as IDu FROM utenti as u INNER JOIN users as us ON u.ID=us.ID_utenti WHERE us.nickname=?";
		$query = $mysqli->prepare($sql);
		$query->bind_param('s', $nickname);
		$query->execute();
		$result = $query->get_result();
		if ($result->num_rows > 0 ) {
			$res = $result->fetch_assoc();
			$ID=$res['ID'];
			$IDu=$res['IDu'];
			$nome=$res['nome'];
			$cognome=$res['cognome'];
			$telefono=$res['telefono'];
			$mail=$res['mail'];
			$scuola=$res['scuola'];
			$img=strtolower("../imgs/users/".$nome.$cognome.".jpg");
			if(!file_exists($img)) $img="imgs/user.jpg";
			$_SESSION[ 'ID' ] = $ID;
			$_SESSION['$nome']=$res['nome'];
			$_SESSION['$cognome']=$res['cognome'];
			$_SESSION['$img']=$img;
		}
		mysqli_close( $mysqli );
	}
?>