import { COOKIE_NAME_FOR_BACKEND_LANG,MAX_PHONE_NUMBER_TO_BE_ADD } from "./config.js";
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Strict;secure";
}
if (window.history.replaceState) 
    window.history.replaceState( null, null, window.location.href );
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
$('input, textarea').on('keyup',function(){
    if($(this).val().length > 0)
        $(this).removeClass('is-invalid')
    else
        $(this).addClass('is-invalid')
});
function filedNumber(){
    if($('.filedNumber').length<=0){
        $('#phoneNumbers0').removeClass('pe-5')
        $('.firstNumber').addClass('d-none');
    }
    for(let i = 0; i<=$('.filedNumber').length; i++){
        $('.filedNumber').eq(i).html(i+2);
        $('.numberFiled').eq(i).attr('id','phoneNumbers'+i);
    }
}
$('.formReset').on('click',function(){
    $(this).closest('form').find('input').removeClass('border-danger').val('');
    $(this).closest('form').find('textarea').val('');
});
$('.addField').on('click',function(){
    $('.firstNumber').removeClass('d-none');
    $('#phoneNumbers0').addClass('pe-5');
    let patern = $('#phoneNumbers0').attr('pattern');
    var html = `
        <div class="d-flex">
        <div class="position-relative flex-fill">
            <input type="tel" pattern="${patern}" name="phone_numbers[]" class="form-control numberFiled fs-5 pe-5">
            <div class="position-absolute filedNumber end-0 top-0 mt-2 me-2 bg-magic-light text-secondary rounded-circle px-2"></div>
        </div>
        <button type="button" class="btn btn-link text-danger removeField"><i class="bi bi-trash"></i></button>
        </div>
    `;
    if($('.numberFiled').length<=MAX_PHONE_NUMBER_TO_BE_ADD-1){
        $(this).parent('div').before(html);
        filedNumber()
    }
    else
        $(this).fadeOut(1);
    removeFiled()
});
function removeFiled(){
    $('.removeField').on('click',function(){
        if($('.numberFiled').length>=MAX_PHONE_NUMBER_TO_BE_ADD-1)
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
            setCookie(COOKIE_NAME_FOR_BACKEND_LANG,thisLang);
            location.reload();
    } catch (e) {
        setCookie(COOKIE_NAME_FOR_BACKEND_LANG,'en');
    }
});

