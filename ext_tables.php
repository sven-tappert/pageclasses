<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_sventbodyclass_css_classes' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_css_classes',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'max' => '150',	
			'eval' => 'trim',
		)
	),
	'tx_sventbodyclass_pass_on_css' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_pass_on_css',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_sventbodyclass_stop_css_inheritance' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:svent_bodyclass/locallang_db.xml:pages.tx_sventbodyclass_stop_css_inheritance',		
		'config' => array (
			'type' => 'check',
		)
	),
);



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','tx_sventbodyclass_css_classes;;;;1-1-1, tx_sventbodyclass_pass_on_css, tx_sventbodyclass_stop_css_inheritance', '', 'before:layout');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/', 'Sven-T Body Class Manipulation');
