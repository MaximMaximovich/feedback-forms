<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arPpcID = [];
foreach($arResult['ITEMS'] as &$arItem) {
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
