<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

use SvenT\Pageclasses\Form\Element\BodyClassInfoElement;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * The read-only field in the page properties lists the classes from the page
 * tree only: no defaultCssClass, no layout classes.
 *
 * Page tree: see BodyTagTest.
 */
class BodyClassInfoElementTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->testExtensionsToLoad = ['typo3conf/ext/pageclasses'];
        // Same strict error handling as AbstractFrontendTestCase
        $this->configurationToUseInTestInstance = [
            'SYS' => [
                'errorHandlerErrors' => E_ALL,
                'exceptionalErrors' => E_ALL & ~E_USER_DEPRECATED,
            ],
        ];
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/Fixtures/pages.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/be_users.csv');
        $backendUser = $this->setUpBackendUser(1);
        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageServiceFactory::class)->createFromUserPreferences($backendUser);
    }

    public function testRootPageShowsOwnClassesOnly(): void
    {
        $html = $this->renderElement('edit', 1);
        self::assertSame(['root' => 'This page'], $this->classRows($html));
        self::assertStringNotContainsString('Inheritance stops', $html);
    }

    public function testSubpageShowsInheritedClassesWithTheirPage(): void
    {
        self::assertSame(
            [
                'from-level1' => 'Inherited from page &quot;Level 1&quot; [2]',
                'from-root' => 'Inherited from page &quot;Root&quot; [1]',
            ],
            $this->classRows($this->renderElement('edit', 3))
        );
    }

    public function testStopInheritanceOnThePageItself(): void
    {
        $html = $this->renderElement('edit', 4);
        self::assertSame(['stop' => 'This page'], $this->classRows($html));
        self::assertStringContainsString('This page does not inherit CSS classes from its parent pages.', $html);
    }

    public function testStopInheritanceOnAParentPage(): void
    {
        $html = $this->renderElement('edit', 5);
        self::assertSame(
            ['below' => 'This page', 'from-stop' => 'Inherited from page &quot;Stop&quot; [4]'],
            $this->classRows($html)
        );
        self::assertStringContainsString('Inheritance stops at page &quot;Stop&quot; [4].', $html);
    }

    public function testNewPageShowsTheClassesOfItsParentPage(): void
    {
        self::assertSame(
            ['from-stop' => 'Inherited from page &quot;Stop&quot; [4]'],
            $this->classRows($this->renderElement('new', 4))
        );
    }

    public function testPageWithoutClasses(): void
    {
        $this->getConnectionPool()->getConnectionForTable('pages')
            ->update('pages', ['tx_pageclasses_css_classes_subpages' => ''], ['uid' => 1]);
        self::assertStringContainsString('No CSS classes from the page tree.', $this->renderElement('edit', 6));
    }

    /**
     * Renders the element with the FormEngine data it reads.
     *
     * @param int $effectivePid The page itself (edit) or the parent page (new)
     */
    private function renderElement(string $command, int $effectivePid): string
    {
        $element = GeneralUtility::makeInstance(BodyClassInfoElement::class);
        $element->setData([
            'command' => $command,
            'effectivePid' => $effectivePid,
            'fieldName' => 'tx_pageclasses_info',
            'parameterArray' => ['fieldConf' => ['label' => 'Info']],
        ]);
        $result = $element->render();
        self::assertTrue($result['labelHasBeenHandled']);
        return (string)$result['html'];
    }

    /**
     * @return array<string, string> class => source column, in the order of the table
     */
    private function classRows(string $html): array
    {
        preg_match_all('#<td><code>([^<]*)</code></td>\s*<td>([^<]*)</td>#', $html, $matches);
        return array_combine($matches[1], $matches[2]);
    }
}
