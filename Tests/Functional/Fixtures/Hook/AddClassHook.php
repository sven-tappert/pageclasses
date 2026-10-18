<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional\Fixtures\Hook;

/**
 * Example implementation of the hook
 * $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook'].
 */
class AddClassHook
{
    /**
     * @param object $parentObject
     * @param array<string, string> $pageClasses
     */
    public function processPageclassesCssHook($parentObject, array &$pageClasses): void
    {
        unset($pageClasses['default']);
        $pageClasses['hooked'] = 'hooked';
    }
}
