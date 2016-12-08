/**
   * [createRight 生成公告内容部分]
   * @param  {[String]} srcOfHead [头像图片的路径]
   * @param  {[String]} sender    [公告发布者]
   * @param  {[String]} time      [发布时间]
   * @param  {[String]} title     [公告题目]
   * @param  {[String]} addresee  [公告接受者]
   * @param  {[String]} text      [正文]
   * @return {[none]}           [description]
   */
  function createRight(srcOfHead, sender, time, title, addresee, text) {
    var rightBar = document.getElementById("rightBar");
    var header = createEle("header", "mainHeader");
    var userHead = createEle("img", "userHead", "fll");
    userHead.src = srcOfHead;
    userHead.alt = "用户头像"
    var mainSender = createEle("h1", "mainSender", "fll");
    mainSender.innerHTML = sender;
    var mainTime = createEle("div", "mainTime", "fll");
    mainTime.innerHTML = time;
    var deleteButton = createEle("a", "deleteButton", "fll");
    deleteButton.href = "javascript:;"
    var mainDelete = createEle("img", "mainDelete");
    mainDelete.src = "./view/admin/img/删除logo.png";
    mainDelete.alt = "删除";
    var deleteText = createEle("span", "deleteText", "rlt");
    deleteText.innerHTML = "删除";
    addChilds(deleteButton, mainDelete, deleteText);
    addChilds(header, userHead, mainSender, mainTime, deleteButton);

    var main = createEle("section", "main");
    var mainTitle = createEle("h3", "mainTitle");
    mainTitle.innerHTML = title;
    var mainContent = createEle("div", "mainContent");
    var towho = createEle("p", "toWho");
    towho.innerHTML = addresee;
    var ti2 = createEle("p", "ti2");
    ti2.innerHTML = text;
    addChilds(mainContent, towho, ti2);
    addChilds(main, mainTitle, mainContent);
    addChilds(rightBar, header, main);
  }


function search() {
    var val = $("#searchField").val();
    // 创建json对象（相当于一个字符串，具体格式见w3school）
    var search = '{"title":"' + val + '","l":"0","r":"10"}';
    // 传json数据--search给后台
    $.post("./index.php?c=AdminMain&a=searchNotices", {"search": search}, function(data, status) {
      if (status == "success") {
        clearAll("announcementList");
        // 将后台传过来的json数据解析，变成数组
        eval("data =" + data);
        if(!(data)){
          var len = data.length;
          for (var i = 0; i < len; i++) {
            // 引用数组数据
            createList(data[i]['title'], data[i]['text'], data[i]['time'], i);
          }
        }
        else{
          alert('查找不到关于"'+val+'"的公告');
        }
        
      }
      else alert("查询出错");
    })
  }
