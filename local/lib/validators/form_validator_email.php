<?php

namespace Disweb;

use \Bitrix\Main\EventManager;

class FormValidatorEmail
{
    static function getDescription()
    {
        return [
            'NAME' => 'dw_email', // идентификатор
            'DESCRIPTION' => 'E-mail', // наименование
            'TYPES' => [
                'text'
            ], // типы полей
            'SETTINGS' => [__CLASS__, 'getSettings'], // метод, возвращающий массив настроек
            'CONVERT_TO_DB' => [__CLASS__, 'toDB'], // метод, конвертирующий массив настроек в строку
            'CONVERT_FROM_DB' => [__CLASS__, 'fromDB'], // метод, конвертирующий строку настроек в массив
            'HANDLER' => [__CLASS__, 'doValidate'], // валидатор
        ];
    }

    static function getSettings()
    {
        return [];
    }

    static function toDB($arParams)
    {
        // возвращаем сериализованную строку
        return serialize($arParams);
    }

    static function fromDB($strParams)
    {
        // никаких преобразований не требуется, просто вернем десериализованный массив
        return unserialize($strParams);
    }

    static function doValidate($arParams, $arQuestion, $arAnswers, $arValues)
    {
        global $APPLICATION;

        foreach ($arValues as $value)
        {
            // проверяем на пустоту
            if (!preg_match("/^([0-9a-zA-Z]+([\-\.\_]{0,1}[0-9a-zA-Z]*)*@[0-9a-zA-ZабвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]+([\-\.\_]{0,1}[0-9a-zA-Z]+)*[\.][a-zA-ZабвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]{2,9})$/", $value))
            {
                // вернем ошибку
                $APPLICATION->ThrowException('Не верно запонен "E-mail"');

                return false;
            }

        }

        // все значения прошли валидацию, вернем true
        return true;
    }
}