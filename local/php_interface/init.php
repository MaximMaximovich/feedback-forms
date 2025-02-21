<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
    'Disweb\FormValidatorPhone' => '/local/lib/validators/form_validator_phone.php',
    'Disweb\FormValidatorEmail' => '/local/lib/validators/form_validator_email.php',
]);

if( file_exists(__DIR__ . '/lib/Helper.php') ) {
    include_once __DIR__ . '/lib/Helper.php';
}

if( file_exists(__DIR__ . '/include/constants.php') ) {
    include_once __DIR__ . '/include/constants.php';
}

if( file_exists(__DIR__ . '/include/events.php') ) {
    include_once __DIR__ . '/include/events.php';
}