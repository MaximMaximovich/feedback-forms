<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['funcGetParams'] = function($questionCode, $arRes) {
    $question = $arRes["QUESTIONS"][$questionCode];
    $arrVALUES = $arRes['arrVALUES'];
    $id = $question['STRUCTURE'][0]['ID'];
    $type = $question['STRUCTURE'][0]['FIELD_TYPE'];
    $name = "form_{$type}_{$id}";
    $value = isset($arrVALUES[$name]) ? htmlentities($arrVALUES[$name]) : '';
    $required = $question['REQUIRED'] === 'Y' ? 'required' : '';
    $class = ' ' . $question['STRUCTURE'][0]['FIELD_PARAM'];
    //$input = "<input class=\"form-control {$class}\" type=\"text\" name=\"{$name}\" value=\"{$value}\" {$required}>";

    return [
        'id' => $id,
        'type' => $type,
        'name' => $name,
        'value' => $value,
        'required' => $required,
        'class' => $class,
        //'input' => $input,
    ];
};