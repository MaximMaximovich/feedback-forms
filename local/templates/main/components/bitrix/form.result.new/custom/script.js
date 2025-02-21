let arParamsForm = [];
$(window).on('load', function () {
    arParamsForm = window.ajaxParams;

    emailRequere(arParamsForm.phoneId);

    $.mask.definitions['H'] = "[0-1]";
    $.mask.definitions['h'] = "[0-9]";
    $.mask.definitions['M'] = "[0-5]";
    $.mask.definitions['m'] = "[0-9]";
    /*$.mask.definitions['P'] = "[AaPp]";
    $.mask.definitions['p'] = "[Mm]";*/

    $(".time").mask("Hh:Mm");
})

$(function () {
    let wr = $('.form-feedback');
    let form = wr.find('form');
    form.parsley().on('field:validated', function() {
        // условие, что если заполнен телефон то email становиться необязательным
        let phone = document.getElementById(arParamsForm.phoneId)
        let email = document.getElementById(arParamsForm.emailId)
        let sPhoneContents = phone.innerHTML;
        if (sPhoneContents !== false && !phone.classList.contains('parsley-error')) {
            email.required = false;
        }
    })
    .on('form:submit', function() {

        let formId = arParamsForm.formId;
        const obForm = document.getElementsByName(formId)[0]
        const link = arParamsForm.link

        obForm.getElementsByClassName('error-msg')[0].innerHTML = '';

        let xhr = new XMLHttpRequest();
        xhr.open('POST', link);

        xhr.onload = function() {
            if (xhr.status != 200) {
                alert(`Ошибка ${xhr.status}: ${xhr.statusText}`);
            } else {
                var json = JSON.parse(xhr.responseText)

                if (! json.success) {
                    let errorStr = '';
                    for (let fieldKey in json.errors) {
                        errorStr += json.errors[fieldKey] + '<br>';
                    }

                    // Ошибки вывести в элемент с классом error-msg
                    obForm.getElementsByClassName('error-msg')[0].innerHTML = errorStr;
                } else {
                    if (arParamsForm.isCaptcha) {
                        reloadCaptcha();
                    }
                    resetFm()
                    // Показываем сообщение об успешной отправке
                    popupSuccess()
                }
            }
        };

        xhr.onerror = function() {
            alert("Запрос не удался");
        };

        // Передаем все данные из формы
        xhr.send(new FormData(obForm));

        return false;
    });
});

function resetFm() {
    $(".input").each(function () {
        $(this).val("");
    });
    $("textarea").each(function () {
        $(this).val("");
    });
    $(".checkbox input").each(function () {
        $(this).prop("checked", false);
    });
    let spanEmailEn = document.getElementById('email-enabled');
    if (spanEmailEn.classList.contains("hidden"))
        spanEmailEn.classList.remove("hidden");
}

function emailRequere(phoneId) {
    let phone = document.getElementById(phoneId)

    if (phone) {
        phone.addEventListener("keyup", function (e) {

            let spanEmailEn = document.getElementById('email-enabled')
            let sPhoneContents = phone.innerHTML;
            if (phone.classList.contains('parsley-success')) {
                if (!spanEmailEn.classList.contains("hidden"))
                    spanEmailEn.classList.add("hidden");
            } else {
                if (spanEmailEn.classList.contains("hidden"))
                    spanEmailEn.classList.remove("hidden");
            }
        });
    }
}

function reloadCaptcha() {
    $.getJSON(arParamsForm.CAPTCHAHandler, function(data) {
        $('#captchaImg').attr('src','/bitrix/tools/captcha.php?captcha_sid='+data);
        $('#captchaSid').val(data);
        $('input[name="captcha_word"]').val('');
    });
    return false;
}


$(document).ready(function(){
    $('#reloadCaptcha').click(function(){
        reloadCaptcha();
    });
    $('#captchaImg').click(function(){
        reloadCaptcha();
    });
});






