<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Context;


if (!Loader::includeModule("form")) {
    echo json_encode(['success' => false, 'errors' => Loc::getMessage("FORM_RES_NEW_CUSTOM_AJAX_FORM_NO_INSTALL_TEXT")]);
}

// Проверка валидности отправки формы
if (check_bitrix_sessid()) {

    $request = Context::getCurrent()->getRequest();
    $webFormId = htmlspecialcharsbx($request->getPost('WEB_FORM_ID'));

    $formErrors = CForm::Check($webFormId, $request, false, "Y", 'Y');

    $phone = '';
    $form = $questions = $answers = $dropdown = $multiselect = [];

    if (!CForm::GetDataByID($webFormId,
        $form,
        $questions,
        $answers,
        $dropdown,
        $multiselect)) {
        echo json_encode(['success' => false, 'errors' => Loc::getMessage("FORM_RES_NEW_CUSTOM_AJAX_FORM_NOT_FOUND_TEXT")]);
    }

    $phone = htmlspecialcharsbx($request->getPost('form_text_' . $questions['PHONE']['ID']));

    // валидация условия "если заполнен телефон, то email необязательный"
    if (!empty($phone) && !empty($formErrors['EMAIL'])) {
        unset($formErrors['EMAIL']);
    }

    // Если не все обязательные поля заполнены
    if (count($formErrors)) {
        echo json_encode(['success' => false, 'errors' => $formErrors]);
    } elseif ($RESULT_ID = CFormResult::Add($webFormId, $request)) {

        // Отправляем все события как в компоненте веб форм
        CFormCRM::onResultAdded($webFormId, $RESULT_ID);
        CFormResult::SetEvent($RESULT_ID);
        CFormResult::Mail($RESULT_ID);

        // говорим что успешно заявку получили
        echo json_encode(['success' => true, 'errors' => []]);
    } else {
        // Какие-то еще ошибки произошли
        echo json_encode(['success' => false, 'errors' => $GLOBALS["strError"]]);
    }
} else {
    // Предотвратили CSRF атаку
    echo json_encode(['success' => false, 'errors' => ['sessid' => Loc::getMessage("FORM_RES_NEW_CUSTOM_AJAX_FORM_NOT_VALID_SESSION_TEXT")]]);
}

// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';