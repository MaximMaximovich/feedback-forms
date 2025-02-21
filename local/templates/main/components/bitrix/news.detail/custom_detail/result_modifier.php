<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['PREVIEW_TEXT'])) {
    $arResult['META_DESCR'] = mb_strimwidth($arResult['PREVIEW_TEXT'], 0, 100, "...");
    $this->__component->SetResultCacheKeys(["META_DESCR"]);
}

if (!empty($arResult['PROPERTIES']['NEWS_RELATED']['VALUE'])) {
    $arResult['NEWS_RELATED_ARRAY'] = $arResult['PROPERTIES']['NEWS_RELATED']['VALUE'];
    $this->__component->SetResultCacheKeys(["NEWS_RELATED_ARRAY"]);
}