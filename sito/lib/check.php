<?php
	session_start();
    if(empty($_SESSION['nickname'])){
		header("location: ../index.php");
		exit();
	}
?>