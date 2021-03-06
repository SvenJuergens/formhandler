<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Typoheads.formhandler',
        'web',
        'log',
        'bottom',
        [
            'Module' => 'index, view, selectFields, export'
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:formhandler/Resources/Public/Icons/Extension.png',
            'labels' => 'LLL:EXT:formhandler/Resources/Private/Language/locallang_mod.xlf'
        ]
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_formhandler_log'
);
