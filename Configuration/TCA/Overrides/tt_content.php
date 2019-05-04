<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['formhandler_pi1'] = 'formhandler-ctype-pi1';

$GLOBALS['TCA']['tt_content']['types']['formhandler_pi1']['showitem'] = '
    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;header,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility,
    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.behaviour,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended
';

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
