<?php

declare(strict_types=1);

// PHP-CS-Fixer rules of the TYPO3 Core (typo3/coding-standards).
// Run with "composer cgl" (fix) or "composer cgl:check" (dry run), see
// Build/Scripts/runTests.sh.
$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()
    ->in(__DIR__)
    ->exclude(['.Build', 'Documentation-GENERATED-temp'])
;
$config->setCacheFile(__DIR__ . '/.Build/.cache/php-cs-fixer.cache');

return $config;
