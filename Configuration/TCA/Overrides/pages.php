<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$languageFile = 'LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:';
$paletteName = 'tx_pageclasses_css';

$temporaryColumns = [
    'tx_pageclasses_css_classes' => [
        'exclude' => 1,
        'l10n_mode' => 'exclude',
        'label' => $languageFile . 'pages.tx_pageclasses_css_classes',
        'config' => [
            'type' => 'input',
            'size' => 20,
            'max' => 150,
            'eval' => 'trim',
        ],
    ],
    'tx_pageclasses_css_classes_subpages' => [
        'exclude' => 1,
        'l10n_mode' => 'exclude',
        'label' => $languageFile . 'pages.tx_pageclasses_css_classes_subpages',
        'config' => [
            'type' => 'input',
            'size' => 20,
            'max' => 150,
            'eval' => 'trim',
        ],
    ],
    'tx_pageclasses_stop_css_inheritance' => [
        'exclude' => 1,
        'l10n_mode' => 'exclude',
        'label' => $languageFile . 'pages.tx_pageclasses_stop_css_inheritance',
        'config' => [
            'type' => 'check',
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('pages', $temporaryColumns);
ExtensionManagementUtility::addFieldsToPalette(
    'pages',
    $paletteName,
    'tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, --linebreak--, tx_pageclasses_stop_css_inheritance,'
);

// Doktypes without a "layout" field get the palette in front of the backend layout instead.
ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;' . $languageFile . 'pages.tx_pageclasses_css_palette;' . $paletteName,
    PageRepository::DOKTYPE_SYSFOLDER . ',' . PageRepository::DOKTYPE_SPACER,
    'before:backend_layout'
);
ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;' . $languageFile . 'pages.tx_pageclasses_css_palette;' . $paletteName,
    '',
    'before:layout'
);

$GLOBALS['TCA']['pages']['palettes'][$paletteName]['canNotCollapse'] = 1;
