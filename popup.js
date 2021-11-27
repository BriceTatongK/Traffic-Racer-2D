var popup, popcontent, popclose, popinstructions;

document.addEventListener('DOMContentLoaded', function() {

    popup =  document.getElementById("mypopup");
    popcontent =  document.getElementById("content");
    popclose =  document.getElementById("close");
    popinstructions =  document.getElementById("instructions");
    console.log("log4");
    
});


function do_popup(){
     popclose.addEventListener("click", function(){
        popup.style.display = "none";
    }, false);

    window.onclick = function(event){
        if (event.target == popup) {
            popup.style.display = "none";
        }
    }

    popup.style.display = "block";
}