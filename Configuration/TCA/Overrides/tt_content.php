<?php
defined('TYPO3_MODE') or die();

// Add flexform field to plugin options
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['formhandler_pi1'] =
    'pi_flexform';

// Add flexform DataStructure
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:formhandler/Configuration/FlexForms/flexform_ds.xml',
    'formhandler_pi1'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:formhandler/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi1',
        'formhandler_pi1'
    ],
    'CType',
    'formhandler'
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'formhandler',
    'Configuration/TypoScript/ExampleConfiguration',
    'Example Configuration'
);
