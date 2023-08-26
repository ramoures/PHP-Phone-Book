import { COOKIE_NAME_FOR_FRONTEND_LANG } from "./config.js";
import { setCookie } from "./Utils.js";

$('.sorting').on('change',function(){
  let value = parseInt($(this).val());
    if(value===1)
        window.location.href = '?page=1&asc=0&nameSort=0';
    else if(value===2)
        window.location.href = '?page=1&asc=1&nameSort=0';
    else if(value===3)
        window.location.href = '?page=1&asc=1&nameSort=1';
    else 
        window.location.href = '?page=1&asc=0&nameSort=1';
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