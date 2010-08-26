function focusEle(ele){
	try {document.getElementById(ele).focus();}
	catch(e){}
}
function displayToggle(ele) {
	var ele = document.getElementById(ele);
	ele.style.display = ele.style.display == 'none' ? '' : 'none' ;
}
function hideEle(ele) {
	document.getElementById(ele).style.display == 'none';
}
function showEle(ele){
	document.getElementById(ele).style.display == '';
}
function updateEle(ele,content){
	document.getElementById(ele).innerHTML = content;
}
function timestamp(){
	return new Date().getTime();
}
function showhidediv(id){
	displayToggle(id);
	var input_id=arguments[1];
	if(input_id){focusEle(input_id);}
}
function keyw(){
	if (document.keyform.keyword.value==""){
		alert(l_keyword_empty);
		document.keyform.keyword.focus();
		return false;
	}
}
function checkEmail (str){
	isEmail1=/^\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w+$/;
	return (isEmail1.test(str));
}
function checkform(){
	if (document.commentform.comname.value==""){
		alert(l_name_empty);
		document.commentform.comname.focus();
		return false;
	}
	if(document.commentform.comname.value.length>16){
		alert(l_key_too_long);
		document.commentform.comname.focus();
		return false;
	}
	if(document.commentform.comment.value.length==""){
		alert(l_comment_empty);
		document.commentform.comment.focus();
		return false;
	}
	if(document.commentform.comment.value.length>2000){
		alert(l_comment_too_long);
		document.commentform.comment.focus();
		return false;
	}
	if(document.commentform.commail.value!=""){
		if(!checkEmail(document.commentform.commail.value)){
			alert(l_email_invalid);
			document.commentform.commail.focus();
			return false;
		}
	}
}
function isdel (id,type,url){
	if(type == 'twitter'){
		var msg = l_sure_delete;
		if(confirm(msg)){sendinfo(url+'twitter.php?action=del&twid='+id,'twitter')}
		else {return;}
	}
}
var XMLHttp = {  
	_objPool: [],
	_getInstance: function () {
		for (var i = 0; i < this._objPool.length; i ++) {
			if (this._objPool[i].readyState == 0 || this._objPool[i].readyState == 4) {
				return this._objPool[i];
			}
		}
		this._objPool[this._objPool.length] = this._createObj();
		return this._objPool[this._objPool.length - 1];
	},
	_createObj: function(){
		if (window.XMLHttpRequest){
			var objXMLHttp = new XMLHttpRequest();
		} else {
			var MSXML = ['MSXML2.XMLHTTP.5.0', 'MSXML2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP', 'Microsoft.XMLHTTP'];
			for(var n = 0; n < MSXML.length; n ++){
				try{
					var objXMLHttp = new ActiveXObject(MSXML[n]);
					break;
				}catch(e){}
			}
		}
		if (objXMLHttp.readyState == null){
			objXMLHttp.readyState = 0;
			objXMLHttp.addEventListener('load',function(){
				objXMLHttp.readyState = 4;
				if (typeof objXMLHttp.onreadystatechange == "function") {  
					objXMLHttp.onreadystatechange();
				}
			}, false);
		}
		return objXMLHttp;
	},
	sendReq: function(method, url, data, callback){
		var objXMLHttp = this._getInstance();
		with(objXMLHttp){
			try {
				if (url.indexOf("?") > 0) {
					url += "&randnum=" + Math.random();
				} else {
					url += "?randnum=" + Math.random();
				}
				open(method, url, true);
				setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				send(data);
				onreadystatechange = function () {  
					if (objXMLHttp.readyState == 4 && (objXMLHttp.status == 200 || objXMLHttp.status == 304)) {  
						callback(objXMLHttp);
					}
				}
			} catch(e) {
				alert('emria:error');
			}
		}
	}
};
function sendinfo(url,node){
	updateEle(node,"<div><span style=\"background-color:#FFFFE5; color:#666666;\">"+l_loading+"...</span></div>");
	XMLHttp.sendReq('GET',url,'',function(obj){updateEle(node,obj.responseText);});
}
function postinfo(url,post_id,node){
	updateEle(node,"<div><span style=\"background-color:#FFFFE5; color:#666666;\">"+l_processing+"...</span></div>");
	var pdata = document.getElementById(post_id).value;
	var data = post_id+"="+encodeURIComponent(pdata);
	XMLHttp.sendReq('POST',url,data,function(obj){updateEle(node,obj.responseText);});
}
function loadr(url,tid){
    url = url+"&stamp="+timestamp();
	var r=document.getElementById("r_"+tid);
	var rp=document.getElementById("rp_"+tid);
	if (r.style.display=="block"){
		r.style.display="none";
		rp.style.display="none";
	} else {
		r.style.display="block";
        r.innerHTML = '<span style=\"background-color:#FFFFE5;text-align:center;font-size:12px;color:#666666;\">'+l_loading+'...</span>';
        XMLHttp.sendReq('GET',url,'',function(obj){r.innerHTML = obj.responseText;rp.style.display="block";});
	}
}
function reply(url,tid){
    var rtext=document.getElementById("rtext_"+tid).value;
    var rname=document.getElementById("rname_"+tid).value;
    var rcode=document.getElementById("rcode_"+tid).value;
    var rmsg=document.getElementById("rmsg_"+tid);
    var rn=document.getElementById("rn_"+tid);
    var r=document.getElementById("r_"+tid);
    var data = "r="+rtext+"&rname="+rname+"&rcode="+rcode+"&tid="+tid;
    XMLHttp.sendReq('POST',url,data,function(obj){
        if(obj.responseText == 'err1'){rmsg.innerHTML = l_comment_length_max_140;
        }else if(obj.responseText == 'err2'){rmsg.innerHTML = l_username_empty;
        }else if(obj.responseText == 'err3'){rmsg.innerHTML = l_captcha_invalid;
        }else if(obj.responseText == 'err4'){rmsg.innerHTML = l_nickname_disabled;
        }else if(obj.responseText == 'err5'){rmsg.innerHTML = l_comment_exists;
        }else if(obj.responseText == 'succ1'){rmsg.innerHTML = l_comment_ok_premod;
        }else{r.innerHTML += obj.responseText;rn.innerHTML = Number(rn.innerHTML)+1;rmsg.innerHTML=''}});
}
function re(tid, rp){
    var rtext=document.getElementById("rtext_"+tid).value = rp;
    focusEle("rtext_"+tid);
}