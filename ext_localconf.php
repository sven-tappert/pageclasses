<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

// Make the page fields available in the rootline (obsolete in TYPO3 13, where
// the option was removed because all pages columns are part of the rootline).
if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() <= 12) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= ',tx_pageclasses_css_classes,tx_pageclasses_css_classes_subpages,tx_pageclasses_pass_on_css,tx_pageclasses_stop_css_inheritance';
}
