<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости 2");?><?$APPLICATION->IncludeComponent(
	"qperfect:simple.list",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"NEWS_IBLOCK_ID" => NEWS_IBLOCK_ID
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>