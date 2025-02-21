<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

use \Bitrix\Iblock\ElementTable;
use \Bitrix\Main\FileTable;
use \Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLE_LIST_IBLOCK_MODULE_NONE"));
	return;
}

if(!isset($arParams["NEWS_IBLOCK_ID"])) {
    $arParams["NEWS_IBLOCK_ID"] = 0;
}
if(!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if ($this->StartResultCache()) {

    $elementEntity = ElementTable::getEntity();
    $query = new Query($elementEntity);
    $iblockId = $arParams["NEWS_IBLOCK_ID"];

    $res = $query
        ->where('IBLOCK_ID', $iblockId)
        ->setSelect([
            'ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID',
            'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'REF_PREVIEW_PICTURE',
            'SECTION_NAME' => 'IBLOCK_SECTION.NAME',
            'SECTION_CODE' => 'IBLOCK_SECTION.CODE'
        ])
        ->registerRuntimeField(new Reference(
            'REF_PREVIEW_PICTURE',
            FileTable::class,
            Join::on('this.PREVIEW_PICTURE', 'ref.ID')
        ))
        ->addOrder('NAME')
        // ->setCacheTtl(3600)
        ->exec();

    $arNews = [];

    while ($arr = $res->fetch()) {

        $arNews[$arr['ID']] = array(
            'ID' => $arr['ID'],
            'NAME' => $arr['NAME'],
            'CODE' => $arr['CODE'],
            'IBLOCK_SECTION_ID' => $arr['IBLOCK_SECTION_ID'],
            'PREVIEW_TEXT' => $arr['PREVIEW_TEXT'],
            'PREVIEW_PICTURE_ID' => $arr['PREVIEW_PICTURE'],
            'SECTION_NAME' => $arr['SECTION_NAME'],
            'SECTION_CODE' => $arr['SECTION_CODE'],
            'DETAIL_PAGE_URL' => '/news/' . $arr['SECTION_CODE'] . '/' . $arr['CODE'] . '/',
            'ACTIVE_FROM' => $arr['ACTIVE_FROM']->format("d.m.Y H:i:s"),
        );

        if (!empty($arr['PREVIEW_PICTURE'])) {
            $arNews[$arr['ID']]['PREVIEW_PICTURE_SRC'] = '/upload/' . $arr['IBLOCK_ELEMENT_REF_PREVIEW_PICTURE_SUBDIR'] .
                '/' . $arr['IBLOCK_ELEMENT_REF_PREVIEW_PICTURE_FILE_NAME'];
        } else {
            $arNews[$arr['ID']]['PREVIEW_PICTURE_SRC'] = SITE_TEMPLATE_PATH . '/images/no_photo.png';
        }
    }

    $arResult['NEWS'] = $arNews;
    unset($arNews);
    $this->SetResultCacheKeys([]);
    $this->includeComponentTemplate();

} else {
    $this->AbortResultCache();
}
