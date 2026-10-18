<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

/**
 * With setBodyTagCObject = 0 the extension does not touch the body tag.
 */
class DisabledBodyTagTest extends AbstractFrontendTestCase
{
    protected function setUp(): void
    {
        $this->constants = implode("\n", [
            'plugin.tx_pageclasses_pi1.defaultCssClass = default',
            'plugin.tx_pageclasses_pi1.setBodyTagCObject = 0',
        ]);
        parent::setUp();
    }

    public function testBodyTagIsNotChanged(): void
    {
        self::assertNull($this->renderBodyClasses(2));
    }
}
