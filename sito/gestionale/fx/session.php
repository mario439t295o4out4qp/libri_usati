<?php
	session_start();
    if(empty($_SESSION['nickname']) || $_SESSION['privilegi']<2){
		session_start();
		session_unset();
		session_destroy();
		header("location: index.php");
		exit();
	}
	else{
		$nickname=$_SESSION['nickname'];
		
		include "../lib/funzioni.php";
		connect_DB( $mysqli );
		$query = "SELECT u.ID, u.nome, u.cognome FROM utenti as u INNER JOIN users as us ON u.ID=us.ID_utenti WHERE us.nickname='$nickname'";
		$mysqli->real_query( $query );
		$res = $mysqli->use_result()->fetch_assoc();
		
		$ID=$res['ID'];
		$nome=$res['nome'];
		$cognome=$res['cognome'];
		$privilegi=$_SESSION['privilegi'];
		$img=strtolower("../imgs/users/".$nome.$cognome.".jpg");
//		if(!file_exists($img)) $img="../imgs/users/user.jpg";
		if(!file_exists($img)) $img="imgs/user.jpg";
	}
?>