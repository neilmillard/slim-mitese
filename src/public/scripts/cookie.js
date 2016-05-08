/*!---- cookie-consent-----*/
var _cookiePlaceHolderSelector = "#headerwrapper"; // placeholder for cookie consent data and accept button
var _cookieWrapper = "_cookieConsentWrapper";
var _cookieName = "_cookieConsent"; // name of the cookie which will be used to store flag if user has clicked on i accept button
var _cookieTimeout = 16; // Time in SECONDS after which the cookie consent will disappear

var _cookieContent = '<p>This site uses cookies as described in our privacy statement. To see what cookies we use and set your own preferences please review the cookie notice in our <a href="/privacy">privacy statement</a>. Otherwise, if you agree to our use of cookies, please continue to use our site. </p> <input name="cookieagree" id="cookie-agree" onclick="setCookies();" value="Continue" type="button">';


// disclaimer content
document.write('<style type="text/css">');
document.write('#' + _cookieWrapper + '{background-color:#83BB13; padding:10px 5px;margin-bottom: 0px;font-size: 0.9em;line-height: 1.2em;}');
document.write('#' + _cookieWrapper + ' p{color:#000000; margin:0 5px; padding-bottom:0;}');
document.write('#' + _cookieWrapper + ' a{color:#000000;text-decoration:underline;}');
document.write('#' + _cookieWrapper + ' a:hover{color:#474747;text-decoration:none;}');
document.write('#' + _cookieWrapper + ' input{cursor:pointer; padding:2px 5px;border:0px;text-decoration:underline;color:#000000;background:none;}');
document.write('#' + _cookieWrapper + ' input:hover{color:#474747;text-decoration:none;}');
document.write('</style>');

function setCookies(name, value, hours) {

    name = typeof name !== 'undefined' ? name : _cookieName;
    value = typeof value !== 'undefined' ? value : 'yes';
    hours = typeof hours !== 'undefined' ? hours : 8760;
    var expire = "";
    if (hours != null) {
        expire = new Date((new Date()).getTime() + hours * 86400000);
        expire = "; expires=" + expire.toGMTString();
    }
    document.cookie = name + "=" + escape(value) + expire + "; path=/";
    $("#" + _cookieWrapper).slideUp().promise().done(function() {
    });

    /*-------------------------Setting of Section Div height Etc on cookie wrapper close Ends-----------------------*/
}

function GetCookie(name) {
    var cookieValue = "";
    var search = name + "=";
    if (document.cookie.length > 0) {
        offset = document.cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = document.cookie.indexOf(";", offset);
            if (end == -1) end = document.cookie.length;
            cookieValue = unescape(document.cookie.substring(offset, end));
        }
    }
    return cookieValue;
}

function checkCookies() {
    var cval = GetCookie(_cookieName);
    if (cval == "yes") {
        $("#" + _cookieWrapper).css("display", "none");
    } else {
        $("#" + _cookieWrapper).slideDown(800);
        startTimeout();
    }
}
function startTimeout(){
    setTimeout(function(){
        $("#" + _cookieWrapper).slideUp(600).promise().done(function() {
        });
    },_cookieTimeout*1000);
}
function cookieSetup() {
    wrapperDiv = '<div id="' + _cookieWrapper + '">' + _cookieContent + '</div>';
    $(wrapperDiv).insertBefore($(_cookiePlaceHolderSelector));
    checkCookies();
}
window.onload = cookieSetup;