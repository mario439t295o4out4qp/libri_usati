<?php 
require_once "../../lib/funzioni.php";
//include "funzioni.php";
connect_DB( $mysqli );
$nickname = $password = "";
if ( isset( $_POST[ 'nickname' ] ) && isset( $_POST[ 'password' ] ) ) {
	$nickname = $_POST[ 'nickname' ];
	$password = md5( $_POST[ 'password' ] );
	$query = "SELECT * FROM users WHERE privilegi>1";
	$mysqli->real_query( $query );
	$res = $mysqli->use_result();

	if ($res) while ( $row = $res->fetch_assoc() ) {
		if($row['nickname']==$nickname && $row['password']==$password){
			session_start();
			$_SESSION[ 'nickname' ] = $nickname;
			$_SESSION[ 'privilegi' ] = $row['privilegi'];
			$_SESSION[ 'ID' ] = $row['ID'];
			$_SESSION[ 'sede' ] = $row['sede'];
			header( "location: ../dashboard.php" );
			exit();
		}
	}
	header( "location: ../index.php" );
	exit();
}
?>