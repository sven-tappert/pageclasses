<?php
$tempColumns = array (
	'tx_pageclasses_css_classes' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:pageclasses/locallang_db.xml:pages.tx_pageclasses_css_classes',
		'config' => array (
			'type' => 'input',
			'size' => '20',
			'max' => '150',
			'eval' => 'trim',
		)
	),
	'tx_pageclasses_css_classes_subpages' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:pageclasses/locallang_db.xml:pages.tx_pageclasses_css_classes_subpages',
		'config' => array (
			'type' => 'input',
			'size' => '20',
			'max' => '150',
			'eval' => 'trim',
		)
	),
	'tx_pageclasses_stop_css_inheritance' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:pageclasses/locallang_db.xml:pages.tx_pageclasses_stop_css_inheritance',
		'config' => array (
			'type' => 'check',
		)
	),
);


$paletteName = 'tx_pageclasses_css';

if (version_compare(TYPO3_version, '6.0.0') < 0) {
	t3lib_extMgm::addTCAcolumns('pages', $tempColumns);
	t3lib_extMgm::addFieldsToPalette('pages', $paletteName, 'tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, --linebreak--, tx_pageclasses_stop_css_inheritance');
	t3lib_extMgm::addToAllTCAtypes('pages','--palette--;LLL:EXT:pageclasses/locallang_db.xml:pages.tx_pageclasses_css_palette;' . $paletteName, '', 'before:layout'); 
} else {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', $paletteName, 'tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, --linebreak--, tx_pageclasses_stop_css_inheritance');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','--palette--;LLL:EXT:pageclasses/locallang_db.xml:pages.tx_pageclasses_css_palette;' . $paletteName, '', 'before:layout'); 
}
$GLOBALS['TCA']['pages']['palettes'][$paletteName]['canNotCollapse'] = 1;
