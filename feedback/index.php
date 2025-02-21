<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форма2");
?>

<?php $APPLICATION->IncludeComponent(
    'qperfect:feedback.form',
    'feedback',
    array(
        'IBLOCK_ID' => FEEDBACK_IBLOCK_ID,
        'IBLOCK_SECTION' => "",
        'EVENT_NAME' => 'CUSTOM_FEEDBACK_FORM',
        'FORM_TITLE' => 'Обратный звонок',
        'URL' => $APPLICATION->GetCurPage(false),
        'INCLUDE_URL' => 'Y',
        'RE_CAPTCHA_SITE_KEY' => RE_CAPTCHA_SITE_KEY,
        'RE_CAPTCHA_SECRET_KEY' => RE_CAPTCHA_SECRET_KEY,
        'RE_CAPTCHA_SCORE' => 0.5,
        'FIELDS' => array(
            'FIO' => array('required' => true, 'minlength' => 3, 'maxlength' => 254, 'regExp' => ''),
            'PHONE' => array('required' => false, 'minlength' => 17, 'maxlength' => 17, 'regExp' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'),
            'EMAIL' => array('required' => false, 'minlength' => 6, 'maxlength' => 254, 'regExp' => '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i'),
            'CALL_TIME' => array('required' => false, 'minlength' => 0, 'maxlength' => 5, 'regExp' => '')
        )
    ),
    false,
    array('HIDE_ICONS' => 'Y')
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>