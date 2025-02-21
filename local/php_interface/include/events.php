<?php

use \Bitrix\Main\EventManager;


EventManager::getInstance()->addEventHandlerCompatible('form', 'onFormValidatorBuildList', [
    'Disweb\FormValidatorPhone',
    'getDescription',
]);

EventManager::getInstance()->addEventHandlerCompatible('form', 'onFormValidatorBuildList', [
    'Disweb\FormValidatorEmail',
    'getDescription',
]);