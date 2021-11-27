
<?php


// REGISTRATION

include('MySQL.php');
include('functions.php');

error_reporting(0);	// 
	
	if(isset($_POST['register'])){
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = md5($_POST['password']);
		$cpassword = md5($_POST['cpassword']);
		
		if($password  == $cpassword){
			// verifico se non esiste già nella base di dati
			$query = "SELECT * FROM accounts WHERE username='$username' OR email='$email'";
			$result = $mysqli->query($query);
			//var_dump($result);
			if($result->num_rows > 0){
				
				echo "<script>alert('oups! username or email already exists !')</script>";
				
			}else{
				// procedo con l'inserimento del nuovo user nella base di dati
				$query = "INSERT INTO accounts (username, password, email)
						 VALUES ('$username', '$password', '$email')";
				$mysqli = get_db();
				$result = $mysqli->query($query);
				
				if(!$result){
					echo "<script>alert('oups ! something went wrong !')</script>";
					
				}else{

					/*
					$_POST['username'] = "";
					$_POST['email'] = "";
					$_POST['password'] = "";
					$_POST['cpassword'] = "";
					*/

					echo "<script>alert('woow ! registration successful !')</script>";
					$message = "REGISTRATION SUCCESSFULL  |^_^| ........ redirecting ......";
				    header('Refresh: 2; url=index.php');
				    exit($message);
				}
			}
			
		}else{
			echo "<script>alert(' both passwords are not equal !')</script>";
		}	
	}

?>




<!DOCTYPE html>
<html lang="en">
	<head>
	
		<meta charset="utf-8">
    	<meta name = "author" content = "Brice Tatong K.">
    	<meta name = "keywords" content = "game">
		<title>Traffic Race Register</title>
		<link rel="shortcut icon" href="./image_folder/icon.png" />
		<link rel="stylesheet" type="text/css" href="./css_folder/login_register.css">
	</head>


	<body>

		<div id="registerBox" class="index">
			<form action="#" method="post">
				<fieldset>
				<legend>REGISTRATION</legend>
				<div id="username">
				<input class="powder" type="text" required placeholder="Username" name="username" value="<?php echo $username ?>"
				pattern="[A-Z][a-zìàòçùèé\s]{4,10}" title="4 to 10 letters ex: Brice">
				</div>
				<div id="email">
				<input class="powder" type="email" id="mail" required placeholder="Email" name="email" value="<?php echo $email ?>">
				</div>
				<div id="password">
				<input class="powder" type="password" required placeholder="Password" name="password" value="<?php echo $_POST['password']?>" id="pass2">
				</div>
				<div id="cpassword">
				<input class="powder" type="password" required placeholder="confirm Password" name="cpassword" value="<?php echo $_POST['cpassword']?>" id="pass1">
				</div><br><br>
				<div id="terms">
					<small>I have read and agree to the <u>Terms</u> & <u>Conditions</u>.
					I acknowledge the <u>Privacy</u> & <u>Policy</u>.
					<input type="checkbox" name="termini" value="" required>
					</small>
				</div>
				<div id="formButton">
					<input class="butt" type="submit" value="register" name="register">
				</div>
				</fieldset>
			</form>
			<span>
				have an account already ?<a href="index.php"> <b>Login Here</b></a>
			</span>
		</div>
	</body>

</html>