/**
 * Created by APone on 2016/11/30.
 */
function reset() {
  var xmlhttp;
  var password = document.getElementById("password");
  var again = document.getElementById("again");

  if (password.value == "") {
    document.getElementById("tips").innerHTML = "新密码不能为空";
    return;
  } else if (again.value == "") {
    document.getElementById("tips").innerHTML = "确认密码不能为空";
    return;
  } else if (again.value != password.value) {
    document.getElementById("tips").innerHTML = "两次密码不相同，请重新输入";
    return;
  }

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject();
  }

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var json = xmlhttp.responseText;
      var get = eval("(" + json + ")");
      if (get.success) {
        window.location.href = get.url;
      } else {
        document.getElementById("tips").innerHTML = get.message;
      }
    }
  }
  xmlhttp.open("POST", "./index.php?c=ResetPass&a=resetPass", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("password=" + password.value + "&again=" + again.value);
}

document.getElementById("button").onclick = reset;
document.onkeydown = function (e) {
  if (e.which == "13") {
    reset();
  }
};