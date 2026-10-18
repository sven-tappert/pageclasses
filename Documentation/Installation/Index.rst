..  _installation:

============
Installation
============

This version supports TYPO3 11.5 LTS and 12.4 LTS with PHP 7.4 to 8.4 (as
supported by the respective TYPO3 version).

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
main TypoScript template of your site: :guilabel:`Web > Template`, edit the
whole template record, tab :guilabel:`Includes`, field
:guilabel:`Include static (from extensions)`.

That's all: the TypoScript of the extension sets ``page.bodyTagCObject`` (see
:confval:`setBodyTagCObject <constants-setbodytagcobject>`), so the classes are
rendered without further configuration.
