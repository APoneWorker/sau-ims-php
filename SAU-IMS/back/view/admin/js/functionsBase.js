/**
 * [createEle 生成dom节点，并添加任意个数的类名]
 * @param  {[String]} ele [标签名字]
 * @return {[objects]}     [dom对象]
 */
function createEle(ele) {
  var cla = arguments;
  ele = document.createElement(ele);
  eleJq = $(ele);
  //根据传入类的个数，
  for (var i = 1; i < cla.length; i++) {
    eleJq.addClass(cla[i]);
  } //for
  return ele; //或是返回一个数组，包括dom对象和jq对象
} //createEle

/**
 * [addChilds 添加任意个数的子节点]
 * @param {[object]} par [父节点]
 */
function addChilds(par) {
  var childs = arguments;
  for (var i = 1; i < childs.length; i++) {
    par.appendChild(childs[i]);
  }
} //addChilds

/**
 * [checkedStyle 列表节点被单击后，样式发生改变]
 * @param  {[object]} obj [被单击的dom节点对象]
 * @return {[type]}     [description]
 */
function checkedStyle(obj) {
  //移除上一个节点的类
  var announcementList = document.getElementById("announcementList");
  var childs = announcementList.childNodes;
  for (var i = 0; i < childs.length; i++) {
    chil = $(childs[i]);
    if (chil.hasClass("active")) {
      chil.removeClass("active");
      break;
    }//if
  }//for
  // 添加此节点的类
  var clickObj = $(obj);
  clickObj.addClass("active");
}//cheakedStyle

/**
 * [clearAll 清空目录部分所有节点]
 * @return {[none]} [description]
 */
function clearAll(id) {
  var par = document.getElementById(id);
  var childs = par.childNodes;
  for (var i = childs.length - 1; i >= 0; i--) {
    par.removeChild(childs[i]);
  } //for
} //clear


function getNowFormatDate() {
  var date = new Date();
  var seperator1 = "-";
  var seperator2 = ":";
  var month = date.getMonth() + 1;
  var strDate = date.getDate();
  if (month >= 1 && month <= 9) {
    month = "0" + month;
  }
  if (strDate >= 0 && strDate <= 9) {
    strDate = "0" + strDate;
  }
  var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
      + " " + date.getHours() + seperator2 + date.getMinutes()
      + seperator2 + date.getSeconds();
  return currentdate;
}
