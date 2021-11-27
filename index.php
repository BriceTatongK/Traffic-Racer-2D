<?php
/*
________________________________________________
|##############################################|
|###############| MANUALE UTENTE |#############|
|##############################################|
______________________
STRUTTURA DEL PROGETTO 
######################
=>	index.php
=>	registration.php
=>	logout.php
=>	MySQL.php
=>	game.php
=>	ajax_gest.php
=>	resetScore.php
=>	receiveScore.php
=>	players.sql

=>	/css_folder:
		. game_css.css
		. login_register.css

=>	/js_folder:
		. ajax_request.js
		. game_js.js
		. loader.js
		. popup.js

=>	/image_folder:
		. contiene le immagini utilizzate dal CSS

=>	/sound_forlder:
		. contiene i sounds track caricati dal JS



_____________________
DESCRIZIONE DEL GIOCO
#####################
Lo scopo del gioco è il seguente: il giocatore prima deve registrarsi, poi fare il login, una volta
nella pagina principale del gioco, può avviare una partita. Deve giudare una macchina della polizia
sulla strada con i tasti arrow (Up, Left, Right, Down) evitando collisioni con altre macchine. 
il livello sale, la velocità delle macchine aumenta, e si guadagna più punti. Esiste una classifica top 3
sempre aggiornata, che permette al giocatore di competere con altri giocatori. enjoy :)


___________________
CREDENZIALI ACCESSO
###################
Username : "Brice" oppure "Emily"
Password : pass



______________
###| NOTE |###
##############
server = "localhost:3307" : per connettersi al DB.
port = 3307	: ho usato la porta 3307 perchè la 3306 era già occupata.



###############| FINE MANUALE |###############|
##############################################|



*/
// AUTHENTIFICATION
//#################
// including files 
include('MySQL.php');
include('functions.php');


// no error reporting check the use of this function. 
error_reporting(0);	// rmove all errors and notices !

if(isset($_POST['login'])){
	// 
	$username = $_POST['username'];
	$password = $_POST['password'];
	$user = $_POST['username'];

	// build query
	$query = sprintf("SELECT id, password, score FROM accounts WHERE username ='%s'", filterSQLQuery($user));

	// Perform Query
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0){
		$res = md5($_POST['password']);
		if ($res == $row['password']){
			
			session_start();
			$_SESSION['loggedin'] = "YES";
			$_SESSION['id'] = $row['id'];
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['score'] = $row['score'];
			
			/*
			$_POST['username'] = "";
			$_POST['password'] = "";
			*/
			$message = "LOGIN SUCCESSFULL :)  redirecting ...........";
    		header('Refresh: 3; url=game.php');
    		exit($message);
		}
		else{
			session_start();
			$_SESSION['loggedin'] = "NO";
			$_SESSION['id'] = $row['id'];

			/*
			$_POST['username'] = "";
			$_POST['password'] = "";
			*/
			$message = "INCORRECT PASSWORD  :(";
    		echo "<script>
    		alert('$message');
    		window.location.href='index.php';
    		</script>";
    		exit;
		}
	}
	else{
		session_start();
		$_SESSION['loggedin'] = "NO";
		$_SESSION['id'] = $row['id'];
		/*
		$_POST['username'] = "";
		$_POST['password'] = "";
		*/

		$message = "INCORRECT USERNAME  :(";
    	echo "<script>
    	alert('$message');
    	window.location.href='index.php';
    	</script>";
    	exit;
	}
}



?>




<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    	<meta name = "author" content = "BrTK">
    	<meta name = "keywords" content = "game">
		<title>Traffic Race Login</title>
		<link rel="shortcut icon" href="./image_folder/icon.png"/>
		<link rel="stylesheet" type="text/css" href="./css_folder/login_register.css">
	</head>

	<body>
		<div id="loginBox" class="index">
			<form action="#" method="post">
			<fieldset>
				<legend>SIGN IN</legend>
				<div>
				<input class="powder" type="text" name="username" placeholder="Username" id="username"
				 required pattern="[A-Z][a-zìàòçùèé\s]{4,10}" title="4 to 10 letters ex: Brice" value="<?php echo $username ?>">
				</div>
				<br>
				<div>
				<input class="powder" type="password" name="password" placeholder="Password" id="password" required value="<?php echo $password ?>">
				</div>
				<br>
				<input class="butt" type="submit" value="Login" name="login"><br>
				<br>
				no account ?<a href="registration.php"> <b>Register Here</b></a>
			</fieldset>
			</form>
		</div>
		
	</body>
</html>