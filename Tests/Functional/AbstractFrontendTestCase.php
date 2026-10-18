<?php

declare(strict_types=1);

namespace SvenT\Pageclasses\Tests\Functional;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Renders pages of the fixture page tree (Fixtures/pages.csv) in the frontend
 * and returns the CSS classes of the rendered body tag.
 *
 * The properties of FunctionalTestCase are untyped in testing-framework 7
 * (TYPO3 11) and typed in testing-framework 8 (TYPO3 12), so they are set in
 * setUp() instead of being redeclared.
 */
abstract class AbstractFrontendTestCase extends FunctionalTestCase
{
    /**
     * TypoScript constants for all pages, added after the constants of the extension.
     */
    protected string $constants = 'plugin.tx_pageclasses_pi1.defaultCssClass = default';

    /**
     * @var array<string, mixed>
     */
    protected array $additionalConfiguration = [];

    protected function setUp(): void
    {
        $this->testExtensionsToLoad = ['typo3conf/ext/pageclasses'];
        // Turn every PHP error, warning, notice and PHP deprecation into an exception,
        // so the tests fail on any incompatibility with the PHP version in use.
        $this->configurationToUseInTestInstance = array_replace_recursive([
            'SYS' => [
                'errorHandlerErrors' => E_ALL,
                'exceptionalErrors' => E_ALL & ~E_USER_DEPRECATED,
            ],
        ], $this->additionalConfiguration);
        parent::setUp();

        $this->writeSiteConfiguration();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/pages.csv');
        $this->setUpFrontendRootPage(
            1,
            ['setup' => ['EXT:pageclasses/Tests/Functional/Fixtures/TypoScript/setup.typoscript']],
            // the trailing line break matters: testing-framework 7 appends its own constants
            ['constants' => "@import 'EXT:pageclasses/Configuration/TypoScript/constants.typoscript'\n" . $this->constants . "\n"]
        );
    }

    /**
     * @return list<string>|null sorted CSS classes of the body tag, null without class attribute
     */
    protected function renderBodyClasses(int $pageId): ?array
    {
        $response = $this->executeFrontendSubRequest((new InternalRequest())->withPageId($pageId));
        self::assertSame(200, $response->getStatusCode());
        $html = (string)$response->getBody();
        self::assertSame(1, preg_match('/<body([^>]*)>/', $html, $bodyTag), 'No body tag in: ' . $html);
        if (preg_match('/\sclass="([^"]*)"/', $bodyTag[1], $class) !== 1) {
            return null;
        }
        $classes = GeneralUtility::trimExplode(' ', $class[1], true);
        sort($classes);
        return $classes;
    }

    private function writeSiteConfiguration(): void
    {
        $path = Environment::getConfigPath() . '/sites/main';
        GeneralUtility::mkdir_deep($path);
        file_put_contents($path . '/config.yaml', <<<'YAML'
rootPageId: 1
base: /
languages:
  -
    title: English
    enabled: true
    languageId: 0
    base: /
    typo3Language: default
    locale: en_US.UTF-8
    iso-639-1: en
    navigationTitle: English
    hreflang: en-us
    direction: ltr
    flag: us
YAML
        );
    }
}
