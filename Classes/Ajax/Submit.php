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
*                                                                        */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Typoheads\Formhandler\Component\Manager;
use Typoheads\Formhandler\Utility\GeneralUtility as FormhandlerGeneralUtility;
use Typoheads\Formhandler\Utility\Globals;

/**
 * A class calling the controller and returning the form content as JSON. This class is called via AJAX.
 *
 * @author    Reinhard Führicht <rf@typoheads.at>
 */
class Submit
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var Manager
     */
    private $componentManager;

    /**
     * Main method of the class.
     *
     * @return string The HTML list of remaining files to be displayed in the form
     */
    public function main()
    {
        $this->init();

        $settings = $this->getTypoScriptFrontendController()->tmpl->setup['plugin.']['tx_formhandler_pi1.'];
        $settings['usePredef'] = Globals::getSession()->get('predef');

        $content = $this->getTypoScriptFrontendController()->cObj->cObjGetSingle('USER', $settings);

        $content = '{' . json_encode('form') . ':' . json_encode($content, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS) . '}';
        print $content;
    }

    /**
     * Initialize the class. Read GET parameters
     */
    protected function init()
    {
        $id = (int)($_GET['pid'] ?? $_GET['id'] ?? 0);

        $this->componentManager = GeneralUtility::makeInstance(Manager::class);
        FormhandlerGeneralUtility::initializeTSFE($id);

        $elementUID = (int)$_GET['uid'];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        $row = $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($elementUID, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetch();
        if (!empty($row)) {
            $this->getTypoScriptFrontendController()->cObj->data = $row;
            $this->getTypoScriptFrontendController()->cObj->current = 'tt_content_' . $elementUID;
        }

        Globals::setCObj($this->getTypoScriptFrontendController()->cObj);
        $randomID = htmlspecialchars(GeneralUtility::_GP('randomID'));
        Globals::setRandomID($randomID);
        Globals::setAjaxMode(true);
        if (!Globals::getSession()) {
            $ts = $this->getTypoScriptFrontendController()->tmpl->setup['plugin.']['Tx_Formhandler.']['settings.'];
            $sessionClass = FormhandlerGeneralUtility::getPreparedClassName($ts['session.'], 'Session\PHP');
            Globals::setSession($this->componentManager->getComponent($sessionClass));
        }

        $this->settings = Globals::getSession()->get('settings');

        //init ajax
        if ($this->settings['ajax.']) {
            $class = FormhandlerGeneralUtility::getPreparedClassName($this->settings['ajax.'], 'AjaxHandler\JQuery');
            $ajaxHandler = $this->componentManager->getComponent($class);
            Globals::setAjaxHandler($ajaxHandler);

            $ajaxHandler->init($this->settings['ajax.']['config.']);
            $ajaxHandler->initAjax();
        }
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
