<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
	
	$tempColumns = array (
		'tx_pageclasses_css_classes' => array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:pages.tx_pageclasses_css_classes',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'max' => '150',
				'eval' => 'trim',
			)
		),
		'tx_pageclasses_css_classes_subpages' => array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:pages.tx_pageclasses_css_classes_subpages',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'max' => '150',
				'eval' => 'trim',
			)
		),
		'tx_pageclasses_stop_css_inheritance' => array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:pages.tx_pageclasses_stop_css_inheritance',
			'config' => array (
				'type' => 'check',
			)
		),
	);
	
	
	$paletteName = 'tx_pageclasses_css';
	
	
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', $paletteName, 'tx_pageclasses_css_classes, tx_pageclasses_css_classes_subpages, --linebreak--, tx_pageclasses_stop_css_inheritance,');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','--palette--;LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:pages.tx_pageclasses_css_palette;' . $paletteName, (string)\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SYSFOLDER .','. (string)\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SPACER, 'before:backend_layout');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','--palette--;LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:pages.tx_pageclasses_css_palette;' . $paletteName, '', 'before:layout'); 
	
	$GLOBALS['TCA']['pages']['palettes'][$paletteName]['canNotCollapse'] = 1;
	
	
});
