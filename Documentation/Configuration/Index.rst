..  _configuration:

=============
Configuration
=============

..  _configuration-page-properties:

Page properties
===============

The fields are in the page properties, tab :guilabel:`Appearance`, section
:guilabel:`Body Tag CSS`. Separate several classes with spaces.

:guilabel:`BodyTag CSS Classes`
    Classes for this page only.

:guilabel:`BodyTag CSS Classes for Subpages`
    Classes for all pages below this page.

:guilabel:`Don't inherit CSS class from parent`
    The page (and the pages below it) do not get the subpage classes of the
    parent pages.

The fields can be excluded for editors in the backend user group settings
(:guilabel:`Allowed excludefields`).

..  _configuration-constants:

TypoScript constants
====================

All options are set in the Constant Editor (category
:guilabel:`PAGECLASSES`) or as TypoScript constants below
``plugin.tx_pageclasses_pi1``.

..  confval:: defaultCssClass
    :name: constants-defaultcssclass
    :type: string
    :default: (empty)

    CSS classes added to all pages, separated by spaces.

    ..  code-block:: typoscript

        plugin.tx_pageclasses_pi1.defaultCssClass = site-main

..  confval:: addFrontendLayoutClass
    :name: constants-addfrontendlayoutclass
    :type: boolean
    :default: 1

    Add the class ``layout_<n>`` for the field :guilabel:`Frontend Layout` of
    the current page.

..  confval:: addBackendLayoutClass
    :name: constants-addbackendlayoutclass
    :type: boolean
    :default: 1

    Add the class ``backend_layout_<identifier>`` for the backend layout of the
    current page. If the page has none, the :guilabel:`Backend Layout
    (subpages of this page)` of the nearest parent page is used.

..  confval:: setBodyTagCObject
    :name: constants-setbodytagcobject
    :type: boolean
    :default: 1

    Assign the plugin to ``page.bodyTagCObject`` automatically. Disable it if
    you want to assign the plugin yourself, see
    :ref:`configuration-manual-setup`.

..  _configuration-manual-setup:

Manual setup
============

With :confval:`setBodyTagCObject <constants-setbodytagcobject>` disabled, or
if another extension or your own TypoScript overrides the body tag, assign the
plugin at the very end of your TypoScript setup (assuming your ``PAGE`` object
is called ``page``):

..  code-block:: typoscript

    page.bodyTagCObject >
    page.bodyTagCObject =< plugin.tx_pageclasses_pi1
