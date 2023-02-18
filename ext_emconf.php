<?php

/*******************************************************************
 * Extension Manager/Repository config file for ext "pageclasses".
 *******************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'Add CSS classes to the body tag',
	'description' => 'Adding one or more CSS classes to the body tag of the rendered pages. The extension provides inheritance, separate handling of subpages and includes classes for frontend- and backend-layouts. Thanks to Florian Elischer for encouraging and supporting Update to Typo3 9/10 LTS.',
	'category' => 'misc',
	'author' => 'Sven Tappert',
	'author_email' => 'tappert@sven-t.de',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearCacheOnLoad' => 1,
	'author_company' => '',
	'version' => '11.0.4',
	'constraints' => [
		'depends' => [
			'typo3' => '9.0.0-11.99.999',
		],
		'conflicts' => [],
		'suggests' => [],
	],
	'clearcacheonload' => true,
];
