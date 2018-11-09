<?php
/**
 * ext tables config file for ext: "formhandler"
 *
 * @author Reinhard Führicht <rf@typoheads.at>
 */
defined('TYPO3_MODE') or die();


if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Typoheads.formhandler',
        'web',
        'log',
        'bottom',
        [
            'Module' => 'index, view, selectFields, export, deleteLogRows'
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:formhandler/Resources/Public/Icons/Extension.png',
            'labels' => 'LLL:EXT:formhandler/Resources/Private/Language/locallang_mod.xml'
        ]
    );
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_formhandler_log'
);
