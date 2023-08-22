import { setCookie } from "./Utils.js";
$('.changeLanguage button').on('click',function(){
    try {
        let thisLang = $(this).attr('id').toLowerCase();
        if(thisLang.length===2 || isNaN(thisLang))
            setCookie('phone_book_lang',thisLang);
            location.reload();
    } catch (e) {
        setCookie('phone_book_lang','en');
    }

});