<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Form\Element;

use SvenT\Pageclasses\Service\BodyClassResolver;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Utility\BackendUtility;
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
 * Read-only field in the page properties: lists the body tag CSS classes that
 * the page gets from its own fields and from its parent pages.
 *
 * Only the page tree is evaluated. Classes from TypoScript (defaultCssClass,
 * frontend and backend layout) and from hooks are not shown.
 *
 * @author Sven Tappert <tappert@sven-t.de>
 */
class BodyClassInfoElement extends AbstractFormElement
{
    private const LANGUAGE_FILE = 'LLL:EXT:pageclasses/Resources/Private/Language/locallang_db.xlf:';

    /**
     * The page tree fields the classes are calculated from
     */
    private const ROOTLINE_FIELDS = [
        'tx_pageclasses_css_classes',
        'tx_pageclasses_css_classes_subpages',
        'tx_pageclasses_stop_css_inheritance',
    ];

    /**
     * Called by the NodeFactory. TYPO3 v13 has this method in AbstractNode, on v12
     * the NodeFactory only uses it if the element provides it.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function render(): array
    {
        $resultArray = $this->initializeResultArray();
        // Needed on v12 only, where the container renders the label otherwise
        $resultArray['labelHasBeenHandled'] = true;

        // effectivePid is the live record of the default language when a page is edited,
        // and the parent page when a new page is created
        $effectivePid = (int)($this->data['effectivePid'] ?? 0);
        $pageId = ($this->data['command'] ?? '') === 'edit' ? $effectivePid : 0;
        $rootLine = $effectivePid > 0 ? BackendUtility::BEgetRootLine($effectivePid, '', true, self::ROOTLINE_FIELDS) : [];
        $page = [];
        foreach ($rootLine as $rootLinePage) {
            if ($pageId > 0 && (int)($rootLinePage['uid'] ?? 0) === $pageId) {
                $page = $rootLinePage;
            }
        }

        // Without configuration, the resolver returns the classes of the page tree only
        $resolved = GeneralUtility::makeInstance(BodyClassResolver::class)->resolve($pageId, $page, $rootLine, []);

        $html = [];
        $html[] = '<div class="formengine-field-item t3js-formengine-field-item">';
        if ($resolved['classes'] === []) {
            $html[] = '<p>' . $this->translate('noClasses') . '</p>';
        } else {
            $html[] = '<div class="table-fit">';
            $html[] = '<table class="table table-striped table-hover">';
            $html[] = '<thead><tr>';
            $html[] = '<th>' . $this->translate('class') . '</th>';
            $html[] = '<th>' . $this->translate('source') . '</th>';
            $html[] = '</tr></thead>';
            $html[] = '<tbody>';
            foreach ($resolved['classes'] as $class => $origin) {
                $source = $origin['source'] === BodyClassResolver::SOURCE_PARENT && $origin['page'] !== null
                    ? $this->translate('source.parent', $this->pageLabel($origin['page']))
                    : $this->translate('source.page');
                $html[] = '<tr>';
                $html[] = '<td><code>' . htmlspecialchars((string)$class) . '</code></td>';
                $html[] = '<td>' . $source . '</td>';
                $html[] = '</tr>';
            }
            $html[] = '</tbody>';
            $html[] = '</table>';
            $html[] = '</div>';
        }
        if ($resolved['stoppedAt'] !== null) {
            $html[] = '<p>' . ($pageId > 0 && (int)($resolved['stoppedAt']['uid'] ?? 0) === $pageId
                ? $this->translate('stopped.page')
                : $this->translate('stopped.parent', $this->pageLabel($resolved['stoppedAt']))) . '</p>';
        }
        $html[] = '<p class="form-text">' . $this->translate('note') . '</p>';
        $html[] = '</div>';

        $resultArray['html'] = $this->wrapWithFieldsetAndLegend(implode(LF, $html));

        return $resultArray;
    }

    /**
     * @param array<string, mixed> $page
     */
    private function pageLabel(array $page): string
    {
        return '"' . (string)($page['title'] ?? '') . '" [' . (int)($page['uid'] ?? 0) . ']';
    }

    /**
     * Returns the label, with the argument inserted for %s. The result is HTML escaped.
     */
    private function translate(string $key, string $argument = ''): string
    {
        $label = (string)$this->getLanguageService()->sL(self::LANGUAGE_FILE . 'pages.tx_pageclasses_info.' . $key);
        return htmlspecialchars(str_replace('%s', $argument, $label));
    }
}
