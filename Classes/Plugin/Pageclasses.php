<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Plugin;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Sven Tappert <tappert@sven-t.de>
 *  All rights reserved.
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
 * Builds the body tag for the 'pageclasses' extension.
 *
 * Registered as USER object in TypoScript (plugin.tx_pageclasses_pi1) and
 * normally assigned to page.bodyTagCObject.
 *
 * The inheritance from AbstractPlugin is kept on this branch for classes
 * extending the plugin; it is dropped with version 13 (AbstractPlugin is
 * deprecated in TYPO3 12 and removed in TYPO3 13).
 *
 * @author Sven Tappert <tappert@sven-t.de>
 */
class Pageclasses extends AbstractPlugin
{
    /**
     * Same flags on all PHP versions (the default of htmlspecialchars() changed with PHP 8.1)
     */
    private const HTMLSPECIALCHARS_FLAGS = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401;

    public $prefixId = 'tx_pageclasses_pi1';
    public $scriptRelPath = 'Classes/Plugin/Pageclasses.php';
    public $extKey = 'pageclasses';
    public $pi_checkCHash = true;

    /**
     * The main method of the plugin, returning the complete body tag.
     *
     * @param string $content The PlugIn content
     * @param array<string, mixed> $conf The PlugIn configuration
     * @return string The body tag that is displayed on the website
     */
    public function main(string $content, array $conf): string
    {
        // init
        $pageClasses = [];
        $frontendLayout = null;
        $backendLayout = null;
        $stopCssInheritance = false;
        $addBackendLayoutClass = (bool)($conf['addBackendLayoutClass'] ?? false);

        // get global class from ts config
        foreach (GeneralUtility::trimExplode(' ', (string)($conf['defaultCssClass'] ?? ''), true) as $class) {
            $pageClasses[$class] = $this->escape($class);
        }

        $frontendController = $GLOBALS['TSFE'];

        // step through rootline, to find inherited css classes
        foreach ($frontendController->rootLine as $page) {
            // current page
            if ((int)($page['uid'] ?? 0) === (int)$frontendController->id) {
                // use current page record directly (to include fields, not in rootline pages array)
                $page = $frontendController->page;

                // first of all: add class for frontend layout
                if (($conf['addFrontendLayoutClass'] ?? false) && isset($page['layout'])) {
                    $frontendLayout = 'layout_' . $page['layout'];
                }

                // if set, add backendLayout
                if ($addBackendLayoutClass && !empty($page['backend_layout'])) {
                    $backendLayout = 'backend_layout_' . $page['backend_layout'];
                }

                // current pages css classes are set - add them
                foreach (GeneralUtility::trimExplode(' ', (string)($page['tx_pageclasses_css_classes'] ?? ''), true) as $class) {
                    $pageClasses[$class] = $this->escape($class);
                }
            } else {
                // collect classes from parent pages - add dedicated subpages classes
                if (!$stopCssInheritance) {
                    foreach (GeneralUtility::trimExplode(' ', (string)($page['tx_pageclasses_css_classes_subpages'] ?? ''), true) as $class) {
                        $pageClasses[$class] = $this->escape($class);
                    }
                }

                if ($addBackendLayoutClass && empty($backendLayout) && !empty($page['backend_layout_next_level'])) {
                    $backendLayout = 'backend_layout_' . $page['backend_layout_next_level'];
                }
            }

            // inheritance is stopped - collect nothing but the backend layout from here on
            if ((int)($page['tx_pageclasses_stop_css_inheritance'] ?? 0) === 1) {
                $stopCssInheritance = true;
            }

            // we're done
            if ($stopCssInheritance && (!$addBackendLayoutClass || !empty($backendLayout))) {
                break;
            }
        }

        if (!empty($frontendLayout)) {
            $pageClasses[$frontendLayout] = $this->escape($frontendLayout);
        }

        if (!empty($backendLayout)) {
            $pageClasses[$backendLayout] = $this->escape($backendLayout);
        }

        // call hooks
        $hooks = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook'] ?? [];
        if (is_array($hooks)) {
            foreach ($hooks as $classReference) {
                $hookObject = GeneralUtility::makeInstance($classReference);
                $hookObject->processPageclassesCssHook($this, $pageClasses);
            }
        }

        // glue together all classes
        return $pageClasses === [] ? '<body>' : '<body class="' . trim(implode(' ', $pageClasses)) . '">';
    }

    private function escape(string $class): string
    {
        return htmlspecialchars($class, self::HTMLSPECIALCHARS_FLAGS);
    }
}
