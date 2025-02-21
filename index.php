<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Quality Performance - главная, форма обратной связи");
$APPLICATION->SetPageProperty("description", "Страница с формой обратной связи ");
$APPLICATION->SetTitle("Форма обратной связи");
?><?php $APPLICATION->IncludeComponent("bitrix:form.result.new", "custom", Array(
    "CACHE_TIME" => "3600",	// Время кеширования (сек.)
    "CACHE_TYPE" => "A",	// Тип кеширования
    "CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
    "CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
    "EDIT_URL" => "",	// Страница редактирования результата
    "IGNORE_CUSTOM_TEMPLATE" => "N",	// Игнорировать свой шаблон
    "LIST_URL" => "",	// Страница со списком результатов
    "SEF_MODE" => "N",	// Включить поддержку ЧПУ
    "SUCCESS_URL" => "",	// Страница с сообщением об успешной отправке
    "USE_EXTENDED_ERRORS" => "Y",	// Использовать расширенный вывод сообщений об ошибках
    "VARIABLE_ALIASES" => array(
        "RESULT_ID" => "RESULT_ID",
        "WEB_FORM_ID" => "WEB_FORM_ID",
    ),
    "WEB_FORM_ID" => "2",	// ID веб-формы
),
    false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>