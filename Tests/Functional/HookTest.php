<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

use SvenT\Pageclasses\Tests\Functional\Fixtures\Hook\AddClassHook;

/**
 * Hook $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook'].
 */
class HookTest extends AbstractFrontendTestCase
{
    protected function setUp(): void
    {
        $this->additionalConfiguration = [
            'EXTCONF' => [
                'pageclasses' => [
                    'pageclassesCssHook' => [AddClassHook::class],
                ],
            ],
        ];
        parent::setUp();
    }

    public function testHookCanAddAndRemoveClasses(): void
    {
        self::assertSame(
            ['backend_layout_pagets__home', 'hooked', 'layout_0', 'root'],
            $this->renderBodyClasses(1)
        );
    }
}
