<?php

	include('MySQL.php');
	include('functions.php');

	session_start();

	$_SESSION['score'] = 0;
	$id = $_SESSION['id'];

	// connect to the database and and update the score.value of the user that correspond _id !
	$query = "UPDATE accounts SET score=0 WHERE id='$id'";

	$mysqli = get_db();
	$result = $mysqli->query($query);
				
	if(!$result){
		echo "<script>alert('oups ! something went wrong !')</script>";
	}
	else
		echo "successfully reset in DB !";


?>