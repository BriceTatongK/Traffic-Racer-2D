<?php

	include('MySQL.php');
	include('functions.php');

	session_start();

	//from my db i fetch all players with "score > 0"
	$query = "SELECT username, score FROM accounts WHERE score > 0";

	$mysqli = get_db();
	$result = $mysqli->query($query);

	if(!$result){
		echo "<script>alert('oups ! something went wrong !')</script>";

	}else{
		
		// creating an associative array " sorting['username'] => score " and apply the "aesort()" method on it.
		$sorting = array();
		while($row = $result->fetch_array()) {
			$sorting[$row['username']] = $row['score'];
		}

		// sort the values and maintain the keys
		arsort($sorting);


		// slice the array if elements are more than 3
		// because we need only the top 3.
		if (count($sorting) > 3) {
			array_slice($sorting, 0, 3, true);
		}

		// preparing $ranks (Associative multidimentional array) to be sent.
		$ranking = array();
		$i =1;
		foreach ($sorting as $key => $value) {
			$ranking[$i] = array('score' => $value, 'name' => $key);
			$i++;
		}

		// final array to encode before sending
		$res = array('rank' => $ranking, 'dim' => count($sorting), 'md5' => md5(json_encode($ranking)));

		// sending so that the client 
		print(json_encode($res));
	}

?>