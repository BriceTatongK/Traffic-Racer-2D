//	player and new game informations
const myCarSpeed = 7;
var started = false;
var currentScore;
var currentLevel;
var bestScore;
var X = 0;
var Y = 0;
var currentLinesSpeed;
var currentCarsSpeed;


//	sound variables
var audio_road, audio_collision, audio_gameOver2, audio_gameOver, audio_drift;

//	keys status
var listKeys = { ArrowUp: false, ArrowDown: false, ArrowLeft: false, ArrowRight: false };


//	cars and lines speeds per levels
var lineSpeed = { level_1: 5, level_2: 7, level_3: 12 };
var carSpeed = { level_1: 3, level_2: 6, level_3: 10 };


//	DOM's elements holders
var level, highscore, score, road, starScreen, myCar, id, user, reset, music;


// OnDOMload begin function
function begin() {

    console.log("log1");
    //########| get elements |######
    level = document.querySelector("#box1");
    highScore = document.querySelector("#box2");
    score = document.querySelector("#box3");
    road = document.querySelector("#road");
    starScreen = document.getElementById("startScreen");
    myCar = document.querySelector("#myCar");
    id = document.getElementById("id");
    user = document.getElementById("username");
    reset = document.querySelector("#reset");
    music = document.getElementById("music");


    //#####|  KeyPress and button Event Listeners set |#####
    starScreen.addEventListener("click", startGame, false);
    document.addEventListener("keydown", keyOn);
    document.addEventListener("keyup", keyOff);
    music.addEventListener("click", function(event){
        audio_road.muted = !audio_road.muted;
    })
    reset.addEventListener("click", function() {

        // clear current ranking data so that ajax(interval 5s) will renew it !
        for (var i = 1; i <= 3; i++) {
            elem = document.getElementById("s" + i);
            elem.textContent = "";
            elem = document.getElementById("u" + i);
            elem.textContent = "";
        }
        resetScore();
        highScore.value = 0;
        bestScore = 0;
        score.value = 0;
        currentScore = 0;

    }, false);

    // play audio playing !!
    audio_road.play();

    // open popup
    do_popup();
}



//########################
// drawing functions
function draw() {

    // disegna lines
    for (var i = 0; i < 4; i++) {
        var line = document.createElement("div");
        line.classList.add("lines");
        line.style.top = i * 300 + "px";
        road.appendChild(line);
    }

    // crea my car
    var myCar = document.createElement("div");
    myCar.classList.add("cars");
    myCar.setAttribute("id", "myCar");
    var r = 1167 - 50; //-50 car width
    var l = 451; 
    var x = getRndInteger(l, r);
    myCar.style.top = 400 + "px"; //400
    myCar.style.left = x + "px"; //800 posizione di partenza casuale(x) ! comunque entro i limiti della strada !
    road.appendChild(myCar);


    // crea le altre macchine
    for (var i = 0; i < 5; i++) {
        var cars = document.createElement("div");
        cars.classList.add("otherCar");

        var id = i + 1;
        cars.classList.add("design" + id);
        //cars.style.backgroundImage = "url('')";
        //console.log("position road: ", roadPosition.top, roadPosition.bottom, roadPosition.right, roadPosition.left);
        //position road:  -219 496 1167 451
        var r = 1167 - 50; //-50 car width
        var l = 451;
        var x = getRndInteger(l, r);
        var y = ((i + 1) * 219) * -1;

        cars.style.left = x + "px";
        cars.style.top = y + "px";
        road.appendChild(cars);
    }
}



//########################
//[min, max]
function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}


//##############################
// check e aggiorna level; speed(car, lines); number(cars)
function checkLevel(scoring) {
    if (scoring == 2000) {
        currentLevel = 2;
        currentLinesSpeed = lineSpeed.level_2;
        currentCarsSpeed = carSpeed.level_2;
    }

    if (scoring == 4000) {
        currentLevel = 3;
        currentLinesSpeed = lineSpeed.level_3;
        currentCarsSpeed = carSpeed.level_3;
    }
}


//############################
// end the game and show result 
function endGame() {

    started = false;
    if (currentScore > bestScore) {
        bestScore = currentScore;
        highScore.value = currentScore;

        // manda il new best socre al server che deve aggiornare il DB
        sendScore(id.value, currentScore);

        // play audio NEW BEST SCORE 
        audio_gameOver.play();
    } else {

        // play audio game over !!
        audio_gameOver2.play();
    }

    // show start button back
    showElem(starScreen);
    showElem(reset);
    showElem(logout);

    // clean the road
    removeAllChildNodes(road);
}



//########################
// show element
function showElem(element) {
    element.classList.remove("go");
    element.classList.add("come");
    element.classList.remove("hide");
}


//########################
// hide element
function hideElem(element) {
    element.classList.add("go");
    element.classList.remove("come");
    //element.classList.add("hide");
}


