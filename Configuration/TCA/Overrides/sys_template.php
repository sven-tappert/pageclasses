<?php
defined('TYPO3_MODE') or die();


// before TYPO3 6
if (version_compare(TYPO3_version, '6.0.0') < 0) 
{
	t3lib_extMgm::addStaticFile(
		'pageclasses', 'static/', 'Pagewide CSS Classes'
	);
} 
// TYPO3 6 and later
else  
{
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
		'pageclasses', 'static/', 'Pagewide CSS Classes'
	);
}
