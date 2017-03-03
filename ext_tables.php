<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


if (version_compare(TYPO3_version, '6.0.0') < 0) {
	t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'Pagewide CSS Classes');
	require_once t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Overrides/Pages.php';
} else {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/', 'Pagewide CSS Classes');
}
