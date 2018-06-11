<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (version_compare(TYPO3_version, '6.0.0') < 0) {
	require_once t3lib_extMgm::extPath($_EXTKEY) . 'pi1/class.tx_pageclasses_pi1_v4.php';
	t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_pageclasses_pi1_v4.php', '_pi1', '', 1);
} else {
	require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'pi1/class.tx_pageclasses_pi1.php';
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_pageclasses_pi1.php', '_pi1', '', 1);
}
$TYPO3_CONF_VARS['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, tx_pageclasses_pass_on_css, tx_pageclasses_stop_css_inheritance';

