<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult["META_DESCR"])) {
    global $APPLICATION;
    $APPLICATION->SetPageProperty("description", $arResult["META_DESCR"]);
}

if (isset($arResult['NEWS_RELATED_ARRAY'])) {
    $GLOBALS['NEWS_RELATED'] = $arResult['NEWS_RELATED_ARRAY'];
}