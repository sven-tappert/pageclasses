<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi1/class.tx_sventbodyclass_pi1.php', '_pi1', '', 1);
$TYPO3_CONF_VARS['FE']['addRootLineFields'] .= ',tx_sventbodyclass_css_classes,tx_sventbodyclass_pass_on_css,tx_sventbodyclass_stop_css_inheritance';
