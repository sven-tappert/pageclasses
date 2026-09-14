<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Plugin;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageInformation;

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
 * @author Sven Tappert <tappert@sven-t.de>
 */
class Pageclasses
{
    /**
     * The main method of the plugin, returning the complete body tag.
     *
     * Called through ContentObjectRenderer::callUserFunction(), which passes
     * the current request as third argument since TYPO3 v12.
     *
     * @param string $content The PlugIn content
     * @param array<string, mixed> $conf The PlugIn configuration
     * @param ServerRequestInterface|null $request The current frontend request
     * @return string The body tag that is displayed on the website
     */
    public function main(string $content, array $conf, ?ServerRequestInterface $request = null): string
    {
        $request ??= $GLOBALS['TYPO3_REQUEST'] ?? null;
        $pageContext = $request instanceof ServerRequestInterface ? $this->resolvePageContext($request) : null;
        if ($pageContext === null) {
            return '<body>';
        }

        // init
        $pageClasses = [];
        $frontendLayout = null;
        $backendLayout = null;
        $stopCssInheritance = false;

        // get global class from ts config
        foreach (GeneralUtility::trimExplode(' ', (string)($conf['defaultCssClass'] ?? ''), true) as $class) {
            $pageClasses[$class] = htmlspecialchars($class);
        }

        // step through rootline, to find inherited css classes
        foreach ($pageContext['rootLine'] as $page) {
            // current page
            if ((int)($page['uid'] ?? 0) === $pageContext['id']) {
                // use current page record directly (to include fields, not in rootline pages array)
                $page = $pageContext['page'];

                // first of all: add class for frontend layout
                if (($conf['addFrontendLayoutClass'] ?? false) && isset($page['layout'])) {
                    $frontendLayout = 'layout_' . $page['layout'];
                }

                // if set, add backendLayout
                if (($conf['addBackendLayoutClass'] ?? false) && !empty($page['backend_layout'])) {
                    $backendLayout = 'backend_layout_' . $page['backend_layout'];
                }

                // current pages css classes are set - add them
                foreach (GeneralUtility::trimExplode(' ', (string)($page['tx_pageclasses_css_classes'] ?? ''), true) as $class) {
                    $pageClasses[$class] = htmlspecialchars($class);
                }
            } else {
                // collect classes from parent pages - add dedicated subpages classes
                if (!$stopCssInheritance) {
                    foreach (GeneralUtility::trimExplode(' ', (string)($page['tx_pageclasses_css_classes_subpages'] ?? ''), true) as $class) {
                        $pageClasses[$class] = htmlspecialchars($class);
                    }
                }

                if (empty($backendLayout) && !empty($page['backend_layout_next_level'])) {
                    $backendLayout = 'backend_layout_' . $page['backend_layout_next_level'];
                }
            }

            // inheritance is stopped - collect nothing but the backend layout from here on
            if ((int)($page['tx_pageclasses_stop_css_inheritance'] ?? 0) === 1) {
                $stopCssInheritance = true;
            }

            // we're done
            if ($stopCssInheritance && !empty($backendLayout)) {
                break;
            }
        }

        if (!empty($frontendLayout)) {
            $pageClasses[$frontendLayout] = htmlspecialchars($frontendLayout);
        }

        if (!empty($backendLayout)) {
            $pageClasses[$backendLayout] = htmlspecialchars($backendLayout);
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

    /**
     * Reads the current page id, the current page record and the rootline
     * from the request.
     *
     * TYPO3 v13 exposes them as the 'frontend.page.information' attribute. On
     * v12 that attribute does not exist yet, so the values are taken from the
     * frontend controller, which holds them as public properties there.
     *
     * @return array{id: int, page: array<string, mixed>, rootLine: array<int, array<string, mixed>>}|null
     */
    private function resolvePageContext(ServerRequestInterface $request): ?array
    {
        // TYPO3 v13 and above
        $pageInformation = $request->getAttribute('frontend.page.information');
        if ($pageInformation instanceof PageInformation) {
            return [
                'id' => $pageInformation->getId(),
                'page' => $pageInformation->getPageRecord(),
                'rootLine' => $pageInformation->getRootLine(),
            ];
        }

        // TYPO3 v12 fallback - drop together with v12 support
        $controller = $request->getAttribute('frontend.controller');
        if ($controller instanceof TypoScriptFrontendController) {
            return [
                'id' => $controller->id,
                'page' => (array)($controller->page ?? []),
                'rootLine' => $controller->rootLine,
            ];
        }

        return null;
    }
}
