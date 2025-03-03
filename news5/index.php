<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости с фильтром");

use Bitrix\Main\Context;
use Bitrix\Main\UI\Filter\FieldAdapter;

$post = Context::getCurrent()->getRequest()->getPostList()->toArray();

$ui_filter = [
    ['id' => 'NAME', 'name' => 'Название', 'type'=>'text', 'default' => true],
    ['id' => 'DATE_ACTIVE_FROM', 'name' => 'Дата', 'type'=> FieldAdapter::DATE,
        "exclude" => array(
            \Bitrix\Main\UI\Filter\DateType::NONE,
            \Bitrix\Main\UI\Filter\DateType::YESTERDAY,
            \Bitrix\Main\UI\Filter\DateType::CURRENT_DAY,
            \Bitrix\Main\UI\Filter\DateType::TOMORROW,
            \Bitrix\Main\UI\Filter\DateType::CURRENT_WEEK,
            \Bitrix\Main\UI\Filter\DateType::CURRENT_MONTH,
            \Bitrix\Main\UI\Filter\DateType::CURRENT_QUARTER,
            \Bitrix\Main\UI\Filter\DateType::LAST_7_DAYS,
            \Bitrix\Main\UI\Filter\DateType::LAST_30_DAYS,
            \Bitrix\Main\UI\Filter\DateType::LAST_60_DAYS,
            \Bitrix\Main\UI\Filter\DateType::LAST_90_DAYS,
            \Bitrix\Main\UI\Filter\DateType::PREV_DAYS,
            \Bitrix\Main\UI\Filter\DateType::NEXT_DAYS,
            \Bitrix\Main\UI\Filter\DateType::MONTH,
            \Bitrix\Main\UI\Filter\DateType::QUARTER,
            \Bitrix\Main\UI\Filter\DateType::YEAR,
            \Bitrix\Main\UI\Filter\DateType::EXACT,
            \Bitrix\Main\UI\Filter\DateType::LAST_WEEK,
            \Bitrix\Main\UI\Filter\DateType::LAST_MONTH,
            \Bitrix\Main\UI\Filter\DateType::NEXT_WEEK,
            \Bitrix\Main\UI\Filter\DateType::NEXT_MONTH,
        ),
        'default' => false
    ],
];


$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [
        'FILTER_ID' => "news_filter",
        'FILTER' => $ui_filter,
        'VALUE_REQUIRED_MODE' => true,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true,
    ]
);


$filterOption = new Bitrix\Main\UI\Filter\Options("news_filter");
$filterData = $filterOption->getFilter([]);

$GLOBALS['arrFilterNews'] = array(
    "LOGIC" => "AND",
    array('>=DATE_ACTIVE_FROM'=>$filterData["DATE_ACTIVE_FROM _from"]),
    array('<=DATE_ACTIVE_FROM'=>$filterData["DATE_ACTIVE_FROM _to"])
);
$GLOBALS['arrFilterNews']['?NAME'] = explode(" ", $filterData["NAME"]);
?>
    <div style="padding-top: 100px"></div>
<?php $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "filtered_news",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("CODE", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM"),
        "FILE_404" => "",
        "USE_FILTER" => "Y",
        "FILTER_NAME" => "arrFilterNews",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "6",
        "IBLOCK_TYPE" => "news",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "5",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "Y",
        "SET_TITLE" => "N",
        "SHOW_404" => "Y",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    )
); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>