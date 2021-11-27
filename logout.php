<?php

	session_start();
	session_destroy();	// end session

	/*
	unset($_SESSION['loggedin']);
	unset($_SESSION['id']);
	*/

	$message = "LOGGED OUT SUCCESSFULLY  |^_^| ......... redirecting .......... ";
    header('Refresh: 1; url=index.php');
    exit($message);

?>