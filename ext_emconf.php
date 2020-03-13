<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "pageclasses".
 *
 * Auto generated 11-06-2018 04:11
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
  'uploadfolder' => false,
  'createDirs' => '',
  'clearCacheOnLoad' => 1,
  'author_company' => '',
  'version' => '9.0.0',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '9.0.0-9.99.999',
    ),
    'conflicts' => 
    array (
      'bodyclass' => '',
    ),
    'suggests' => 
    array (
    ),
  ),
  'clearcacheonload' => true,
);

