..  _installation:

============
Installation
============

This version supports TYPO3 14.3 LTS with PHP 8.2 to 8.5. For TYPO3 12.4 and
13.4 LTS use version 13.x of the extension, for TYPO3 11.5 LTS version 12.x.

..  _installation-composer:

Composer
========

..  code-block:: bash

    composer require svent/pageclasses

Then update the database schema, e.g. in :guilabel:`System > Maintenance >
Analyze Database Structure`.

..  _installation-ter:

Without Composer
================

Install the extension ``pageclasses`` from the TYPO3 Extension Repository (TER)
in :guilabel:`System > Extensions`. The database fields are created during the
installation.

..  _installation-typoscript:

Include the TypoScript
======================

Add the static template :guilabel:`Pagewide CSS Classes (pageclasses)` to the
main TypoScript template of your site: :guilabel:`Sites > TypoScript`, edit
the whole TypoScript record and add it in the field
:guilabel:`Include static (from extensions)`.

That's all: the TypoScript of the extension sets ``page.bodyTagCObject`` (see
:confval:`setBodyTagCObject <constants-setbodytagcobject>`), so the classes are
rendered without further configuration.
