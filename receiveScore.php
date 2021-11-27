<?php

	include('MySQL.php');
	include('functions.php');

	session_start();

	// Takes raw data from the request
	$json = file_get_contents('php://input');

	// data format sent from client side. <=  xhr.send(JSON.stringify({ _id: Id, _score: NewScore })) 
	// Converts it into a PHP object
	$data = json_decode($json/*, true*/); // "true" mi restituisce un Associative array  | a php object !

	// update the session variables
	$_SESSION['score'] = $data->_score;

	// connect to the database and update the "score.value" of the user id:  "_id"
	$query = "UPDATE accounts SET score='$data->_score' WHERE id='$data->_id'";

	$mysqli = get_db();	 // just in case we lost the connection with DB
	$result = $mysqli->query($query);
				
	if(!$result){
		echo "<script>alert('oups ! something went wrong !')</script>";
	}
	else
		echo "successfully insert in DB !";


?>