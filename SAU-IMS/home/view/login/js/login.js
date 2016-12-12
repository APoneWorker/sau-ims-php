$(function() {
  //登录
  function login() {

      var xmlhttp;
      var userName = document.getElementById("user");
      var password = document.getElementById("password");

      if (userName.value == "" || password.value == "") {
          //document.getElementById("tips").innerHTML = "用户或密码不能为空";
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
              var login=eval("("+json+")");
              if(login.success){
                  window.location.href=login.url;
              }else{
                  password.value="";
                  //document.getElementById("tips").innerHTML=login.message;
              }
          }
      }
      xmlhttp.open("POST", "./index.php?c=LoginUser&t="+Math.random(), true);

      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.send("userName=" + userName.value + "&password=" + password.value);
  }

  $("#button").click(function () {
    login();
  });
  //document.getElementById("button").onclick=login;

  // 清除对应输入框的内容
  $("#clearName").click(function() {
    $("#user").val("");
    $("#clearName").css({
      "display": "none"
    })
  })
  $("#clearPwd").click(function() {
    $("#password").val("");
    $("#clearPwd").css({
        "display": "none"
      })
  })
  $("#clearVerification").click(function() {
    $("#loVerification").val("");
    $("#clearVerification").css({
        "display": "none"
      })
  });

  // 控制明文密码的显示与隐藏
  $("#showPwd").click(function() {
    var pwdType = document.getElementById("password").type;
    if (pwdType == "password") {
      document.getElementById("password").type = "text";
      document.getElementById("showPwd").src = "/view/login/img/睁眼logo.png";
    } else {
      document.getElementById("password").type = "password";
      document.getElementById("showPwd").src = "/view/login/img/闭眼logo.png";
    }
  });

  // 控制清除图标的显示与隐藏
  $('#user').bind('input propertychange', function() {
    if ($('#user').val() != "") {
      $("#clearName").css({
        "display": "block"
      })
    } else {
      $("#clearName").css({
        "display": "none"
      })
    }
  });
  $('#password').bind('input propertychange', function() {
    if ($('#password').val() != "") {
      $("#clearPwd").css({
        "display": "block"
      })
    } else {
      $("#clearPwd").css({
        "display": "none"
      })
    }
  });
  $('#loVerification').bind('input propertychange', function() {
    if ($('#loVerification').val() != "") {
      $("#clearVerification").css({
        "display": "block"
      })
    } else {
      $("#clearVerification").css({
        "display": "none"
      })
    }
  });


  // 点击更换验证码
  $("#changeVerification").click(function(){
    $("#changeVerification").src = "code.php?t="+Math.random(); //增加一个随机参数，防止图片缓存
  })

});
