$(function() {
  ids={};
  $("#assignment").click(function(){
    checkedLeftStyle(this);
    createAssignment();
  })
  $("#sendNotice").click(function(){
    checkedLeftStyle(this);
    createNotice();
  })
  $("#zhuce").click(function(){
    checkedLeftStyle(this);
    createZhuce();
  })
  $("#niandu").click(function(){
    checkedLeftStyle(this);
    createNiandu();
  })
  $("#rizhe").click(function(){
    checkedLeftStyle(this);
    createRizhi();
  })

})

function createAssignment(){
  if(document.getElementById("label")){
    document.getElementById("label").innerHTML = "任务";
  }
  else{
    $(".label").html("任务");
  }
  $("#searchbtn").unbind('click');
  $("#searchbtn").bind('click',function(){searchTask();});
  $("#deleteimg").unbind('click');
  $("#deleteimg").bind('click',function(){clearCheckedTasks();});
  $("#refresh").unbind('click');
  $("#refresh").bind('click',function(){refreshTack();});
  $("#new1").unbind('click');
  $("#new1").bind('click',function(){newAssignment();});

  clearAll("announcementList");

  // 创建列表项
    $.post("./index.php?c=AdminTask&a=getSendTasks", {"limit": limit}, function(data) {
    eval("data =" + data);
    for (var i = 0; i < data.length; i++) {
      createTasksList(data[i]['title'], data[i]['time'], data[i]['id']);
    }
    var firstId = data[0]['id'];
    var firstDom = document.getElementById(firstId);
    checkedStyle(firstDom);
    //生成正文内容
    $.post("./index.php?c=AdminTask&a=getTaskById", {"tid": firstId}, function(data) {
      eval("data = " + data);
        clearAll("rightBar");
      createTasks(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id'],data['user']);
    });
  });

    return false;

}
function createNotice(){
  if(document.getElementById("label")){
    document.getElementById("label").innerHTML = "公告";
  }
  else{
    $(".label").html("公告");
  }
  $("#new1").unbind('click');
  $("#new1").bind('click',function(){newNotice();});
    $("#searchbtn").unbind('click');
  $("#searchbtn").bind('click',function(){search();});
  $("#deleteimg").unbind('click');
  $("#deleteimg").bind('click',function(){clearChecked();});
  $("#refresh").unbind('click');
  $("#refresh").bind('click',function(){refresh();});

  clearAll("announcementList");

  // 创建列表项
    $.post("./index.php?c=AdminMain&a=refresh", {"limit": limit}, function(data) {
    eval("data =" + data);
    for (var i = 0; i < data.length; i++) {
      createList(data[i]['title'], data[i]['time'], data[i]['id']);
    }
    var firstId = data[0]['id'];
    var firstDom = document.getElementById(firstId);
    checkedStyle(firstDom);
    //生成正文内容
    $.post("./index.php?c=AdminMain&a=getNoticeById", {"nid": firstId}, function(data) {
      eval("data = " + data);

        clearAll("rightBar");
      createRight(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id']);
    });
  });

}
function createZhuce(){
  if(document.getElementById("label")){
    document.getElementById("label").innerHTML = "注册审核";
  }
  else{
    $(".label").html("注册审核");
  }
  $("#new1").unbind('click');
  $("#new1").bind('click',function(){newAssignment();});

  clearAll("announcementList");

  // 创建列表项
    $.post("./index.php?c=AdminMain&a=refresh", {"limit": limit}, function(data) {
    eval("data =" + data);
    for (var i = 0; i < data.length; i++) {
      createList(data[i]['title'], data[i]['time'], data[i]['id']);
    }
    var firstId = data[0]['id'];
    var firstDom = document.getElementById(firstId);
    checkedStyle(firstDom);
    //生成正文内容
    $.post("./index.php?c=AdminMain&a=getNoticeById", {"nid": firstId}, function(data) {
      eval("data = " + data);

        clearAll("rightBar");
      createRight(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id']);
    });
  });
}
function createNiandu(){
  if(document.getElementById("label")){
    document.getElementById("label").innerHTML = "年度审核";
  }
  else{
    $(".label").html("年度审核");
  }
  $("#new1").unbind('click');
  $("#new1").bind('click',function(){newAssignment();});

  clearAll("announcementList");

  // 创建列表项
    $.post("./index.php?c=AdminMain&a=refresh", {"limit": limit}, function(data) {
    eval("data =" + data);
    for (var i = 0; i < data.length; i++) {
      createList(data[i]['title'], data[i]['time'], data[i]['id']);
    }
    var firstId = data[0]['id'];
    var firstDom = document.getElementById(firstId);
    checkedStyle(firstDom);
    //生成正文内容
    $.post("./index.php?c=AdminMain&a=getNoticeById", {"nid": firstId}, function(data) {
      eval("data = " + data);
        clearAll("rightBar");
      createRight(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id']);
    });
  });
}
function createRizhi(){
  if(document.getElementById("label")){
    document.getElementById("label").innerHTML = "社团日志";
  }
  else{
    $(".label").html("社团日志");
  }
  $("#new1").unbind('click');
  $("#new1").bind('click',function(){newAssignment();});

  clearAll("announcementList");

  // 创建列表项
    $.post("./index.php?c=AdminMain&a=refresh", {"limit": limit}, function(data) {
    eval("data =" + data);
    for (var i = 0; i < data.length; i++) {
      createList(data[i]['title'], data[i]['time'], data[i]['id']);
    }
    var firstId = data[0]['id'];
    var firstDom = document.getElementById(firstId);
    checkedStyle(firstDom);
    //生成正文内容
    $.post("./index.php?c=AdminMain&a=getNoticeById", {"nid": firstId}, function(data) {
      eval("data = " + data);
        clearAll("rightBar");
      createRight(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id']);
    });
  });
}
/**
 * [newAssignment 创建新公告]
 * @return {[type]} [description]
 */
function newAssignment(){
  ids = {};
  clearAll("rightBar");
    var boxDom = document.getElementById("rightBar");
    //生成编辑区域
    var header = createEle("header", "mainHeader");
      var newAnnouncement = createEle("h3","newAnnouncement")
      newAnnouncement.innerHTML = "创建任务";
    var main = createEle("section", "main");
      var newBox = createEle("div","newBox");
        var toWho = createEle("input","toWho");
        toWho.placeholder = ("To…");
        var contact = createEle("img","contact");
        contact.src="./view/admin/img/contact.png";
        contact.onclick = function(){
          addContacts();
        }
        var hr = createEle("hr","newHr");
        var titleDom = createEle("input","newAssignmentTitle");
        // titleDom.contentEditable = true;
        titleDom.placeholder = ("标题…");
      var textDom = createEle("div","newText");
      textDom.contentEditable = true;
      var btnDom = createEle("input","submit");
      btnDom.type = "button";
      btnDom.id = "submitNew";
      btnDom.value = "发布";
      btnDom.onclick =btnClick;
      function btnClick(){
        var title = titleDom.value;
        var text = textDom.innerHTML;
        title =escape(title);
        text = escape(text);
        var time = getNowFormatDate();
        ids =arrayToJsonstr(ids);
        var task = {};
        task.title=title;
        task.text=text;
        task.time=time;
        // task.ids = ids;
        task=arrayToJsonstr(task);
        // var task = '{"title":"'+title+'","text":"'+text+'","time":"'+time+'"}';
        $.post("./index.php?c=AdminTask&a=addTask", {"task":task,"ids":ids}, function(data, status) {
          // alert(data);
          if(data){
            refreshTack();
          }
        })
      }
    addChilds(newBox,toWho,contact,hr,titleDom);
    addChilds(header,newAnnouncement);
    addChilds(main,newBox,textDom,btnDom);
    addChilds(boxDom,header,main);
}

function checkedLeftStyle(obj){
    //移除上一个节点的类
    var announcementList = document.getElementById("navigation");
    var childs = announcementList.childNodes;
    for(var i=0;i<childs.length;i++){
      chil=$(childs[i]);
      if(chil.hasClass("checked")){
        chil.removeClass("checked");
        break;
      }//if
    }//for
    // 添加此节点的类
     var clickObj = $(obj);
     clickObj.addClass("checked");
  }//cheakedStyle


  //数组转json
  function arrayToJsonstr(arr){
    var jstr = '{';
    for(key in arr){
      jstr +='"'+key+'":"'+arr[key]+'",';
    }
    jstr = jstr.substring(0,jstr.length-1);
    jstr += '}';
    return jstr;
  }

  //选择收件人
  function addContacts(){
    var markO = createEle("div","mark");
    markO.id = "mark";
    markO.onclick = closeContBox;
    var addContactBox = createEle("div","addContactBox");
    addContactBox.id = "addContactBox";
      var closeConBox = createEle("div","closeCon");
      closeConBox.onclick = closeContBox;
      var title = createEle("div","addContactsTitle");
      title.innerHTML = "选择并添加联系人";
      var contacts = createEle("div","contacts");
        var allCon = createEle("ul","allCon");
        allCon.id="allCon";
        $.post("./index.php?c=AdminTask&a=getUserSauCanSendTask",null,function(data){
          eval("data = "+data);
          var club = data.club;
          var sau = data.sau;
          for (var i=0;i<club.length;i++){
            creatCon(club[i].id,club[i].name,allCon);
          }
        })

        var handleCon = createEle("div","handleCon");
          var shiftIn = createEle("div","shiftIn");
          var shiftOut = createEle("div","shiftOut");
        var selectedCon = createEle("ul","selectedCon");
        selectedCon.id = "selectedCon";
          shiftIn.onclick = function(){
            inserts(allCon,selectedCon);
          }
          shiftIn.innerHTML = "插入";
          shiftOut.onclick = function(){
            inserts(selectedCon,allCon);
          }
          shiftOut.innerHTML = "移出";
      var below = createEle("input","below");
      below.type="button";
      below.value="确认";
    below.onclick=function(){
      ids = getSelectedId(selectedCon);
      closeContBox();
    }
    addChilds(handleCon,shiftIn,shiftOut);
    addChilds(contacts,allCon,handleCon,selectedCon);
    addChilds(addContactBox,closeConBox,title,contacts,below);
    addChilds(document.body,markO,addContactBox);
  }

  function closeContBox(){
    var mark = document.getElementById("mark");
    var addContactBox = document.getElementById("addContactBox");
    document.body.removeChild(mark);
    document.body.removeChild(addContactBox);
  }

  function creatCon(id,name,par){
    var li = createEle("li");
    li.id = id;
    li.innerHTML = name;
    li.onclick = isSelected;
    par.appendChild(li);
  }

  function isSelected(){
    if(this.className == "selected"){
      this.className = "";
    }
    else{
      this.className = "selected";
    }
  }

  function inserts(Out,In){
    var childs = Out.childNodes;
    var len = childs.length
    for(var i=0;i<len;i++){
      if(childs[0].className == "selected"){
        childs[0].className = "";
        addChilds(In,childs[0]);
      }
    }
  }

  function getSelectedId(par){
    var childs = par.childNodes;
    var Ids = {};
    var names="";
    var len = childs.length
    for(var i=0;i<len;i++){
        Ids[i] = childs[i].id;
        names +=childs[i].innerHTML+",";
    }
    $(".toWho").val(names);
    return Ids;
  }

  /**
 * 刷新Task
 * @return none
 */
function refreshTack() {
  $("#listContainer").bind('listScroll');
  clearAll("announcementList");
  $("#searchField").val("");
   // 创建列表项
  // $.post("./index.php?c=AdminTask&a=getSendTasks", {"limit": limit}, function(data,status) {
  $.post("./index.php?c=AdminTask&a=getSendTasks", {"limit": limit}, function(data,status) {
    if (status == "success") {
      clearAll("announcementList");
      eval("data =" + data);
      var len = data.length;
      for (var i = 0; i < len; i++) {
        createTasksList(data[i]['title'], data[i]['time'], data[i]['id']);
      }
      var firstId = data[0]['id'];
      var firstDom = document.getElementById(firstId);
      checkedStyle(firstDom);
      //生成正文内容
      $.post("./index.php?c=AdminTask&a=getTaskById", {"tid": firstId}, function(data) {
        eval("data = " + data);
        clearAll("rightBar");
        createTasks(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id'],data['user']);
      })
    } else {
      alert("刷新出错");
    }
  });
}
