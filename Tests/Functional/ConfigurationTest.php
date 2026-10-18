<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

/**
 * TypoScript constants of the extension switched off.
 */
class ConfigurationTest extends AbstractFrontendTestCase
{
    protected function setUp(): void
    {
        $this->constants = implode("\n", [
            'plugin.tx_pageclasses_pi1.defaultCssClass =',
            'plugin.tx_pageclasses_pi1.addFrontendLayoutClass = 0',
            'plugin.tx_pageclasses_pi1.addBackendLayoutClass = 0',
        ]);
        parent::setUp();
    }

    public function testRootPageWithoutDefaultAndLayoutClasses(): void
    {
        self::assertSame(['root'], $this->renderBodyClasses(1));
    }

    public function testSubpageWithoutDefaultAndLayoutClasses(): void
    {
        self::assertSame(['from-root', 'level1', 'second-class'], $this->renderBodyClasses(2));
    }
}
