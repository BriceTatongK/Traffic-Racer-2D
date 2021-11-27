<?php

	// Start up your PHP Session
	session_start();
	// If the user is not logged in send him to the login form
	if ($_SESSION['loggedin'] != "YES") {
		
		$message = "YOU ARE NOT LOGGED IN !!!   ....... |°_°| ...... redirecting ....... ";
    header('Refresh: 1; url=index.php');
    exit($message);
	}

?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "author" content = "BrTK">
        <meta name = "keywords" content = "game">
        <title>TrafficRace Game</title>
        <link rel="icon" type="image/png"  href="./image_folder/icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./css_folder/game_css.css">
        <script type="text/javascript" src="./js_folder/game_js.js"></script>
        <script type="text/javascript" src="./js_folder/loader.js"></script>
        <script type="text/javascript" src="./js_folder/ajax_request.js"></script>
        <script type="text/javascript" src="./js_folder/popup.js"></script>
    </head>


    <body onload="begin()">

        <!-- ###| BOX LEFT SIDE |### -->
        <div id="infoArea" class="area">
            <!-- blocco score, livello, highscore -->
            <label for="box1"><b>LEVEL :</b></label>
            <input type="text" name="levelBox" value="0" class="box" id="box1" readonly><br>
            <label for="box2"><b>BEST SCORE : </b></label>
            <input type="text" name="highScoreBox" value="<?php print $_SESSION['score']; ?>" class="box" id="box2" readonly><br>
            <label for="box3"><b>CURRENT SCORE :</b></label>
            <input type="text" name="scoreBox" value="0" class="box" id="box3" readonly><br>

            <!--  informations player -->
            <label for="username">Player : </label>
            <input type="text" id="username" class="box" readonly value="<?php print $_SESSION["username"]; ?>"><br>
            <label for="id">ID : </label>
            <input type="text" id="id" class="box" readonly value="<?php print $_SESSION["id"]; ?>"><br>

            <!-- my car image -->
            <figure>
              <img src="./image_folder/police.jpg" alt="my car" style="width:20%">
              <figcaption>My Car.</figcaption>
            </figure><br>


            <!-- SOUND ELEMENTS -->
            <audio id="audio_road" loop>
              <source src="./sound_folder/road.mp3" type="audio/mp3"></audio>
            <audio id="audio_collision">
              <source src="./sound_folder/collision.mp3" type="audio/mp3"></audio>
            <audio id="audio_rank">
              <source src="./sound_folder/rank2.wav" type="audio/wav"></audio>
            <audio id="audio_gameOver1">
              <source src="./sound_folder/newScore.wav" type="audio/wav"></audio>
            <audio id="audio_gameOver2">
              <source src="./sound_folder/gameOver2.wav" type="audio/wav"></audio>
            <audio id="audio_drift">
              <source src="./sound_folder/drift.mp3" type="audio/mp3"></audio>
        </div>



        <!-- ###| BOX CENTER |### -->
        <div id="gameArea" class="area">
            <!--road box-->
            <div id="road"></div>
        </div>



        <!-- ###| BOX RIGTH SIDE |### -->
        <div id="rankingArea" class="area">
            <!-- ranking table -->
            <h1>LIVE RANKING</h1>
            <table id="list">
              <tr>
                <th class="cell">RANK</th><th class="cell">SCORE</th><th class="cell">NAME</th></tr>
              <tr>
                <td id="first" class="medal">1ST</td><td class="cell" id="s1"></td><td class="cell" id="u1"></td></tr>
              <tr>
                <td id="second" class="medal">2ND</td><td class="cell" id="s2"></td><td class="cell" id="u2"></td></tr>
              <tr>
                <td id="third" class="medal">3RD</td><td class="cell" id="s3"></td><td class="cell" id="u3"></td></tr>
            </table>

            <!-- buttons -->
            <!--<button id="gameOver" class="popup">GAME OVER</button>-->
            <button id="music">MUSIC</button><br>

            <button id="startScreen">NEW GAME</button><br>

            <!-- reset score button -->
            <button id="reset">RESET MY SCORE</button><br>

            <!-- logout button -->
            <form action="logout.php">
              <button type="submit" id="logout">LOGOUT</button>
            </form><br>
        </div>


        <!-- #####| POPUP |##### -->
        <div id="mypopup">
          <div id="content">
            <span id="close">X</span><br>
            <h2>Traffic Racer Game</h2>
            <!--<p id="instructions">-->
              <ul>
                <li> |left:  Arrow Left key</li>
                <li> |back:  Arrow Down key</li>
                <li> |rigth:  Arrow Right key</li>
                <li> |front: Arrow Up key</li>
              </ul>
            <!--</p>-->
          </div>
        </div>
    </body>
</html>