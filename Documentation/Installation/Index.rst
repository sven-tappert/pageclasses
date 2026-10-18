..  _installation:

============
Installation
============

This version supports TYPO3 12.4 LTS and 13.4 LTS with PHP 8.1 to 8.5 (as
supported by the respective TYPO3 version). For TYPO3 11.5 LTS use version
12.x of the extension.

..  _installation-composer:

Composer
========

..  code-block:: bash

    composer require svent/pageclasses

Then update the database schema, e.g. in :guilabel:`Admin Tools > Maintenance >
Analyze Database Structure`.

..  _installation-ter:

Without Composer
================

Install the extension ``pageclasses`` from the TYPO3 Extension Repository (TER)
in :guilabel:`Admin Tools > Extensions`. The database fields are created during
the installation.

..  _installation-typoscript:

Include the TypoScript
======================

Add the static template :guilabel:`Pagewide CSS Classes (pageclasses)` to the
main TypoScript template of your site: :guilabel:`Web > Template` (TYPO3 12)
or :guilabel:`Site Management > TypoScript` (TYPO3 13), edit the whole
TypoScript record and add it in the field
:guilabel:`Include static (from extensions)`.

That's all: the TypoScript of the extension sets ``page.bodyTagCObject`` (see
:confval:`setBodyTagCObject <constants-setbodytagcobject>`), so the classes are
rendered without further configuration.
