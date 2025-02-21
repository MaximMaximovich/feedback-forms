window.addEventListener("DOMContentLoaded", function () {
    focusRequeredInput();
    valid();
    [].forEach.call(document.querySelectorAll(".phone"), function (input) {
        let keyCode;
        function mask(event) {
            event.keyCode && (keyCode = event.keyCode);
            let pos = this.selectionStart;
            if (pos < 3) event.preventDefault();
            let matrix = "+7 (___) ___ ____",
                i = 0,
                def = matrix.replace(/\D/g, ""),
                val = this.value.replace(/\D/g, ""),
                new_value = matrix.replace(/[_\d]/g, function (a) {
                    return i < val.length ? val.charAt(i++) || def.charAt(i) : a;
                });
            i = new_value.indexOf("_");
            if (i != -1) {
                i < 5 && (i = 3);
                new_value = new_value.slice(0, i);
            }
            let reg = matrix
                .substr(0, this.value.length)
                .replace(/_+/g, function (a) {
                    return "\\d{1," + a.length + "}";
                })
                .replace(/[+()]/g, "\\$&");
            reg = new RegExp("^" + reg + "$");
            if (!reg.test(this.value) || this.value.length < 5 || (keyCode > 47 && keyCode < 58)) this.value = new_value;
            if (event.type == "blur" && this.value.length < 5) this.value = "";
        }

        input.addEventListener("input", mask, false);
        input.addEventListener("focus", mask, false);
        input.addEventListener("blur", mask, false);
        input.addEventListener("keydown", mask, false);
    });
});


function valid() {
    const text = document.querySelectorAll("._text");

    if (text) {
        for (let i = 0; i < text.length; i++) {
            const el = text[i];

            el.addEventListener("keydown", function (e) {
                if (e.key.match(/[0-9]/)) return e.preventDefault();
            });

            el.addEventListener("input", function (e) {
                el.value = el.value.replace(/[0-9]/g, "");
            });
        }
    }

}


function focusRequeredInput() {
    $("._pr").on("focusin", function () {
        if ($(this).parsley().isValid()) {
        } else {
            $(this).parsley().reset();
        }
    });
    $("._pr").on("focusout", function () {
        $(this).parsley().validate();
    });
}


function popupSuccess() {
    var addAnswer = new BX.PopupWindow("form_answer", null, {
        content: '<div id="mainshadow"></div>'+'<h3>Ваша заявка успешно отправлена!</h3>',
        titleBar: {content: BX.create("span", {html: '', 'props': {'className': 'access-title-bar'}})},
        zIndex: 0,
        autoHide : true,
        offsetTop : 1,
        offsetLeft : 0,
        lightShadow : true,
        closeIcon : true,
        closeByEsc : true,
        draggable: {restrict: false},
        overlay: {backgroundColor: 'black', opacity: '80' },  /* затемнение фона */
        buttons: [
            new BX.PopupWindowButton({
                text: "Ок!",
                className: "webform-button-link-cancel",
                events: {click: function(){
                        this.popupWindow.close(); // закрытие окна
                    }}
            })
        ]
    });

    return addAnswer.show();
}
