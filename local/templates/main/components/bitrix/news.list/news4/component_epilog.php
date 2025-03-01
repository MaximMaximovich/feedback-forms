<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

if (!empty($arResult['NEW_DATE']) && !empty($arResult['OLD_DATE'])) {
    global $APPLICATION;

    $APPLICATION->SetTitle(Loc::getMessage("NEWS_EPILOG_TITLE_PREFIX") . $arResult['OLD_DATE'] . ' - ' . $arResult['NEW_DATE'] );

}