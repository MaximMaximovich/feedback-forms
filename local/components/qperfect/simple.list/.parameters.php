<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
		    "PARENT" => "BASE",
			"NAME" => GetMessage("SIMPLE_LIST_NEWS_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "CACHE_TIME" => array("DEFAULT" => 36000000),
	),
);