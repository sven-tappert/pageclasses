..  _introduction:

============
Introduction
============

..  _what-it-does:

What does it do?
================

The extension adds fields for CSS classes to the page properties (tab
:guilabel:`Appearance`). The classes are added to the class attribute of the
body tag, which gives you full flexibility to style single pages and whole
branches of your page tree differently.

*   **CSS classes of the page** are only added to this page.
*   **CSS classes for subpages** are added to all pages below this page, on
    every level. They are inherited until a page stops the inheritance.
*   **Stop inheritance**: a page with this option set does not get the
    subpage classes of its parent pages - and neither do the pages below it,
    unless they define subpage classes on their own.

Additionally, CSS classes for the page layout are added:

*   **Frontend layout** of the current page, e.g. ``layout_1``. This is useful
    if authors should switch the design of a page by selecting a frontend
    layout. The frontend layout is not inherited.
*   **Backend layout** of the current page, or the "backend layout for
    subpages" of the nearest parent page, e.g. ``backend_layout_pagets__2col``.
    This links the frontend design directly to the backend layout.

A rendered body tag looks like this:

..  code-block:: html

    <body class="home news layout_1 backend_layout_pagets__1col">

..  _screenshots:

Screenshots
===========

..  TODO: The screenshots show the backend of TYPO3 9/10. Replace them with
    screenshots of TYPO3 12 (sources in Build/Sources/Documentation/Images/).

..  figure:: /Images/screenshot1.png
    :alt: Tab "Appearance" of the page properties with the fields "BodyTag CSS Classes", "BodyTag CSS Classes for Subpages" and "Don't inherit CSS class from parent"
    :class: with-shadow

    Page properties, tab :guilabel:`Appearance`: fields of the extension and
    the layout fields that result in CSS classes.

..  figure:: /Images/screenshot2.png
    :alt: Constant Editor with the category "PAGECLASSES" and its four options
    :class: with-shadow

    Constant Editor: options of the extension (see :ref:`configuration`).
