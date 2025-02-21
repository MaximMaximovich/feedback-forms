<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var CBitrixComponent $component */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;


?>

<div class="form-feedback">
    <form id="feedback" action="<?=POST_FORM_ACTION_URI?>" method="post" name="form">
    <input type="hidden" name="mode" value="<?= $arResult['HIDDEN'] ?>">
    <div class="form-content">
        <div class="input-box">
            <label for="form_fio">
                <?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_NAME_CAPTION") ?><span> *</span>
            </label>
            <input
                    type="text"
                    class="input _pr _text"
                    id="form_fio"
                    name="FIO"
                    maxlength="254"
                    data-parsley-trigger="input"
                    required
                    data-parsley-required-message="<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PARSLEY_REQUIRED_MESSAGE") ?>"
                    data-parsley-error-message="<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PARSLEY_NAME_ERROR_MESSAGE") ?>"
                    data-parsley-minlength="3"
            />
        </div>
        <div class="input-box">
            <label for="form_phone">
                <?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PHONE_CAPTION") ?>
            </label>
            <input
                    type="tel"
                    class="input phone _pr"
                    id="form_phone"
                    name="PHONE"
                    placeholder="+7 (___) ___-____"
                    data-parsley-trigger="input"
                    data-parsley-error-message="<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PARSLEY_PHONE_ERROR_MESSAGE") ?>"
                    data-parsley-pattern="\+7\s\(\d{3}\)\s\d{3}\s\d{4}"
            />
        </div>
        <div class="input-box">
            <label for="form_email">
                <?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_EMAIL_CAPTION") ?><span id="email_required"> *</span>
            </label>
            <input
                    type="email"
                    class="input email _pr"
                    id="form_email"
                    name="EMAIL"
                    maxlength="254"
                    data-parsley-trigger="input"
                    required
                    data-parsley-required-message="<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PARSLEY_REQUIRED_MESSAGE") ?>"
                    data-parsley-error-message="<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_PARSLEY_EMAIL_ERROR_MESSAGE") ?>"
                    data-parsley-type="email"
            />
        </div>
        <div class="input-box">
            <label for="form_call_time">
                <?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_TINE_CAPTION") ?>
            </label>
            <input
                    type="text"
                    placeholder="HH:MM"
                    class="input time _pr"
                    id="form_call_time"
                    maxlength="5"
                    name="CALL_TIME" />
        </div>
        <input
                role="button"
                data-sitekey="<?= $arParams['RE_CAPTCHA_SITE_KEY'] ?>"
                data-callback="onCallbackSubmit"
                class="bot-send-mail g-recaptcha" type='submit' value='<?= Loc::getMessage("FEEDBACK_FORM_TEMPLATE_BUTTON_TEXT") ?>'>
    </div>
    <div class="error-msg"></div>
    </form>
</div>

<?php
$arJSParams = array(
    "dataArParams" => $this->__component->getSignedParameters(),
    "dataAddiSigned" => $arResult["additionalSignedParams"],
);
?>
<script type="text/javascript">
    window.ajaxPrms = <?= json_encode($arJSParams) ?>
</script>


