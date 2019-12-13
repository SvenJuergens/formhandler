<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
    'formhandler',
    'pi1/class.tx_formhandler_pi1.php',
    '_pi1',
    'CType',
    0
);

$overrideSetup = 'plugin.tx_formhandler_pi1.userFunc = Typoheads\Formhandler\Controller\Dispatcher->main';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'formhandler',
    'setup',
    $overrideSetup
);

//Hook in tslib_content->stdWrap
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][$_EXTKEY] =
    'Typoheads\Formhandler\Hooks\StdWrapHook';

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['formhandler'] =
    'EXT:formhandler/Classes/Http/Validate.php';

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['formhandler-removefile'] =
    'EXT:formhandler/Classes/Http/RemoveFile.php';

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['formhandler-ajaxsubmit'] =
    'EXT:formhandler/Classes/Http/Submit.php';

// Register for hook to show preview of tt_content element of CType="form_formframework" in page module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['formhandler_pi1'] =
    \Typoheads\Formhandler\Hooks\CustomPagePreviewRenderer::class;

// load default PageTS config from static file
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:formhandler/Configuration/TypoScript/pageTsConfig.ts">'
);

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\CMS\\Scheduler\\Task\\TableGarbageCollectionTask']['options']['tables']['tx_formhandler_log'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\CMS\\Scheduler\\Task\\TableGarbageCollectionTask']['options']['tables']['tx_formhandler_log'] = [
        'dateField' => 'tstamp',
        'expirePeriod' => 180
    ];
}

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
    'formhandler-foldericon',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:formhandler/Resources/Public/Images/pagetreeicon.png']
);
$iconRegistry->registerIcon(
    'formhandler-ctype-pi1',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:formhandler/Resources/Public/Icons/Extension.png']
);