$('.clearImage').on('click',function(){
    $(this).addClass('d-none');
    $('.chooseNewFile').addClass('d-none');
    $('#image').parent().removeClass('d-none');
    $('#image').prop('disabled',false);
    $(this).parent().find('img').addClass('d-none');
    $('.reloadImage').removeClass('d-none');
});
$('.reloadImage').on('click',function(){
    $('#image').prop('disabled',true);
    $(this).addClass('d-none');
    $('.chooseNewFile').removeClass('d-none');
    $('#image').parent().addClass('d-none');
    $(this).parent().find('img').removeClass('d-none');
    $('.clearImage').removeClass('d-none');
});
addEventListener("resize", () => {
    $('.moreInfo').addClass('d-none').prop('hidden',true);
    $('.toMoreInfo').attr('data-toggle',0);
    $('.toMoreInfo i').removeClass('bi-caret-up-fill').addClass('bi-caret-down-fill');

});
$('.toMoreInfo').on('click',function(){
    const toggle = parseInt($(this).attr('data-toggle'));
    if(toggle){
        $('i',this).removeClass('bi-caret-up-fill').addClass('bi-caret-down-fill');
        $(this).parent().parent().parent().next().addClass('d-none').prop('hidden',true);
        $(this).attr('data-toggle',0)
    }
    else{
        $(this).attr('data-toggle',1)
        $('i',this).removeClass('bi-caret-down-fill').addClass('bi-caret-up-fill');

        let fullNameTtl = $(this).closest('tr').parent().prev().find('.fullNameTtl').text();
        let addressTtl = $(this).closest('tr').parent().prev().find('.addressTtl').text();
        let datetimeTtl = $(this).closest('tr').parent().prev().find('.datetimeTtl').text();
        let fullNameBd = $(this).closest('tr').find('.fullNameBd').html();
        let fullName = fullNameBd?`<strong>${fullNameTtl}</strong> : ${fullNameBd}<br>`:'';
        let addressBd = $(this).closest('tr').find('.addressBd').html();
        let address = addressBd?`<strong>${addressTtl}</strong> : ${addressBd}<br>`:'';
        let datetimeBd = $(this).closest('tr').find('.datetimeBd').html();
        let html = `
            ${fullName}${address}
            <strong>${datetimeTtl}</strong> : ${datetimeBd}
        `;
        $(this).parent().parent().parent().next().removeClass('d-none').prop('hidden',false).find('td').html(html)
    }
});
$('.toConfirmModal').on('click',function(){
    let thisId = $(this).attr('data-id');
    $('#phoneNumbersId').val(thisId)
});
$('#txtSearchProdAssign').keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('input[name = butAssignProd]').click();
       return false;  
     }
   });
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
$('#newPassCheckBox').on('change',function(){
    $('.newPass').addClass('d-none').find('input').prop('disabled',true);
    if(this.checked)
        $('.newPass').removeClass('d-none').find('input').prop('disabled',false);
});
$('.showPass').on('click',function(){
    const type = $(this).parent().find('input').attr('type')
    if(type==='password')
    {
        $(this).parent().find('input').attr('type','text');
        $(this).find('i').removeAttr('class').addClass('bi bi-eye-slash fs-4 opacity-75')
    }
    else
    {
        $(this).parent().find('input').attr('type','password');
        $(this).find('i').removeAttr('class').addClass('bi bi-eye fs-4 opacity-75')
    }
});
$('#newPassword').on('keyup',function(){
    // Pattern information: find define('PASSWORD_PATTERN') in config.php file.
    const thisVal = $(this).val();
    if(thisVal.length >= 8 && thisVal.length <= 16)
        $('.passValid').find('span').eq(0).addClass('text-success fw-bold').find('i').addClass('bi bi-check');
    if(thisVal.length < 8 || thisVal.length > 16 )
        $('.passValid').find('span').eq(0).removeClass('text-success fw-bold').find('i').removeClass('bi bi-check');  

    if(thisVal.search(/(?=(.*[0-9]){2,})/g) !== -1 )
        $('.passValid').find('span').eq(1).addClass('text-success fw-bold').find('i').addClass('bi bi-check');
    if(thisVal.search(/(?=(.*[0-9]){2,})/g) === -1 )
        $('.passValid').find('span').eq(1).removeClass('text-success fw-bold').find('i').removeClass('bi bi-check');

    if(thisVal.search(/(?=(.*[a-z]){1,})/g) !== -1 )
        $('.passValid').find('span').eq(2).addClass('text-success fw-bold').find('i').addClass('bi bi-check');
    if(thisVal.search(/(?=(.*[a-z]){1,})/g) === -1 )
        $('.passValid').find('span').eq(2).removeClass('text-success fw-bold').find('i').removeClass('bi bi-check');

    if(thisVal.search(/(?=(.*[A-Z]){1,})/g) !== -1 )
        $('.passValid').find('span').eq(3).addClass('text-success fw-bold').find('i').addClass('bi bi-check');
    if(thisVal.search(/(?=(.*[A-Z]){1,})/g) === -1 )
        $('.passValid').find('span').eq(3).removeClass('text-success fw-bold').find('i').removeClass('bi bi-check');

    if(thisVal.search(/(?=(.*[!@#$%^&*_=+\-]){2,})/g) !== -1 )
        $('.passValid').find('span').eq(4).addClass('text-success fw-bold').find('i').addClass('bi bi-check');
    if(thisVal.search(/(?=(.*[!@#$%^&*_=+\-]){2,})/g) === -1 )
        $('.passValid').find('span').eq(4).removeClass('text-success fw-bold').find('i').removeClass('bi bi-check');
})

