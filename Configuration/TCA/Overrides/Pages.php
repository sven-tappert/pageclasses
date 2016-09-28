<?php
$tempColumns = array (
	'tx_sventbodyclass_css_classes' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_css_classes',
		'config' => array (
			'type' => 'input',
			'size' => '20',
			'max' => '150',
			'eval' => 'trim',
		)
	),
	'tx_sventbodyclass_css_classes_subpages' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_css_classes_subpages',
		'config' => array (
			'type' => 'input',
			'size' => '20',
			'max' => '150',
			'eval' => 'trim',
		)
	),
// 	'tx_sventbodyclass_pass_on_css' => array (
// 		'exclude' => 1,
// 		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_pass_on_css',
// 		'config' => array (
// 			'type' => 'select',
// 			'items' => array(
// 				array(
// 					'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_pass_on_css.inherit',
// 					0
// 				),
// 				array(
// 					'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_pass_on_css.enable',
// 					1
// 				),
// 				array(
// 					'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_pass_on_css.disable',
// 					2
// 				),
// 			),
// 		),
// 	),
	'tx_sventbodyclass_stop_css_inheritance' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_stop_css_inheritance',
		'config' => array (
			'type' => 'check',
		)
	),
);


$paletteName = 'tx_sventbodyclass_css';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', $paletteName, 'tx_sventbodyclass_css_classes, tx_sventbodyclass_css_classes_subpages, --linebreak--, '. /*tx_sventbodyclass_pass_on_css, /**/ 'tx_sventbodyclass_stop_css_inheritance');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','--palette--;LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_css_palette;' . $paletteName, '', 'before:layout'); 
$GLOBALS['TCA']['pages']['palettes'][$paletteName]['canNotCollapse'] = 1;
