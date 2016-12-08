/**
 * Created by APone on 2016/11/27.
 */
function returnLogin() {
    window.location.href = "?c=LoginAdmin&t=" + Math.random();
}

function autoReturn() {
    if (sec == 0) {
        returnLogin();
        clearInterval(interval);
    }
    document.getElementById("red").innerHTML = "" + sec;
    sec--;
}

document.getElementById("button").onclick=returnLogin;
document.onkeydown = function (e) {
  if (e.which == "13") {
    returnLogin();
  }
};

var sec = 3;
var interval = setInterval("autoReturn()", 1000);




