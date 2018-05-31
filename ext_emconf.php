<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "pageclasses".
 *
 * Auto generated 27-03-2018 01:06
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Add CSS classes to the body tag',
	'description' => 'Extension for adding one or more CSS classes to the body tag of the rendered pages. The extension provides inheritance, separate handling of subpages and includes classes for frontend- and backend-layouts.',
	'category' => 'misc',
	'author' => 'Sven Tappert',
	'author_email' => 'tappert@sven-t.de',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 1,
	'author_company' => '',
	'version' => '8.1.1',
	'constraints' => 
	array (
		'depends' => array (
			'typo3' => '4.5.0-8.99.999',
		),
		'conflicts' => array (
			'bodyclass' => '',
		),
		'suggests' => array (
		),
	),
);

