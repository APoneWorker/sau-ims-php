$(function(){
  var limit = '{"l":"0","r":"10"}';
  $.post("../back/index.php?c=AdminMain&a=refresh", {"limit": limit}, function (data) {
    eval("data =" + data);
    for (var i = 0; i < 10; i++) {
    var nowYear = parseInt(getNowFormatDate().substring(0,5));
    var nowMonth = parseInt(getNowFormatDate().substring(6,8));
    var nowDay = parseInt(getNowFormatDate().substring(9,11));
    var dataYear = parseInt(data[i].time.substring(0,5));
    var dataMonth = parseInt(data[i].time.substring(6,8));
    var dataDay = parseInt(data[i].time.substring(9,11));
      if(nowYear==dataYear && nowMonth==dataMonth && nowDay==dataDay){
        var box = "today";
      }
      else if(nowYear==dataYear && nowMonth==dataMonth && (nowDay-dataDay)<=3){
        var box = "three";
      }
      else{
        var box = "earlier";
      }
      var title = data[i].title;
      var text = data[i].text;
      var time=data[i].time.substring(11,16);
      var sender="By "+data[i].name;
      var id = data[i].id;
      createNotice(box,title,text,time,sender,i)
    }
});

  // $("#notice").click(function(){
  //   location='./view/user/indexNotice.html';
  // })
})
