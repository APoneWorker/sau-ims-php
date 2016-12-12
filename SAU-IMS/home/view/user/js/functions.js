  function createNotice(box,title,text,time,sender,i) {
    if(title.length>8){
      title = title.substring(0,7) + "…";
    }
    if(text.length>8){
      textS = text.substring(0,7) + "…";
    }
    else{
      textS = text;
    }
    var annousBox = document.getElementById(box);
    var li = createEle("li",  "dynamicList");;
    li.id = i;
    li.onclick = Events;

      function Events() {
      var readD = document.getElementById("read"+this.id);
      if(readD.style.display!="none"){
        readD.style.display="none";
      }
      var ddddd = document.getElementById("liText"+this.id);
      if(document.getElementById("liText"+this.id).style.display==""){
        document.getElementById("liText"+this.id).style.display="list-item";
      }
      else{
        document.getElementById("liText"+this.id).style.display="none"
      }
      // $.post("../../../index.php?c=AdminMain&a=getNoticeById", {"nid": i}, function(data, status) {
      //   // clearAll("rightBar");
      //   eval("data = " + data);
      //   createRight(srcOfHead, data['name'], data['time'], data['title'],data['text'],data['id'])
      // })
      // checkedStyle(this);
    }

      var readDom = createEle("img",  "unread");
      readDom.id = "read"+ i ;
      readDom.src = "./view/user/img/unread.svg" ;
      var dynamicImg = createEle("img",  "dynamicImg");
      dynamicImg.src = "./view/user/img/logo.png" ;
      var contentDom = createEle("div","content");
        var titleDom = createEle("h1", "title");
        titleDom.innerHTML = title;
        var textDom = createEle("h4", "text");
        textDom.innerHTML = textS;
      var inforDom = createEle("div","infor");
        var timeDom = createEle("div", "time");
        timeDom.innerHTML = time;
        var senderDom = createEle("div", "sender");
        senderDom.innerHTML = sender;
    var liText = createEle("li",  "dynamicText");;
    liText.innerHTML= text;
    liText.id = "liText"+i;
    var hr= createEle("hr");


    addChilds(li, readDom, dynamicImg,contentDom, inforDom);
    addChilds(contentDom, titleDom, textDom);
    addChilds(inforDom, timeDom, senderDom);
    addChilds(annousBox,li,liText,hr);
  }
