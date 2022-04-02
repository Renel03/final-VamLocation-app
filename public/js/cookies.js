var dropCookie = true;
var cookieDuration = 14;
var cookieName = 'complianceCookie';
var cookieValue = 'on';
var url = '/page/privacy-and-cookies.htm';

function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-law');
    div.innerHTML = '<div class="wp d-flex align-items-center"><p class="text-truncate">En utilisant ce site, vous acceptez notre <a href="' + url + '" rel="nofollow">politique de cookies</a> dans le but d\'améliorer votre expérience utilisateur.</p><a class="close-cookie-banner" href="javascript:void(0);" onclick="removeMe();"><span>J\'accepte</span></a></div>';
    bodytag.insertBefore(div,bodytag.firstChild);
    document.getElementsByTagName('body')[0].classList.add('cookiebanner');
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    if(window.dropCookie) {
        document.cookie = name+"="+value+expires+"; path=/";
    }
}

function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

window.onload = function(){
    if(checkCookie(window.cookieName) != window.cookieValue){
        createDiv();
    }
}

function removeMe(){
    createCookie(window.cookieName,window.cookieValue, window.cookieDuration);
  	var element = document.getElementById('cookie-law');
    document.getElementsByTagName('body')[0].classList.remove('cookiebanner');
  	element.parentNode.removeChild(element);
}
