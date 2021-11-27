<?php



$nomeHost = "localhost:3307";
$nomeUtente = "root";
$password = "";
$nomeDb = "players";
$mysqli = new mysqli($nomeHost, $nomeUtente, $password, $nomeDb);
if ($mysqli->connect_error){
	die('Connect Error (' . $mysqli->connect_errno .')'. $mysqli->connect_error);
}


?>