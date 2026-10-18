..  _developer:

=========
Developer
=========

..  _developer-hook:

Hook: change the classes
========================

Before the body tag is rendered, the hook ``pageclassesCssHook`` gets all
collected classes and may add, change or remove them. The array is passed by
reference, keys and values are the (HTML-escaped) class names.

Register the hook in the :file:`ext_localconf.php` of your extension:

..  code-block:: php
    :caption: EXT:my_extension/ext_localconf.php

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pageclasses']['pageclassesCssHook'][]
        = \MyVendor\MyExtension\Hook\PageclassesHook::class;

..  code-block:: php
    :caption: EXT:my_extension/Classes/Hook/PageclassesHook.php

    namespace MyVendor\MyExtension\Hook;

    class PageclassesHook
    {
        /**
         * @param object $parentObject the plugin \SvenT\Pageclasses\Plugin\Pageclasses
         * @param array<string, string> $pageClasses
         */
        public function processPageclassesCssHook($parentObject, array &$pageClasses): void
        {
            $pageClasses['is-christmas'] = 'is-christmas';
        }
    }

..  _developer-typoscript:

TypoScript
==========

The body tag is rendered by the ``USER`` object ``plugin.tx_pageclasses_pi1``
(class :php:`SvenT\Pageclasses\Plugin\Pageclasses`, method ``main``). It returns
the complete body tag, e.g. ``<body class="home layout_0">``, or ``<body>`` if
there is no class.
