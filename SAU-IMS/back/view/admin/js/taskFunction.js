function createTasks(srcOfHead, sender, time, title, text, id,shouj) {
  var rightBar = document.getElementById("rightBar");
  var header = createEle("header", "mainHeader");
  var userHead = createEle("img", "userHead", "fll");
  userHead.src = srcOfHead;
  userHead.alt = "用户头像";
  var mainSender = createEle("h1", "mainSender", "fll");
  mainSender.innerHTML = sender;
  var mainTime = createEle("div", "mainTime", "fll");
  mainTime.innerHTML = time;
  var deleteButton = createEle("a", "deleteButton", "fll");
  deleteButton.href = "javascript:;"
  var mainDelete = createEle("img", "mainDelete");
  mainDelete.src = "./view/admin/img/删除logo.png";
  mainDelete.alt = "删除";
  mainDelete.Data = id;
  mainDelete.onclick = deleteSingleTask;
  var deleteText = createEle("span", "deleteText", "rlt");
  deleteText.innerHTML = "删除";
  deleteText.Data = id;
  deleteText.onclick = deleteSingleTask;
  addChilds(deleteButton, mainDelete, deleteText);
  addChilds(header, userHead, mainSender, mainTime, deleteButton);

  var main = createEle("section", "main");
  var mainTitle = createEle("h3", "mainTitle");
  mainTitle.innerHTML = title;
  var mainContent = createEle("div", "mainContent");
  var shou = createEle("div","shoujian");
  shou.innerHTML = "To: "+shouj;
  var ti2 = createEle("p");
  ti2.innerHTML = text
  // .replace(/\s/g, "&nbsp;");
  addChilds(mainContent, shou,ti2);
  addChilds(main, mainTitle, mainContent);
  addChilds(rightBar, header, main);
}

function createTasksList(title, time, i) {
  if (title.length > 8) {
    title = title.substring(0, 7) + "…";
  }
  var annousBox = document.getElementById("announcementList");
  var li = createEle("li", "content");
  ;
  li.id = i;
  li.onclick = Events;

  var tokenDom = createEle("div", "fll", "token");
  tokenDom.id = "token" + i;

  var titleDom = createEle("h1", "fll", "title");
  titleDom.innerHTML = title;

  var checkboxDom = createEle("div", "flr", "rlt", "checkbox");
  var input = document.createElement("input");
  input.type = "checkbox";
  input.name = i;
  checkboxDom.appendChild(input);

  var timeDom = createEle("div", "rlt", "time");
  timeDom.innerHTML = time;

  addChilds(li, tokenDom, titleDom, checkboxDom, timeDom);
  annousBox.appendChild(li);

  function Events() {
    var tokenD = document.getElementById("token" + this.id);
    if (tokenD.className == "untoken") {
      tokenD.className = "token";
      $.POST();
    }
    $.post("./index.php?c=AdminTask&a=getTaskById", {"tid": i}, function (data, status) {
      clearAll("rightBar");
      eval("data = " + data);
      createTasks(srcOfHead, data['name'], data['time'], data['title'], data['text'], data['id'],data['user']);
    });
    checkedStyle(this);
  }

  return input;
}

function deleteSingleTask() {
  var ID = this.Data;
  var deleteId = '{"id":"' + ID + '"}';
  $.post("./index.php?c=AdminTask&a=deleteTasks", {"taskIds": deleteId}, function (data, status) {
    if (data == "true") {
      refreshTack();
    }
  })
}

/**
 * [clearChecked 删除选中的列表项]
 * @return {[none]} []
 * 用到外界变量：checkboxs
 */
function clearCheckedTasks() {
  // 此数组用于储存要删除的节点id
  var checkedIds = '{';
  // 此数组用于储存要删除的节点
  var checkedobj = new Array;
  var announcementList = document.getElementById("announcementList");
  var checkboxs = announcementList.getElementsByTagName("input");
  var j = 0;
  for (var i = 0; i < checkboxs.length; i++) {
    if (checkboxs[i].checked) {
      checkedIds += '"' + j + '"' + ':' + checkboxs[i].name + ',';
      checkedobj[j] = document.getElementById(checkboxs[i].name)
      j++;
    } //if
  } //for
  checkedIds = checkedIds.substring(0, checkedIds.length - 1)
  checkedIds += '}';
  $.post("./index.php?c=AdminTask&a=deleteTasks", {"taskIds": checkedIds}, function (data, status) {

    if (status == "success") {
      var announcementList = document.getElementById("announcementList");
      for (var i = 0; i < checkedobj.length; i++) {
        announcementList.removeChild(checkedobj[i]);
      } //for
    } //if
  })
} //clearChecked

/**
 * 搜索
 * @return none
 */
function searchTask() {
  $("#listContainer").unbind('scroll');
  var val = $("#searchField").val();
  valE = escape(val);
  if (valE) {
    clearAll("announcementList");
    var search = '{"title":"' + valE + '","l":"0","r":"10"}';
    $.post("./index.php?c=AdminTask&a=searchTasks", {"search": search}, function (data, status) {
      if (data != "false") {//之前这里是"false"。。。怪不得不会出现查询出错
        eval("data =" + data);
        if (data.length != 0) {
          var len = data.length;
          for (var i = 0; i < len; i++) {
            createList(data[i]['title'], data[i]['time'], data[i]['id']);
          }
          searchScroll(search);
          var firstId = data[0]['id'];
          var firstDom = document.getElementById(firstId);
          checkedStyle(firstDom);
          //生成正文内容
          $.post("./index.php?c=AdminTask&a=getTaskById", {"tid": firstId}, function (data) {
            eval("data = " + data);
            clearAll("rightBar");
            createRight(srcOfHead, data['name'], data['time'], data['title'], data['text'],data['id']);
          })
        } else {
          alert('查找不到与"' + val + '"有关的任务');
        }
      } else {
        alert("查询出错");
      }
    });
  }
}
