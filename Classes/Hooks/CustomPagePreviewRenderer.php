<?php
namespace Typoheads\Formhandler\Hooks;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Typoheads\Formhandler\Utility\TcaUtility;

class CustomPagePreviewRenderer implements PageLayoutViewDrawItemHookInterface
{

    /**
     * Localisation prefix
     */
    public const  L10N_PREFIX = 'LLL:EXT:formhandler/Resources/Private/Language/locallang_tca.xlf:';

    /**
     * Preprocesses the preview rendering of the content element "form_formframework".
     *
     * @param PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionalities
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        if ($row['CType'] !== 'formhandler_pi1') {
            return;
        }
        
        $contentType = $parentObject->CType_labels[$row['CType']];
        $itemContent .= $parentObject->linkEditContent('<strong>' . htmlspecialchars($contentType) . '</strong>',
                $row) . '<br />';

        $flexFormData = GeneralUtility::makeInstance(FlexFormService::class)
            ->convertFlexFormContentToArray($row['pi_flexform']);

        if($flexFormData['predefined'] !== ''){
            $formsHeadline = $this->getLanguageService()->sL(
                self::L10N_PREFIX . 'formhandler.pi_flexform.template_predefined'
            ) . ': ';


            $configurationManager = $this->getObjectManager()->get(BackendConfigurationManager::class);
            $setup = $configurationManager->getTypoScriptSetup();
            $formsName = $this->getFormsName($setup, $flexFormData['predefined']);
            $itemContent .= $parentObject->linkEditContent(
                    $parentObject->renderText($formsHeadline . $formsName),
                    $row
                ) . '<br />';

        }
        $drawItem = false;
    }

    /**
     * @param $setup
     * @param $formTypoScriptName
     * @return string
     */
    public function getFormsName($setup, $formTypoScriptName): ?string
    {
        if (
            isset($setup['plugin.']['Tx_Formhandler.']['settings.']['predef.'][$formTypoScriptName])
            && is_array($setup['plugin.']['Tx_Formhandler.']['settings.']['predef.'][$formTypoScriptName])
        ) {
            $view = $setup['plugin.']['Tx_Formhandler.']['settings.']['predef.'][$formTypoScriptName];
            $beName = $view['name'];
            if (isset($view['name.']['data'])) {
                $data = explode(':', $view['name.']['data']);
                if (strtolower($data[0]) === 'lll') {
                    array_shift($data);
                }
                $langFileAndKey = implode(':', $data);
                $beName = $GLOBALS['LANG']->sL('LLL:' . $langFileAndKey);
            }
            return $beName;
        }
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager(): ObjectManager
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}