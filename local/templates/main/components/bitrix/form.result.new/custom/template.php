<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//echo '<pre>'.htmlspecialchars(print_r($arResult, true)).'</pre>';

use Bitrix\Main\Localization\Loc;
?>
<div class="form-feedback">
    <?=$arResult["FORM_HEADER"]?>
        <div class="form-content">
            <div class="input-box">
                <label for="<?=$arResult['funcGetParams']('NAME', $arResult)['id']?>">
                    <?=$arResult["QUESTIONS"]['NAME']['CAPTION']?>
                    <?=($arResult["QUESTIONS"]['NAME']['REQUIRED'] === 'Y' ? '<span> *</span>' : '')?>
                </label>
                <input
                    type="text"
                    class="input _pr _text<?=$arResult['funcGetParams']('NAME', $arResult)['class']?>"
                    id="<?=$arResult['funcGetParams']('NAME', $arResult)['id']?>"
                    name="<?=$arResult['funcGetParams']('NAME', $arResult)['name']?>"
                    maxlength="64"
                    data-parsley-trigger="input"
                    required
                    data-parsley-required-message="<?= Loc::getMessage("FORM_TEMPLATE_PARSLEY_REQUIRED_MESSAGE") ?>"
                    data-parsley-error-message="<?= Loc::getMessage("FORM_TEMPLATE_PARSLEY_NAME_ERROR_MESSAGE") ?>"
                    data-parsley-minlength="3" />
            </div>
            <div class="input-box">
                <label
                    for="<?=$arResult['funcGetParams']('PHONE', $arResult)['id']?>">
                    <?=$arResult["QUESTIONS"]['PHONE']['CAPTION']?>
                    <?=($arResult["QUESTIONS"]['PHONE']['REQUIRED'] === 'Y' ? '<span> *</span>' : '')?>
                </label>
                <input
                    type="tel"
                    class="input phone _pr <?=$arResult['funcGetParams']('PHONE', $arResult)['class']?>"
                    id="<?=$arResult['funcGetParams']('PHONE', $arResult)['id']?>"
                    name="<?=$arResult['funcGetParams']('PHONE', $arResult)['name']?>"
                    placeholder="+7 (___) ___-____"
                    data-parsley-trigger="input"
                    data-parsley-error-message="<?= Loc::getMessage("FORM_TEMPLATE_PARSLEY_PHONE_ERROR_MESSAGE") ?>"
                    data-parsley-pattern="\+7\s\(\d{3}\)\s\d{3}\s\d{4}" />
            </div>
            <div class="input-box">
                <label
                    for="<?=$arResult['funcGetParams']('EMAIL', $arResult)['id']?>">
                    <?=$arResult["QUESTIONS"]['EMAIL']['CAPTION']?>
                    <?=($arResult["QUESTIONS"]['EMAIL']['REQUIRED'] === 'Y' ? '<span id="email-enabled"> *</span>' : '')?>
                </label>
                <input
                    type="email"
                    class="input email _pr <?=$arResult['funcGetParams']('EMAIL', $arResult)['class']?>"
                    id="<?=$arResult['funcGetParams']('EMAIL', $arResult)['id']?>"
                    name="<?=$arResult['funcGetParams']('EMAIL', $arResult)['name']?>"
                    maxlength="64"
                    data-parsley-trigger="input"
                    required
                    data-parsley-required-message="<?= Loc::getMessage("FORM_TEMPLATE_PARSLEY_REQUIRED_MESSAGE") ?>"
                    data-parsley-error-message="<?= Loc::getMessage("FORM_TEMPLATE_PARSLEY_EMAIL_ERROR_MESSAGE") ?>"
                    data-parsley-type="email" />
            </div>
            <div class="input-box">
                <label
                    for="<?=$arResult['funcGetParams']('TIME', $arResult)['id']?>">
                    <?=$arResult["QUESTIONS"]['TIME']['CAPTION']?>
                    <?=($arResult["QUESTIONS"]['TIME']['REQUIRED'] === 'Y' ? '<span id="email-enabled"> *</span>' : '')?>
                </label>
                <input
                    type="text"
                    placeholder="HH:MM"
                    class="input time _pr <?=$arResult['funcGetParams']('TIME', $arResult)['class']?>"
                    id="<?=$arResult['funcGetParams']('TIME', $arResult)['id']?>"
                    name="<?=$arResult['funcGetParams']('TIME', $arResult)['name']?>" />
            </div>
            <?php if ($arResult["isUseCaptcha"] === true): ?>
            <div class="captcha-promo">
                <input id="captchaSid" type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHACode']?>" />
                <img
                    id="captchaImg"
                    class="captcha-img"
                    src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHACode"]?>"
                    style="cursor:pointer"
                    width="180"
                    height="40" />
                <img
                    id="reloadCaptcha"
                    class="captcha-img"
                    src="<?= SITE_TEMPLATE_PATH?>/dist/img/icons/reload-green.png"
                    style="cursor:pointer"
                    width="40"
                    height="40" />
            </div>
                <label><?= Loc::getMessage("FORM_TEMPLATE_CAPTCHA_MESSAGE") ?></label>
                <?=$arResult["CAPTCHA_FIELD"]?>
            <?php endif; ?>
            <input class="bot-send-mail" type='submit' value='<?=$arResult["arForm"]["BUTTON"]?>'>
        </div>
        <div class="error-msg"></div>
        <?=$arResult["FORM_FOOTER"]?>
</div>

<?php
$arJSParams = array(
    "link" => $templateFolder . "/ajax.php",
    "formId" => $arResult['arForm']['SID'],
    "phoneId" => $arResult['funcGetParams']('PHONE', $arResult)['id'],
    "emailId" => $arResult['funcGetParams']('EMAIL', $arResult)['id'],
    "isCaptcha" => $arResult["isUseCaptcha"] === true,
    "CAPTCHAHandler" => $templateFolder . "/reload_captcha.php",
);
?>
<script type="text/javascript">
    window.ajaxParams = <?= CUtil::PhpToJSObject($arJSParams); ?>
</script>