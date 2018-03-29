<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Include configuration files manually in Typo3 versions before 6
if (version_compare(TYPO3_version, '6.0.0') < 0) {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'Pagewide CSS Classes');
	require_once t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Overrides/pages.php';
} 
// Include configuration files manually in Typo3 versions before 6.2.1
// In later versions inclusion is done automagically
else if (version_compare(TYPO3_version, '6.2.1') < 0) {
	require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Overrides/pages.php';
	require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Overrides/sys_template.php';
}
