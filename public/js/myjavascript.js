//RWD navbar隱藏工具列
$(document).ready(function(){
    $(".nav-toggler").each(function (_, navToggler){
        var target = $(navToggler).data('target');
        $(navToggler).on('click',function(){
            $(target).animate({
                height:"toggle",
            });
        });
    });
});
//js 補零函數
function pad(num, n) { 
    if ((num + "").length >= n) return num; 
    return pad("0" + num, n); 
}
//new date再加一個函數
// Date.prototype.addHours = function(h) {
//     this.setTime(this.getTime() + (h*60*60*1000));
//     return this;
// }
// Date.prototype.addMinutes = function (minutes) {
//     return new Date(this.getTime() + minutes*60000);
// }
//collapsible 可折疊 功能 
$('body').on('click', '*[data-toggle="mycollapse"]', function() {
    var targer = $(this).data('target');
    $('*[data-toggle="mycollapse"]').each(function (){ //全部收起
        var back_targer = $(this).data('target');
        if ($(back_targer).height()){
            $(back_targer).css("transition-duration", '300ms');
            $(back_targer).css("maxHeight", '');
        }
    });
    // if($(targer).hasClass('max-h-0')){
    //     $(targer).removeClass("max-h-0");
    // }else{
    //     $(targer).addClass('max-h-0 overflow-hidden');
    // }
    var scrollHeight = $(targer).prop('scrollHeight');
    if ($(targer).height()){
        $(targer).css("transition-duration", '300ms');
        $(targer).css("maxHeight", '');
    } else {
        $(targer).css("transition-duration", '300ms');
        $(targer).css("maxHeight", scrollHeight+"px");
    } 
    // 範例
    // <div class="collapsible bg-blue-300" data-toggle='mycollapse' data-target="#CollapseTest">Open Collapsible</div>
    // <div class="max-h-0 overflow-hidden " id='CollapseTest'>
    //     <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    // </div>
});
//modal 跳出與顯示功能
$('body').on('click', '*[data-toggle="mymodal"]', function() {
    var targer = $(this).data('target');
    // if($(targer).hasClass("hidden")){
    //     $(targer).removeClass("hidden");
    // }else{
    //     $(targer).addClass("hidden");
    // }
    $(targer).removeClass("hidden");
});
$('body').on('click', '*[data-dismiss="mymodal"]', function() {
    var label = $(this).data('label');
    $(label).addClass("hidden");
});
// javascript cookie GET 因為原本的cookie取的方式不方便，所以寫一個函數來取得
function parseCookie() {
    var cookieObj = {};
    var cookieAry = document.cookie.split(';');
    var cookie;
    for (var i=0, l=cookieAry.length; i<l; ++i) {
        cookie = jQuery.trim(cookieAry[i]);
        cookie = cookie.split('=');
        cookieObj[cookie[0]] = cookie[1];
    }
    return cookieObj;
}
function getCookieByName(name) {
    var value = parseCookie()[name];
    if (value) {
        value = decodeURIComponent(value);
    }

    return value;
}