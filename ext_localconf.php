<?php

declare(strict_types=1);

defined('TYPO3') or die();

// Obsolete since TYPO3 13.2 (#103752), where the option was removed because all
// pages columns are part of the rootline anyway. Guarded by isset() so that the
// fields are still registered on TYPO3 12 and on TYPO3 13.0/13.1.
if (isset($GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'])) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes,tx_pageclasses_css_classes_subpages,tx_pageclasses_pass_on_css,tx_pageclasses_stop_css_inheritance';
}
