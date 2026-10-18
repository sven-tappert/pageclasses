..  _troubleshooting:

===============
Troubleshooting
===============

Set a class in the constant
:confval:`defaultCssClass <constants-defaultcssclass>` first. It is added to
every page, so you can see whether the extension renders the body tag at all.
Reload the frontend and check the source code after every step.

#.  Make sure the extension is installed and the database fields exist
    (:guilabel:`Admin Tools > Maintenance > Analyze Database Structure`).

#.  Make sure the static template :guilabel:`Pagewide CSS Classes (pageclasses)`
    is included in your main TypoScript template (see
    :ref:`installation-typoscript`). Check it in the :guilabel:`Web > Template`
    module: :guilabel:`Template Analyzer` in TYPO3 11,
    :guilabel:`Included TypoScript` in TYPO3 12.

#.  ``page.bodyTagCObject`` may be set by another extension or by your own
    TypoScript. Find out with the :guilabel:`TypoScript Object Browser`
    (TYPO3 11) or :guilabel:`Active TypoScript` (TYPO3 12) and assign the
    plugin at the very end of your setup, see :ref:`configuration-manual-setup`.

#.  Your ``PAGE`` object might not be called ``page``. Check this in the same
    module.

..  TODO: The screenshots show the backend of TYPO3 9/10. Replace them with
    screenshots of TYPO3 12 "Included TypoScript" / "Active TypoScript"
    (sources in Build/Sources/Documentation/Images/).

..  figure:: /Images/template-analyser-v9.png
    :alt: Template Analyzer listing the static template "Pagewide CSS Classes (pageclasses)"
    :class: with-shadow

    Template Analyzer (TYPO3 9-11): the static template of the extension is
    included.

..  figure:: /Images/object-browser.png
    :alt: TypoScript Object Browser showing page.bodyTagCObject set to the pageclasses plugin
    :class: with-shadow

    TypoScript Object Browser (TYPO3 9-11): ``page.bodyTagCObject`` is the
    plugin of the extension.
