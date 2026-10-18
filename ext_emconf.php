<?php

/*******************************************************************
 * Extension Manager/Repository config file for ext "pageclasses".
 *******************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'Add CSS classes to the body tag',
	'description' => 'Adding one or more CSS classes to the body tag of the rendered pages. The extension provides inheritance, separate handling of subpages and includes classes for frontend- and backend-layouts.',
	'category' => 'misc',
	'author' => 'Sven Tappert',
	'author_email' => 'tappert@sven-t.de',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearCacheOnLoad' => 1,
	'author_company' => '',
	'version' => '12.0.1',
	'constraints' => [
		'depends' => [
			'php' => '7.4.0-8.4.99',
			'typo3' => '11.5.0-12.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
	'clearcacheonload' => true,
];
