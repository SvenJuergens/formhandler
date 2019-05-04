<?php
namespace Typoheads\Formhandler\Ajax;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
*                                                                        *
* TYPO3 is free software; you can redistribute it and/or modify it under *
* the terms of the GNU General Public License version 2 as published by  *
* the Free Software Foundation.                                          *
*                                                                        *
* This script is distributed in the hope that it will be useful, but     *
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
* TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
* Public License for more details.                                       *
*
*                                                                        */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Typoheads\Formhandler\Component\Manager;
use Typoheads\Formhandler\Utility\GeneralUtility as FormhandlerGeneralUtility;
use Typoheads\Formhandler\Utility\Globals;
use Typoheads\Formhandler\View\AjaxValidation;

/**
 * A class validating a field via AJAX.
 *
 * @author    Reinhard Führicht <rf@typoheads.at>
 */
class Validate
{
    public $id;
    public $componentManager;

    /**
     * @var array
     */
    protected $templates = [
        'spanSuccess' => '<span class="success">%s</span>',
        'spanError' => '<span class="error">%s</span>',
    ];

    /**
     * Main method of the class.
     *
     * @return string The HTML list of remaining files to be displayed in the form
     */
    public function main()
    {
        $this->init();
        $field = htmlspecialchars(GeneralUtility::_GP('field'));
        if ($field) {
            $randomID = htmlspecialchars(GeneralUtility::_GP('randomID'));
            Globals::setCObj($this->getTypoScriptFrontendController()->cObj);
            Globals::setRandomID($randomID);
            if (!Globals::getSession()) {
                $ts = $this->getTypoScriptFrontendController()->tmpl->setup['plugin.']['Tx_Formhandler.']['settings.'];
                $sessionClass = FormhandlerGeneralUtility::getPreparedClassName($ts['session.'], 'Session\PHP');
                Globals::setSession($this->componentManager->getComponent($sessionClass));
            }
            $this->settings = Globals::getSession()->get('settings');
            Globals::setFormValuesPrefix(FormhandlerGeneralUtility::getSingle($this->settings, 'formValuesPrefix'));
            $gp = FormhandlerGeneralUtility::getMergedGP();
            $validator = $this->componentManager->getComponent('\Typoheads\Formhandler\Validator\Ajax');
            $errors = [];
            $valid = $validator->validateAjax($field, $gp, $errors);

            if ($valid) {
                $content = FormhandlerGeneralUtility::getSingle($this->settings['ajax.']['config.'], 'ok');
                if (strlen($content) === 0) {
                    $okImage = ExtensionManagementUtility::extPath('formhandler') . 'Resources/Public/Images/ok.png';
                    $okImage = PathUtility::getAbsoluteWebPath($okImage);
                    $content = '<img src="' . ltrim($okImage, '/') . '" />';
                } else {
                    $gp = [
                        $_GET['field'] => $_GET['value']
                    ];
                    $view = $this->initView($content);
                    $content = $view->render($gp, $errors);
                }
                $content = sprintf($this->templates['spanSuccess'], $content);
            } else {
                $content = FormhandlerGeneralUtility::getSingle($this->settings['ajax.']['config.'], 'notOk');
                if (strlen($content) === 0) {
                    $notOkImage = ExtensionManagementUtility::extPath('formhandler') . 'Resources/Public/Images/notok.png';
                    $notOkImage = PathUtility::getAbsoluteWebPath($notOkImage);
                    $content = '<img src="' . ltrim($notOkImage, '/') . '" />';
                } else {
                    $view = $this->initView($content);
                    $gp = [
                        $_GET['field'] => $_GET['value']
                    ];
                    $content = $view->render($gp, $errors);
                }
                $content = sprintf($this->templates['spanError'], $content);
            }
            print $content;
        }
    }

    /**
     * Initialize the class. Read GET parameters
     */
    protected function init()
    {
        $this->id = isset($_GET['pid']) ? (int)$_GET['pid'] : (int)$_GET['id'];
        $this->componentManager = GeneralUtility::makeInstance(Manager::class);
        Globals::setAjaxMode(true);
        FormhandlerGeneralUtility::initializeTSFE($this->id);
    }

    /**
     * Initialize the AJAX validation view.
     *
     * @param string $content The raw content
     * @return AjaxValidation The view class
     */
    protected function initView($content)
    {
        $viewClass = '\Typoheads\Formhandler\View\AjaxValidation';
        $view = $this->componentManager->getComponent($viewClass);
        $view->setLangFiles(FormhandlerGeneralUtility::readLanguageFiles([], $this->settings));
        $view->setSettings($this->settings);
        $templateName = 'AJAX';
        $template = str_replace('###fieldname###', htmlspecialchars($_GET['field']), $content);
        $template = '###TEMPLATE_' . $templateName . '###' . $template . '###TEMPLATE_' . $templateName . '###';
        $view->setTemplate($template, 'AJAX');
        return $view;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