//########################
// remove child nodes from DOM node: "parent"
// questo metodo mi conviene rispetto a (road.innerHTML = "") perchè così cancello anche gli "event handler"
// associati a quelli NodesChild che rimuovo.
function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}



//########################
// collision() ## axis-aligned bounding box //src: MozillaDevN site
function collision(element) {
    var myCarBound = myCar.getBoundingClientRect();
    //console.log("position myCar: ", myCarBound.top, myCarBound.bottom, myCarBound.right, myCarBound.left);
    var otherCarBound = element.getBoundingClientRect();

    if (myCarBound.bottom < otherCarBound.top ||
        myCarBound.top > otherCarBound.bottom ||
        myCarBound.right < otherCarBound.left ||
        myCarBound.left > otherCarBound.right) {
        return false;
    } else {
        return true;
    }
}



//########################
// moving other cars
function otherCar_moving() {

    var cars = document.querySelectorAll(".otherCar");
    for (var i = 0; i < cars.length; i++) {

        //### collision check !! 
        if (collision(cars[i])) {

            // pause road's sound
            audio_road.pause();

            // play audio collision
            audio_collision.play();

            //end game and break the loop
            endGame();
            break;
        }

        var carPosition = cars[i].getBoundingClientRect();
        var top = carPosition.top;
        if (top > 715) {
            top = -239; //road.top
            //console.log("position road: ", roadPosition.top, roadPosition.bottom, roadPosition.right, roadPosition.left);
            //position road:  -239 506 1167 451
            var r = 1167 - 50; //-50 car width
            var l = 451;
            var x = getRndInteger(l, r);
            //console.log("MOVING X: "+x+" r:"+r+" l:"+l);
            cars[i].style.left = x + "px";
        }

        top += currentCarsSpeed;
        cars[i].style.top = top + "px";
    }
}



//########################
// move the road's lines 
function lines_moving() {
    var lines = document.querySelectorAll(".lines");
    Array.prototype.forEach.call(lines, function(element) { // lines non è un array ma un ListNode (oggetto)
        var linePosition = element.getBoundingClientRect();
        var top = linePosition.top;
        if (top > 715) { // road.bottom 715
            top = -338; //road.top -338
        }

        top += currentLinesSpeed;
        element.style.top = top + "px";
    })
}



//########################
// start game function
function startGame() {

    // draw road's elements
    draw();
    myCar = document.querySelector("#myCar");

    // hide some controls button
    hideElem(starScreen);
    hideElem(reset);
    hideElem(logout);

    // set cars and lines level 1 speed
    currentCarsSpeed = carSpeed.level_1;
    currentLinesSpeed = lineSpeed.level_1;
    myCar.classList.remove("hide");

    // aggiorno lo stato del gioco !
    started = true;

    // inizializzo le variabili del playing object 
    currentScore = 0;
    currentLevel = 1;
    score.value = 0;
    level.value = 0;
    bestScore = parseInt(highScore.value);

    // recupero la posizione della mia macchina
    X = myCar.offsetLeft;
    Y = myCar.offsetTop;

    // richiedo l'animazione per il movimento delle linee e delle macchine
    window.requestAnimationFrame(callBack);
}



//########################
function keyOn(event) {
    //se non viene gestita con questo handler, non deve essere 
    //gestista con l'handler di default ch'è di spostare il contenuto della pagina. (scroll bar)
    event.preventDefault();
    listKeys[event.key] = true; //oggiorno lo stato del tasto nell'oggetto "arrayKeys"
    console.log("key " + event.key + " on");
}



//########################
function keyOff(event) {
    event.preventDefault();
    listKeys[event.key] = false; //oggiorno lo stato del tasto nell'oggetto "arrayKeys"
    console.log("key " + event.key + " off");
}


//########################
// call back function for continous animation
function callBack() {
    //lines and cars moving 
    lines_moving();
    otherCar_moving();

    //uso questo metodo per recuperare le coordinate della posizione della strada,
    // per poter spostare la mia macchina rimanendo nella strada
    var roadPosition = road.getBoundingClientRect();

    //aggiorno X e Y stando attento che la macchina non vada oltre la strada !
    if (started) {

        if (listKeys.ArrowUp && Y > roadPosition.top) {
            Y -= myCarSpeed;
        }
        if (listKeys.ArrowDown && Y < roadPosition.bottom - myCar.offsetHeight) {
            Y += myCarSpeed;
        }
        if (listKeys.ArrowRight && X < roadPosition.right - myCar.offsetWidth) {
            X += myCarSpeed;
        }
        if (listKeys.ArrowLeft && X > roadPosition.left) {
            X -= myCarSpeed;
        }

        //per spostare la macchina
        myCar.style.left = X + "px";
        myCar.style.top = Y + "px";

        // new animations since i changed cars and lines positions !
        window.requestAnimationFrame(callBack);


        // update playing values
        currentScore++;
        checkLevel(currentScore);
        score.value = currentScore;
        level.value = currentLevel;
    }
}