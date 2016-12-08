function login() {//登录，post函数
  var xmlhttp;
  var userName = document.getElementById("user");
  var password = document.getElementById("password");

  if (userName.value == "" || password.value == "") {
    document.getElementById("tips").innerHTML = "账号或密码不能为空";
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
      var login = eval("(" + json + ")");
      if (login.success) {
        checked();
        window.location.href = login.url;
      } else {
        password.value = "";
        document.getElementById("tips").innerHTML = login.message;
      }
    }
  }
  xmlhttp.open("POST", "./index.php?c=LoginAdmin&t=" + Math.random(), true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("userName=" + userName.value + "&password=" + password.value);
}

document.getElementById("button").onclick = login;//按键登录

function pressLogin(e) {//键盘登录
  $(this).keydown(function (e) {
    if (e.which == "13") {
      login();
    }
  })
}//以上为登录功能

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires;
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name) == 0)
      return c.substring(name.length, c.length);
  }
  return "";
}

function checked() {
  var user = getCookie("username");
  if ($("#checkbox").is(':checked')) {
    user = $("#user").val();
    setCookie("username", user, 365);
  }
}

function remember() {
  var user = getCookie("username");
  $("#user").val(user);
}//以上为记住密码功能

function addLoadEvent(func) {//dom树加载完时加载
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function () {
      oldonload();
      func();
    }
  }
}//什么要加载的都放在这

addLoadEvent(remember);
addLoadEvent(pressLogin);
