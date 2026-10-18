<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

/**
 * Body tag classes with the default configuration of the extension:
 * frontend and backend layout classes on, plus defaultCssClass "default".
 *
 * Page tree (Fixtures/pages.csv):
 *   1 Root               own "root", subpages "from-root", backend layout "pagets__home",
 *                        backend layout subpages "pagets__content"
 *   2   Level 1          own "level1 second-class", subpages "from-level1", frontend layout 2
 *   3     Level 2        nothing set
 *   4     Stop           own "stop", subpages "from-stop", stop inheritance
 *   5       Below stop   own "below"
 *   6   Own backend layout  backend layout "pagets__special"
 */
class BodyTagTest extends AbstractFrontendTestCase
{
    public function testRootPageGetsOwnClassesAndBackendLayout(): void
    {
        self::assertSame(
            ['backend_layout_pagets__home', 'default', 'layout_0', 'root'],
            $this->renderBodyClasses(1)
        );
    }

    public function testSubpageGetsSubpageClassesOfParentAndBackendLayoutOfNextLevel(): void
    {
        self::assertSame(
            ['backend_layout_pagets__content', 'default', 'from-root', 'layout_2', 'level1', 'second-class'],
            $this->renderBodyClasses(2)
        );
    }

    public function testSubpageClassesAreInheritedFromAllParents(): void
    {
        self::assertSame(
            ['backend_layout_pagets__content', 'default', 'from-level1', 'from-root', 'layout_0'],
            $this->renderBodyClasses(3)
        );
    }

    public function testStopInheritanceSkipsSubpageClassesOfParents(): void
    {
        self::assertSame(
            ['backend_layout_pagets__content', 'default', 'layout_0', 'stop'],
            $this->renderBodyClasses(4)
        );
    }

    public function testPagesBelowStopGetOnlySubpageClassesUpToTheStopPage(): void
    {
        self::assertSame(
            ['backend_layout_pagets__content', 'below', 'default', 'from-stop', 'layout_0'],
            $this->renderBodyClasses(5)
        );
    }

    public function testOwnBackendLayoutWinsOverBackendLayoutOfParent(): void
    {
        self::assertSame(
            ['backend_layout_pagets__special', 'default', 'from-root', 'layout_0'],
            $this->renderBodyClasses(6)
        );
    }
}
