<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, tx_pageclasses_pass_on_css, tx_pageclasses_stop_css_inheritance';
