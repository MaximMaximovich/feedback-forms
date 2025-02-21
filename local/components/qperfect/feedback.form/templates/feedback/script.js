(function (BX) {
    BX.ready(function () {
        let arParamsForm = window.ajaxPrms;
        emailRequire('form_phone');

        $.mask.definitions['H'] = "[0-1]";
        $.mask.definitions['h'] = "[0-9]";
        $.mask.definitions['M'] = "[0-5]";
        $.mask.definitions['m'] = "[0-9]";
        $(".time").mask("Hh:Mm");

        const form = $('#feedback');
        if (form.length > 0) {
            form.parsley().on('field:validated', function() {
                // условие, что если заполнен телефон то email становиться необязательным
                let phone = document.getElementById('form_phone')
                let email = document.getElementById('form_email')
                let sPhoneContents = phone.innerHTML;
                if (sPhoneContents !== false && !phone.classList.contains('parsley-error')) {
                    email.required = false;
                }
            })
            .on("form:submit", function () {

                let dataArParams = arParamsForm.dataArParams;
                let addiSigned = arParamsForm.dataAddiSigned;
                let arFormData = form.serializeArray();

                BX.ajax.runComponentAction( 'qperfect:feedback.form', 'ajaxSendFormData', {
                    mode:'class',
                    signedParameters: dataArParams,
                    data: {
                        arFormData : arFormData,
                        addiSigned : addiSigned
                    }
                }).then(function (response) {
                    console.log(response);
                    if (response.status == 'success') {
                        if (response.data) {
                            $('body').append(response.data.modal);
                            let textID = $("#popup_success").find(".modal-content").find("h3.pt__text").text();
                            textID = textID.replace("#ID#", "№" + response.data.id);
                            $("#popup_success").find(".modal-content").find("h3.pt__text").text(textID);
                            setTimeout(() => {
                                $("#popup_success").addClass("show-modal")
                            }, 100);
                            $(".close-button").each(function (index, element) {
                                $(this).on('click', (e) => {
                                    $("div.show-modal").remove();
                                })
                            });
                        }
                    }

                    resetFrm();
                }, function (response) {
                    //сюда будут приходить все ответы, у которых status !== 'success'
                    console.log(response);
                    if (response.status == 'error') {
                        if (response.data) {
                            $('body').append(response.data.modal);
                            $(".close-button").each(function (index, element) {
                                $(this).on('click', (e) => {
                                    $("div.show-modal").remove();
                                })
                            });
                        }
                    }
                    resetFrm();
                });

                return false;
            });
        }

    });
})(BX);


function resetFrm() {
    $(".input").each(function () {
        $(this).val("");
    });
    $("textarea").each(function () {
        $(this).val("");
    });
    $(".checkbox input").each(function () {
        $(this).prop("checked", false);
    });
    let spanEmailEn = document.getElementById('email_required');
    if (spanEmailEn.classList.contains("hidden"))
        spanEmailEn.classList.remove("hidden");
}

function emailRequire(phoneId) {

    let phone = document.getElementById(phoneId)

    if (phone) {
        phone.addEventListener("keyup", function (e) {

            let spanEmailEn = document.getElementById('email_required')
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


// рекапча
function onCallbackSubmit(token) {
    $("#feedback").trigger('submit');
}



