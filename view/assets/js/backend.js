import { setCookie } from "./Utils.js";
var stored = {};
var inputs = $('.numberFiled');
$.each(inputs,function(k,v){
    stored[k] = $(v).val();
});
var values = [];
for(let key in stored) {
  if(values.indexOf(stored[key]) > -1){
   $(`input[value="${stored[key]}"]`).addClass('border-danger')
  } else {
    values.push(stored[key]);
  }
}
const MAX_NUM_ADD = 6;
$('input, textarea').on('keyup',function(){
    if($(this).val().length > 0)
        $(this).removeClass('is-invalid')
    else
        $(this).addClass('is-invalid')
});
function filedNumber(){
    for(let i = 0; i<=$('.filedNumber').length; i++){
        $('.filedNumber').eq(i).html(i+2);
        $('.numberFiled').eq(i).attr('id','phoneNumbers'+i);
    }
}


$('.addField').on('click',function(){
    $('.firstNumber').removeClass('d-none');
    var html = `
        <div class="d-flex ">
        <div class="position-relative flex-fill">
            <input type="tel" pattern="[0-9]{11}" name="phone_numbers[]" class="form-control numberFiled fs-5">
            <div class="position-absolute filedNumber end-0 top-0 mt-2 me-2 bg-light text-info rounded-circle px-2"></div>
        </div>
        <button type="button" class="btn btn-link text-danger removeField"><i class="bi bi-trash"></i></button>
        </div>
    `;
    if($('.numberFiled').length<=MAX_NUM_ADD){
        $(this).parent('div').before(html);
        filedNumber()
    }
    else
        $(this).fadeOut(1);
    
    removeFiled()
});
function removeFiled(){
    $('.removeField').on('click',function(){
        if($('.numberFiled').length>=MAX_NUM_ADD)
            $('.addField').fadeIn();
        $(this).parent('div').remove();
        filedNumber();
    });
}
removeFiled();


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
(() => {
'use strict'
const forms = document.querySelectorAll('.needs-validation')
Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
    if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
    }
    form.classList.add('was-validated')
    }, false)
})
})()