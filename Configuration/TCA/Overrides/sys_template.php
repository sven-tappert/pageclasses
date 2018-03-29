<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'user_configuration', 'Configuration/TypoScript', 'UserConfiguration - Lib'
);

