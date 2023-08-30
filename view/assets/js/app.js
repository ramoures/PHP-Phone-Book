import { COOKIE_NAME_FOR_FRONTEND_LANG } from "./config.js";
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Strict;secure";
}
function serach(){
    const text = $('.searcher').parent().find('input').val();
    const baseUrl = $('.searcher').attr('data-base-target');
    location.href = baseUrl+"&s="+text;
}
$('.searcher').on('click',function(){
    serach();
});
$('.searcher').parent().find('input').on('keypress',function(e){
    var key = e.which;
    if(key == 13){
        serach();
        return false;  
     }
});
$('.showSearchBox, .closeSearchBox').on('click',function(){
    const toggle = parseInt($('.showSearchBox').attr('data-toggle'));
    if(toggle){
        $('.searchBox').removeClass('d-flex').addClass('d-none');
        $('.showSearchBox').removeClass('active').attr('data-toggle',0);
    }else{
        $('.searchBox').removeClass('d-none').addClass('d-flex');
        $('.showSearchBox').addClass('active').attr('data-toggle',1);
    }
});
$('.sorting').on('change',function(){
    let value = $(this).val();
    window.location.href = value;
   
});
$('.changeLanguage button').on('click',function(){
    try {
        let thisLang = $(this).attr('id').toLowerCase();
        if(thisLang.length===2 || isNaN(thisLang))
            setCookie(COOKIE_NAME_FOR_FRONTEND_LANG,thisLang);
            location.reload();
    } catch (e) {
        setCookie(COOKIE_NAME_FOR_FRONTEND_LANG,'en');
    }

});
$('.toMoreNumbers').on('click',function(){
   $(this).addClass('d-none')
   $(this).parent().find('.moreNumbers').removeClass('d-none')
   $(this).parent().find('.lessMoreNumbers').removeClass('d-none')
   $(this).parent().parent().find('.phoneImg').removeClass('align-self-center')

});
$('.lessMoreNumbers').on('click',function(){
   $(this).addClass('d-none')
   $(this).parent().parent().find('.toMoreNumbers').removeClass('d-none')
   $(this).parent().parent().find('.moreNumbers').addClass('d-none')
   $(this).parent().parent().parent().find('.phoneImg').addClass('align-self-center')

});