<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ORM\Fields\ExpressionField;
use \Bitrix\Iblock\Iblock;

$iblockClass = Iblock::wakeUp($arParams['IBLOCK_ID'])->getEntityDataClass(); //получаем объект

/*  // вариант с двумя запросами
$select = ['ID', 'NAME', 'DATE_VALUE' => 'DATE.VALUE'];
$filter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'];
$order = ['DATE.VALUE' => 'DESC'];

$arResult['NEW_DATE'] = $iblockClass::getList(['order' => $order, 'filter' => $filter, 'limit' => 1, 'select' => $select,])->fetchAll()[0]['DATE_VALUE'];
$arResult['NEW_DATE'] = explode(" ", $arResult['NEW_DATE'])[0];

$order = ['DATE.VALUE' => 'ASC'];
$arResult['OLD_DATE'] = $iblockClass::getList(['order' => $order, 'filter' => $filter, 'limit' => 1, 'select' => $select,])->fetchAll()[0]['DATE_VALUE'];
$arResult['OLD_DATE'] = explode(" ", $arResult['OLD_DATE'])[0]; */

$arRes = $iblockClass::getList(array(
    'filter' => array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'),
    'runtime' => array(
        new ExpressionField(
            'MIN_DATE',
            'MIN(%s)',
            ['DATE.VALUE']
        ),
        new ExpressionField(
            'MAX_DATE',
            'MAX(%s)',
            ['DATE.VALUE']
        )),
    'select' => array('MIN_DATE','MAX_DATE'),
))->fetchAll();

$arResult['NEW_DATE'] = explode(" ", $arRes[0]['MAX_DATE'])[0];
$arResult['OLD_DATE'] = explode(" ", $arRes[0]['MIN_DATE'])[0];

if (!empty($arResult['NEW_DATE']) && !empty($arResult['OLD_DATE'])) {
    $this->__component->SetResultCacheKeys(['NEW_DATE', 'OLD_DATE']);
}



