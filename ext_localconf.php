<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

//require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'pi1/class.tx_pageclasses_pi1.php';
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, '', '', '', 1);

// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', '# Setting ' . $_EXTKEY . ' plugin TypoScript
// plugin.Pageclasses = USER
// plugin.Pageclasses.userFunc = SvenT\Pageclasses\Plugin\Pageclasses->main
// '
// );



$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, tx_pageclasses_pass_on_css, tx_pageclasses_stop_css_inheritance';

