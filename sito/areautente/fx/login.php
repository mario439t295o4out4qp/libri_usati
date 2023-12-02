<?php 
require_once "../../lib/funzioni.php";

$nickname = $password = "";
if ( isset( $_POST[ 'nickname' ] ) && isset( $_POST[ 'password' ] ) ) {
	connect_DB( $mysqli );
	$nickname = $_POST[ 'nickname' ];
	$password = md5( $_POST[ 'password' ] );
	$sql = "SELECT * FROM users where nickname=? and password=?";
	$query = $mysqli->prepare($sql);
	$query->bind_param('ss', $nickname,$password);
	$query->execute();
	$res = $query->get_result();
	mysqli_close( $mysqli );
	if ($res->num_rows > 0 ) {
		session_start();
		if ($nickname == 'admin') $nickname="annalisa.cassia";
		$_SESSION[ 'nickname' ] = $nickname;
		$_SESSION[ 'privilegi' ] = 1;
		header( "location: ../dashboard.php" );
		exit();
	}
	header( "location: ../index.php" );
	exit();
}
?>