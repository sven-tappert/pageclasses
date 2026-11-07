<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2026 Sven Tappert <tappert@sven-t.de>
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
 * Collects the body tag CSS classes of a page from the configuration and the
 * rootline. Used by the frontend plugin and by the info field in the page
 * properties, so both show the same classes.
 *
 * @author Sven Tappert <tappert@sven-t.de>
 */
class BodyClassResolver
{
    /** Class from the TypoScript setting defaultCssClass */
    public const SOURCE_DEFAULT = 'default';

    /** Class from the field tx_pageclasses_css_classes of the page itself */
    public const SOURCE_PAGE = 'page';

    /** Class from the field tx_pageclasses_css_classes_subpages of a parent page */
    public const SOURCE_PARENT = 'parent';

    /** Class for the frontend layout of the page */
    public const SOURCE_FRONTEND_LAYOUT = 'frontendLayout';

    /** Class for the backend layout of the page or the backend layout for subpages of a parent page */
    public const SOURCE_BACKEND_LAYOUT = 'backendLayout';

    /**
     * Returns the CSS classes in the order of the body tag. The keys are the
     * (unescaped) class names, the values tell where a class comes from. A class
     * that appears more than once keeps its first position and source. Like any
     * PHP array key, a numeric class name becomes an int key.
     *
     * $stoppedAt is the page with "stop inheritance" that ended the collection of
     * classes from parent pages, null if no such page is in the rootline.
     *
     * @param int $pageId Uid of the page. Use 0 for a page that is not in the rootline yet (new page)
     * @param array<string, mixed> $page The page record (fields that are missing in the rootline row)
     * @param array<int, array<string, mixed>> $rootLine Rootline starting with the page itself, then its parents
     * @param array<string, mixed> $conf The plugin configuration (defaultCssClass, addFrontendLayoutClass, addBackendLayoutClass)
     * @return array{classes: array<array-key, array{source: string, page: array<string, mixed>|null}>, stoppedAt: array<string, mixed>|null}
     */
    public function resolve(int $pageId, array $page, array $rootLine, array $conf): array
    {
        $classes = [];
        $frontendLayout = null;
        $backendLayout = null;
        $backendLayoutPage = null;
        $stoppedAt = null;
        $addBackendLayoutClass = (bool)($conf['addBackendLayoutClass'] ?? false);

        // get global class from ts config
        foreach (GeneralUtility::trimExplode(' ', (string)($conf['defaultCssClass'] ?? ''), true) as $class) {
            $classes[$class] ??= ['source' => self::SOURCE_DEFAULT, 'page' => null];
        }

        // step through rootline, to find inherited css classes
        foreach ($rootLine as $rootLinePage) {
            // current page
            if ((int)($rootLinePage['uid'] ?? 0) === $pageId) {
                // use current page record directly (to include fields, not in rootline pages array)
                $rootLinePage = $page;

                // first of all: add class for frontend layout
                if (($conf['addFrontendLayoutClass'] ?? false) && isset($rootLinePage['layout'])) {
                    $frontendLayout = 'layout_' . $rootLinePage['layout'];
                }

                // if set, add backendLayout
                if ($addBackendLayoutClass && !empty($rootLinePage['backend_layout'])) {
                    $backendLayout = 'backend_layout_' . $rootLinePage['backend_layout'];
                    $backendLayoutPage = $rootLinePage;
                }

                // current pages css classes are set - add them
                foreach (GeneralUtility::trimExplode(' ', (string)($rootLinePage['tx_pageclasses_css_classes'] ?? ''), true) as $class) {
                    $classes[$class] ??= ['source' => self::SOURCE_PAGE, 'page' => $rootLinePage];
                }
            } else {
                // collect classes from parent pages - add dedicated subpages classes
                if ($stoppedAt === null) {
                    foreach (GeneralUtility::trimExplode(' ', (string)($rootLinePage['tx_pageclasses_css_classes_subpages'] ?? ''), true) as $class) {
                        $classes[$class] ??= ['source' => self::SOURCE_PARENT, 'page' => $rootLinePage];
                    }
                }

                if ($addBackendLayoutClass && empty($backendLayout) && !empty($rootLinePage['backend_layout_next_level'])) {
                    $backendLayout = 'backend_layout_' . $rootLinePage['backend_layout_next_level'];
                    $backendLayoutPage = $rootLinePage;
                }
            }

            // inheritance is stopped - collect nothing but the backend layout from here on
            if ($stoppedAt === null && (int)($rootLinePage['tx_pageclasses_stop_css_inheritance'] ?? 0) === 1) {
                $stoppedAt = $rootLinePage;
            }

            // we're done
            if ($stoppedAt !== null && (!$addBackendLayoutClass || !empty($backendLayout))) {
                break;
            }
        }

        if (!empty($frontendLayout)) {
            $classes[$frontendLayout] ??= ['source' => self::SOURCE_FRONTEND_LAYOUT, 'page' => $page];
        }

        if (!empty($backendLayout)) {
            $classes[$backendLayout] ??= ['source' => self::SOURCE_BACKEND_LAYOUT, 'page' => $backendLayoutPage];
        }

        return ['classes' => $classes, 'stoppedAt' => $stoppedAt];
    }
}
