<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Component\ParameterSigner;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Application;
use \Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);


/**
 *      FeedbackForm - Класс компонента формы обратной связи
 */

class FeedbackForm extends \CBitrixComponent implements Controllerable, Errorable
{

    protected Main\ErrorCollection $errorCollection;

    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->errorCollection = new Main\ErrorCollection();
    }

    public function onPrepareComponentParams($arParams): array
    {
        if (!Loader::includeModule('iblock')) {
            $this->errorCollection->setError(new Error(Loc::getMessage("FEEDBACK_FORM_CLASS_IBLOCK_MODULE_DOES_NOT_INSTALL"), 'iblock'));
        }

        $arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];
        $arParams['IBLOCK_SECTION'] = (int)$arParams['IBLOCK_SECTION'];
        $arParams['EVENT_NAME'] = trim($arParams['EVENT_NAME']);
        $arParams['FORM_TITLE'] = !empty($arParams['FORM_TITLE']) ? trim($arParams['FORM_TITLE']) : Loc::getMessage("FEEDBACK_FORM_CLASS_DEFAULT_TITLE");

        $arParams['INCLUDE_URL'] = ($arParams['INCLUDE_URL'] == 'Y');
        $arParams['IS_NEED_RE_CAPTCHA'] = (!empty($arParams['RE_CAPTCHA_SITE_KEY']) && !empty($arParams['RE_CAPTCHA_SECRET_KEY']));
        $arParams['RE_CAPTCHA_SCORE'] = (float)$arParams['RE_CAPTCHA_SCORE'];
        if ($arParams['RE_CAPTCHA_SCORE'] <= 0) $arParams['RE_CAPTCHA_SCORE'] = 0;

        $arParams['IBLOCK_FIELDS'] = array(
            'SORT',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE'
        );

        $context = Application::getInstance()->getContext();
        $server = $context->getServer();
        $requestScheme = $server->getRequestScheme() ?? "http";
        $curPage = $context->getRequest()->getRequestedPageDirectory();
        $httpHost = $server->getHttpHost();

        $arParams['URL'] = !empty($arParams['URL']) ? $arParams['URL'] : $requestScheme . '://' . $httpHost . $curPage;

        return $arParams;
    }

    public function executeComponent(): void
    {

        $this->signAdditionalParams();
        $this->IncludeComponentTemplate();

        // установка кода рекапчи
        if ($this->arParams['IS_NEED_RE_CAPTCHA']) {
            Asset::getInstance()->addString('<script src="https://www.google.com/recaptcha/api.js"></script>', true, AssetLocation::AFTER_CSS);
        }
    }

    protected function signAdditionalParams(): void
    {
        $this->initComponentTemplate();

        // поле 'HIDDEN' для идентификации компонента
        $this->arResult['HIDDEN'] = md5(implode('', $this->arParams) . __CLASS__);

        $additionalParams = [
            'hidden' => $this->arResult['HIDDEN'],
            'templateFolder' => Application::getInstance()->getContext()->getServer()->getDocumentRoot() . $this->getPath() . '/templates/' . $this->getTemplateName() . '/',
        ];

        $this->arResult['additionalSignedParams'] = \Bitrix\Main\Component\ParameterSigner::signParameters(
            $this->getName() . '_additional',
            $additionalParams
        );
    }

    /**
     *  Возвращает список основных параметров компонента
     *
     * @return string[]
     */
    protected function listKeysSignedParameters(): array
    {
        return [
            'IBLOCK_ID',
            'IBLOCK_SECTION',
            'EVENT_NAME',
            'FORM_TITLE',
            'URL',
            'INCLUDE_URL',
            'IS_NEED_RE_CAPTCHA',
            'RE_CAPTCHA_SITE_KEY',
            'RE_CAPTCHA_SECRET_KEY',
            'RE_CAPTCHA_SCORE',
            'FIELDS',
        ];
    }

    public function configureActions(): array
    {
        return [
            'ajaxSendFormData' => [
                '-prefilters' => [
                    ActionFilter\Authentication::class,
                ],
                '+prefilters' => [
                    new ActionFilter\Httpmethod([ActionFilter\Httpmethod::METHOD_POST]),
                ],
            ],
        ];
    }

    public function ajaxSendFormDataAction($arFormData, $addiSigned): array
    {

        $addiUnsigned = ParameterSigner::unsignParameters(
            $this->getName() . '_additional',
            $addiSigned
        );

        $arPostData = [];
        if (!empty($arFormData)) {
            foreach ($arFormData as $item) {
                $arPostData[$item['name']] = $item['value'];
            }
        }

        // антиспам
        if ($addiUnsigned['hidden'] !== $arPostData['mode']) {
            $this->errorCollection->setError(new Error(Loc::getMessage("FEEDBACK_FORM_CLASS_NOT_VALID_CLIENT_ID"), 'mode'));
        }

        if (!check_bitrix_sessid()) {
            $this->errorCollection->setError(new Error(Loc::getMessage("FEEDBACK_FORM_CLASS_NOT_VALID_SESS_ID"), 'sessid'));
        }

        // валидация
        $this->validate($arPostData, $this->arParams['FIELDS']);

        // если нужна рекапча, проверяем её
        if ($this->arParams['IS_NEED_RE_CAPTCHA']) {
            $this->checkCaptcha($arPostData);
        }

        $name = $this->getFormName();

        $elID = $this->addIblockElement($name, $arPostData);

        if (!empty($this->arParams['EVENT_NAME'])) {
            $this->sendEmail($arPostData);
        }

        $path = $addiUnsigned['templateFolder'];
        if (empty($this->getErrors())) {
            $arOut = array(
                'status' => true,
                'id' => $elID
            );
            $fileName = 'success.php';
            if(file_exists($path . $fileName)){
                $arOut['modal'] = require $path . $fileName;
            }

            return $arOut;
        }

        $arOut = array(
            'status' => false,
            'errors' => $this->getErrors()
        );
        $fileName = 'unsuccess.php';
        if(file_exists($path . $fileName)){
            $arOut['modal'] = require $path . $fileName;
        }

        return $arOut;
    }

    private function validate($data, $rules): void
    {
        $inputNames = array_keys($rules);
        if (!empty($data)) {
            foreach ($data as $name => $value) {
                if (in_array($name, $inputNames)) {
                    // required
                    if (empty($value) && $rules[$name]['required']) {
                        $this->errorCollection->setError(
                            new Error(
                                Loc::getMessage("FEEDBACK_FORM_CLASS_INPUT_REQUIRED_ERROR_MESSAGE",
                                    array("#INPUT_NAME#" => $name)),
                                'inp_required'));
                    }
                    // minlength
                    if (!empty($value) && !empty($rules[$name]['minlength'])) {
                        if (mb_strlen($value) < $rules[$name]['minlength'])
                            $this->errorCollection->setError(
                                new Error(
                                    Loc::getMessage("FEEDBACK_FORM_CLASS_INPUT_MINLENGTH_ERROR_MESSAGE",
                                        array("#INPUT_NAME#" => $name, "#MINLENGHT#" => $rules[$name]['minlength'])),
                                    'inp_minlength'));
                    }
                    // minlength
                    if (!empty($value) && !empty($rules[$name]['maxlength'])) {
                        if (mb_strlen($value) > $rules[$name]['maxlength'])
                            $this->errorCollection->setError(
                                new Error(
                                    Loc::getMessage("FEEDBACK_FORM_CLASS_INPUT_MAXLENGTH_ERROR_MESSAGE",
                                        array("#INPUT_NAME#" => $name, "#MAXLENGHT#" => $rules[$name]['maxlength'])),
                                    'inp_maxlength'));
                    }
                    // regExp
                    if (!empty($value) && !empty($rules[$name]['regExp'])) {
                        if (!preg_match($rules[$name]['regExp'], $value))
                            $this->errorCollection->setError(
                                new Error(
                                    Loc::getMessage("FEEDBACK_FORM_CLASS_INPUT_REG_EXPR_ERROR_MESSAGE",
                                        array("#INPUT_NAME#" => $name, "#REG_EXPR#" => $rules[$name]['regExp'])),
                                    'inp_regexp'));
                    }
                }
            }
        }
    }

    private function getFormName()
    {
        return $this->arParams['FORM_TITLE'] . ' - ' . ConvertTimeStamp(time(), "FULL");
    }

    private function addIblockElement($name, $data)
    {
        $element = new \CIBlockElement;

        $code = Cutil::translit($name,"ru", array("replace_space"=>"_","replace_other"=>"_"));

        $arFields = array(
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'IBLOCK_SECTION' => $this->arParams['IBLOCK_SECTION'],
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_VALUES' => array()
        );

        if (!empty($this->arParams['FIELDS']) && !empty($data)) {
            foreach ($this->arParams['FIELDS'] as $key => $value) {

                $strCode = is_numeric($key) ? $value : $key;

                if (in_array($strCode, $this->arParams['IBLOCK_FIELDS'])) {
                    $arFields[$strCode] = $data[$strCode];
                } else {
                    $arFields['PROPERTY_VALUES'][$strCode] = $data[$strCode];
                }
            }
        }

        if ($this->arParams['INCLUDE_URL'] == "Y") {
            $arFields['PROPERTY_VALUES']['URL'] = $this->arParams['URL'];
        }

        $arFields['PROPERTY_VALUES']['FORM_NAME'] = $this->arParams['FORM_TITLE'];

        $result = $element->Add($arFields);

        if ($result === false) {
            $this->arResult['ERROR'][] = $element->LAST_ERROR;
            $this->errorCollection->setError(
                new Error(Loc::getMessage("FEEDBACK_FORM_CLASS_ADD_IBLOCK_ERROR_MESSAGE"),
                    'add_iblock',
                    $element->LAST_ERROR
                ));
            return false;
        }

        return $result;
    }

    private function sendEmail($data): void
    {
        $arMailFields = array(
            'EVENT_NAME' => $this->arParams['EVENT_NAME'],
            'LID' => $this->getSiteId(),
            'C_FIELDS' => array(),
            "FILE" => array()
        );

        if (!empty($this->arParams['FIELDS']) && !empty($data)) {
            foreach ($this->arParams['FIELDS'] as $key => $value) {

                $strCode = is_numeric($key) ? $value : $key;

                $arMailFields['C_FIELDS'][$strCode] = $data[$strCode];
            }
        }

        $arMailFields['C_FIELDS']['FORM_TITLE'] = $this->arParams['FORM_TITLE'];
        if ($this->arParams['INCLUDE_URL']) {
            $arMailFields['C_FIELDS']['URL'] = $this->arParams['URL'];
        }

        Event::send($arMailFields);
    }


    private function checkCaptcha($data): void
    {
        $remoteAddr = Application::getInstance()->getContext()->getServer()->getRemoteAddr();
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="
            . $this->arParams['RE_CAPTCHA_SECRET_KEY'] . "&response="
            . $data['g-recaptcha-response'] . "&remoteip="
            . $remoteAddr);
        $arResponse = json_decode($response, true);

        // рекапча не прошла проверку
        if (!$arResponse['success'] || ($arResponse['score'] < $this->arParams['RE_CAPTCHA_SCORE'])) {
            $this->errorCollection->setError(
                new Error(
                    Loc::getMessage("FEEDBACK_FORM_CLASS_CAPTCHA_ERROR_MESSAGE"),
                    'captcha',
                    array(
                        'response' => $arResponse,
                        'key' => $this->arParams['RE_CAPTCHA_SECRET_KEY'],
                        'g_recaptcha_response' => $arResponse,
                        'remote_addr' => $remoteAddr
                    )
                ));
        }
    }

    /**
     * Getting array of errorCollection.
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getErrorByCode($code): ?Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }


}