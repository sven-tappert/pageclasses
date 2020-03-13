<?php
namespace SvenT\Pageclasses\Plugin;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Sven Tappert <tappert@sven-t.de>
 *  All rights reserved
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *  
 ***************************************************************/

/**
 * Plugin 'Pageclasses' for the 'pageclasses' extension.
 *
 * @author	Sven Tappert <tappert@sven-t.de>
 * @package	TYPO3
 * @subpackage	Plugin
 */
class Pageclasses extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin
{
	public $prefixId      = 'tx_pageclasses_pi1';		// prefix to catch get/post vars
	public $scriptRelPath = 'Classes/Plugin/Pageclasses.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'pageclasses';	// The extension key.
	public $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	string		The content that is displayed on the website
	 */
	public function main($content, $conf)
	{
		// init
		$pageClasses = array();
		$frontendLayout = null;
		$backendLayout = null;
		$stopCssInheritance = false;
		
		// get global class from ts config
		if (isset($conf['defaultCssClass'])) {
			foreach(GeneralUtility::trimExplode(' ', $conf['defaultCssClass'], true) as $class)
			{
				$pageClasses[$class] = htmlspecialchars($class);
			}
		}
		
		// step through rootline, to find inherited css classes
		foreach ($GLOBALS['TSFE']->rootLine as $pid => $page)
		{
			// current page
			if ($page['uid'] == $GLOBALS['TSFE']->id)
			{
				// use current page record directly (to include fields, not in rootline pages array)
				$page = $GLOBALS['TSFE']->page;
				
				// first of all: add class for frontend layout
				if ($conf['addFrontendLayoutClass'] && isset($page['layout'])) {
					$frontendLayout = 'layout_' . $page['layout'];
				}
				
				// if set, add backendLayout
				if ($conf['addBackendLayoutClass'] && isset($page['backend_layout']) && !empty($page['backend_layout'])) {
					$backendLayout = 'backend_layout_' . $page['backend_layout'];
				}
				
				// current pages css classes are set - add them
				if (!empty($page['tx_pageclasses_css_classes']))
				{
					foreach (GeneralUtility::trimExplode(' ', $page['tx_pageclasses_css_classes'], true) as $class)
					{
						$pageClasses[$class] = htmlspecialchars($class);
					}
				}
			}
			// collect classes from parent pages
			else
			{
				// add dedicated subpages classes
				if (!$stopCssInheritance)
				{
					foreach (GeneralUtility::trimExplode(' ', $page['tx_pageclasses_css_classes_subpages'], true) as $class) {
						$pageClasses[$class] = htmlspecialchars($class);
					}
				}
				
				if (empty($backendLayout) && isset($page['backend_layout_next_level']) && !empty($page['backend_layout_next_level'])) {
					$backendLayout = 'backend_layout_' . $page['backend_layout_next_level'];
				}
			}
			
			// inheritance is stopped - skip (continue only
			if ($page['tx_pageclasses_stop_css_inheritance'] == 1) {
				$stopCssInheritance = true;
			}
			
			// we're done
			if ($stopCssInheritance && !empty($backendLayout)) break;
			
		}
		
		if (!empty($frontendLayout)) {
			$pageClasses[$frontendLayout] = htmlspecialchars($frontendLayout);
		}
		
		if (!empty($backendLayout)) {
			$pageClasses[$backendLayout] = htmlspecialchars($backendLayout);
		}
		
		// call hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook']))
		{
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook'] as $_classRef)
			{
				$_procObj = & GeneralUtility::makeInstance($_classRef);
				$_procObj->processPageclassesCssHook($this, $pageClasses);
			}
		}
		
		// glue together all classes
		return empty($pageClasses) ? '<body>' : '<body class="'.trim(implode(' ', $pageClasses)).'">';
	}
}
