<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (version_compare(TYPO3_version, '6.0.0') < 0) {
	t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_pageclasses_pi1_v4.php', '_pi1', '', 1);
} else {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_pageclasses_pi1.php', '_pi1', '', 1);
}
$TYPO3_CONF_VARS['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, tx_pageclasses_pass_on_css, tx_pageclasses_stop_css_inheritance';

