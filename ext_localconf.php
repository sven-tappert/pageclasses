<?php

defined('TYPO3') or die();

// obsolete in LTS 13
if ((new (\TYPO3\CMS\Core\Information\Typo3Version::class))->getMajorVersion() <= 12) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, tx_pageclasses_pass_on_css, tx_pageclasses_stop_css_inheritance';
}
