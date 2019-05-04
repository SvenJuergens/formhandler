<?php
namespace Typoheads\Formhandler\PreProcessor;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * A PreProcessor cleaning session values stored by Finisher_StoreGP
 *
 * Example:
 * <code>
 * preProcessors.1.class = Tx_Formhandler_PreProcessor_ClearSession
 * </code>
 *
 * @author    Stefan Froemken <firma@sfroemken.de>
 * @author    Reinhard Führicht <rf@typoheads.at>
 */
class ClearSession extends AbstractPreProcessor
{

    /**
     * The main method called by the controller
     *
     * @return array The probably modified GET/POST parameters
     */
    public function process()
    {
        $sessionKeysToRemove = [
            'finisher-storegp'
        ];
        if ($this->settings['sessionKeysToRemove']) {
            $sessionKeysToRemove = GeneralUtility::trimExplode(',', $this->utilityFuncs->getSingle($this->settings, 'sessionKeysToRemove'));
        }

        foreach ($sessionKeysToRemove as $sessionKey) {
            $this->getTypoScriptFrontendController()->fe_user->setKey('ses', $sessionKey, null);
            $this->getTypoScriptFrontendController()->fe_user->storeSessionData();
        }

        return $this->gp;
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
