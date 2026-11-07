<?php

declare(strict_types=1);

defined('TYPO3') or die();

// Read-only field in the page properties (pages.tx_pageclasses_info)
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1792396800] = [
    'nodeName' => 'pageclassesBodyClassInfo',
    'priority' => 40,
    'class' => \SvenT\Pageclasses\Form\Element\BodyClassInfoElement::class,
];
