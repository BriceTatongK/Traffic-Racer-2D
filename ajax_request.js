//#############################################################
//## on dom ready, set up this function to be exsecuted each 5s
// to mantain the top tree list updated !
document.addEventListener('DOMContentLoaded', function() {

    console.log('log3');
    var id = setInterval(get_ranking, 5000);
});





//#######################################
// per compatibilità con tutti browser 
var XMLHttpFactories = [
    function() { return new XMLHttpRequest() },
    function() { return new ActiveXObject("Msxml3.XMLHTTP") },
    function() { return new ActiveXObject("Msxml2.XMLHTTP.6.0") },
    function() { return new ActiveXObject("Msxml2.XMLHTTP.3.0") },
    function() { return new ActiveXObject("Msxml2.XMLHTTP") },
    function() { return new ActiveXObject("Microsoft.XMLHTTP") }
];

function createXMLHTTPObject() {
    var xmlhttp = false;
    for (var i = 0; i < XMLHttpFactories.length; i++) {
        try {
            xmlhttp = XMLHttpFactories[i]();
        } catch (e) {
            continue;
        }
        break;
    }
    return xmlhttp;
}



//##############################################################################
var md5 = ""; // this value is just for the first call of get_ranking() function

function get_ranking() {

    // Initialize the HTTP request.
    var xhr = createXMLHTTPObject(); // new XMLHttpRequest();
    xhr.open('GET', 'ajax_gest.php', true);

    // Track the state changes of the request.
    xhr.onreadystatechange = function() {
        var DONE = 4; // readyState 4 means the request is done.
        var OK = 200; // status 200 is a successful return.
        if (xhr.readyState === DONE) {
            if (xhr.status === OK) {
                res = JSON.parse(xhr.responseText);
                if (md5 != res.md5) {
                    md5 = res.md5;

                    for (var i = 1; i <= res.dim; i++) {
                        document.getElementById("s" + i).textContent = res.rank[i].score;
                        document.getElementById("u" + i).textContent = res.rank[i].name;
                        //console.log("res.rank.score: "+res.rank[i].score+" res.rank[i].name: "+res.rank[i].name);
                    }

                    // clear the remaining places of rank list to avoid inconsistency on out screened informations
                    for (var i = ++res.dim; i <= 3; i++) {
                        document.getElementById("s" + i).textContent = "";
                        document.getElementById("u" + i).textContent = "";
                    }

                    // anim the table to alert the player about the new update on the ranking table
                    let table = document.getElementById("list");
                    table.classList.add("anim");

                    // play sound to alert
                    audio_rank.play();

                    // remove the class "anim" from the element 3s after the animation execution
                    // the animation is 2s long.
                    setTimeout(function() {
                        let table = document.getElementById("list");
                        table.classList.remove("anim");
                    }, 3000);
                }

            } else {
                console.log('Error: ' + xhr.status); // An error occurred during the request.
            }
        }
    };

    // Send the request to ajax_gest.php
    xhr.send(null);
}




//##########################################
// send a JSON file to the server
// JSON file contains : user's ID & new best score.
function sendScore(Id, NewScore) {

    var xhr = createXMLHTTPObject();    // new HttpRequest instance 
    xhr.open("POST", "receiveScore.php", true);     // true : async request and responce !
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function() {
        var DONE = 4;   // readyState 4 means the request is done.
        var OK = 200;   // status 200 is a successful return.
        if (xhr.readyState === DONE) {
            if (xhr.status === OK) {
                console.log(xhr.responseText);
            } else {
                console.log('Error: ' + xhr.status);    // An error occurred during the request.
            }
        }
    }
    xhr.send(JSON.stringify({ _id: Id, _score: NewScore }));
}



//####################################################
// reset my best score !
// send a GET method request to the server for the reset of this user score into the DB
function resetScore() {
    var xhr = createXMLHTTPObject(); // new HttpRequest instance 
    xhr.open("GET", "resetScore.php", true); // true : async request and responce !
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function() {
        var DONE = 4; // readyState 4 means the request is done.
        var OK = 200; // status 200 is a successful return.
        if (xhr.readyState === DONE) {
            if (xhr.status === OK) {
                console.log(xhr.responseText);
            } else {
                console.log('Error: ' + xhr.status); // An error occurred during the request.
            }
        }
    }

    xhr.send(null);
}