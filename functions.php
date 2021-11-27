<?php

function checkResult($result, $query){
	
	if (!$result) {
		$mysqli = get_db();
		$message = 'Invalid query: ' .$mysqli->error . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
}




function showResult($result){
	while ($r = $result->fetch_assoc()){
		echo $r['id']. " ";
		//echo $r['username']. " ";
		echo $r['password'] . "<br>";
	}
	echo "<br>";
}



function filterSQLQuery ($string){
	if (get_magic_quotes_gpc()){
		/*Whenmagic_quotes are on for GPC (Get/Post/Cookie) operations,
		all ', ", and NUL's are escaped with a backslash automatically
		This feature has been removed in PHP 5.4.0 and the function returns
		always false*/
		$string = stripslashes ($string);
	}
	
	/* removes slashes (\)*/
	if (!is_numeric($string)){
		$mysqli = get_db();
		//$string = "'". $mysqli->real_escape_string($string)."'";
		$string = $mysqli->real_escape_string($string);
	}

	/*Escapes special characters in a string for use in an SQL statement
	in such a way that the SQL statements are sure ;*/
	return $string;
}




function get_db(){
	
	$nomeHost = "localhost:3307";
	$nomeUtente = "root";
	$password = "";
	$nomeDb = "players";
	
	static $db;
	if(!$db){
		$db = new mysqli($nomeHost, $nomeUtente, $password, $nomeDb);
		if ($db->connect_error){
			die('Connect Error (' . $db->connect_errno .')'. $db->connect_error);}
	}	
	//echo 'Success... ' . $mysqli->host_info . "\n";
	return $db;
}


?>