<?php

namespace Disweb;

use \Bitrix\Main\EventManager;

class FormValidatorPhone
{
    static function getDescription()
    {
        return [
            'NAME' => 'dw_phone', // идентификатор
            'DESCRIPTION' => 'Номер телефона', // наименование
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

        if (!empty($arValues[0]) && !self::is_valid_russian_phone_number($arValues[0])) {
            // вернем ошибку
            $APPLICATION->ThrowException('Не верно заполнен "Номер телефона"');

            return false;
        }

        // все значения прошли валидацию, вернем true
        return true;
    }

    static function is_valid_russian_phone_number($phone) {

        // Удаляем все не символы кроме цифр
        $phone = preg_replace('/\D/', '', $phone);

        // Номер должен начинается на цифру 7
        if (substr($phone, 0, 1) !== '7') {
            return false;
        }

        // Длиной 11 символов (включая 7)
        if (strlen($phone) !== 11) {
            return false;
        }

        return true;
    }
}