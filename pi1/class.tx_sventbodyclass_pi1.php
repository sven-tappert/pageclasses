<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
*  Copyright notice
*
*  (c) 2012 Sven Tappert <tappert@sven-t.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


/**
 * Plugin 'Bodytag' for the 'svent_bodyclass' extension.
 *
 * @author	Sven Tappert <tappert@sven-t.de>
 * @package	TYPO3
 * @subpackage	tx_sventbodyclass
 */
class tx_sventbodyclass_pi1 extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin 
{
	public $prefixId      = 'tx_sventbodyclass_pi1';		// Same as class name
	public $scriptRelPath = 'pi1/class.tx_sventbodyclass_pi1.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'svent_bodyclass';	// The extension key.
	public $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	public function main($content, $conf)	
	{	
		// init
		$pageClasses = array();
		$parentClasses = array();
		
		// get global class from ts config
		if (isset($conf['bodytagCssClass'])) {
			foreach(GeneralUtility::trimExplode(' ', $conf['bodytagCssClass'], true) as $class)
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
				// current pages css classes are set - add them
				if (!empty($page['tx_sventbodyclass_css_classes'])) 
				{
					foreach (GeneralUtility::trimExplode(' ', $page['tx_sventbodyclass_css_classes'], true) as $class)
					{
						$pageClasses[$class] = htmlspecialchars($class);
					}
				}
			}
			// collect classes from parent pages
			else
			{
				// add dedicated subpages classes
				foreach (GeneralUtility::trimExplode(' ', $page['tx_sventbodyclass_css_classes_subpages'], true) as $class)
				{
					$pageClasses[$class] = htmlspecialchars($class);
				}
			}
			
			// inheritance is stopped - exit here
			if ($page['tx_sventbodyclass_stop_css_inheritance'] == 1) { break; }
			
		}

		// call hooks
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['svent_bodyclass']['bodytagCssHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['svent_bodyclass']['bodytagCssHook'] as $_classRef) 
			{
				$_procObj = & GeneralUtility::getUserObj($_classRef);
				$_procObj->processBodytagCssHook($this, $pageClasses);
			}
		}
		
		// glue together all classes
		return empty($pageClasses) ? '<body>' : '<body class="'.trim(implode(' ', $pageClasses)).'">';
	}
}



// if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/svent_bodyclass/pi1/class.tx_sventbodyclass_pi1.php'])	{
// 	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/svent_bodyclass/pi1/class.tx_sventbodyclass_pi1.php']);
// }
