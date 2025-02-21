<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arPpcID = [];
foreach($arResult['ITEMS'] as &$arItem) {

    $arItem["CUSTOM_DETAIL_PAGE_URL"] = $arParams['SECTION_RESULT']['FOLDER'] . str_replace(
            array("#SECTION_CODE#", "#ELEMENT_CODE#"),
            array($arParams['PARENT_SECTION_CODE'], $arItem["CODE"]),
            $arParams['SECTION_RESULT']['URL_TEMPLATES']['element'] );


    if(isset($arItem["PREVIEW_PICTURE"]['ID'])) {
        $resImage = CFile::ResizeImageGet(
            $arItem["PREVIEW_PICTURE"]["ID"],
            array("width" => 100, "height" => 100),
            BX_RESIZE_IMAGE_PROPORTIONAL
        );
        $arItem["PREVIEW_PICTURE_SRC"] = $resImage['src'];
        $arPpcID[] = $arItem["PREVIEW_PICTURE"]['ID'];
    } else {
        $arItem["PREVIEW_PICTURE_SRC"] = SITE_TEMPLATE_PATH . '/images/no_photo.png';
    }
}
