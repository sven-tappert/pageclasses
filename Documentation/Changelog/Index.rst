..  _changelog:

=========
Changelog
=========

..  _changelog-13-0-0:

13.0.0
======

*   [RELEASE] Compatibility with TYPO3 13.4 LTS, while TYPO3 12.4 LTS keeps
    working. PHP 8.1 - 8.5.
*   [!!!] Dropped the inheritance from "Frontend\Plugin\AbstractPlugin",
    which has been removed in TYPO3 13. Extensions overriding the plugin
    class must no longer rely on the inherited "pi_*" API.
*   [TASK] Read page id, page record and rootline from the request
    attribute "frontend.page.information" instead of $GLOBALS['TSFE'],
    which is deprecated in TYPO3 13. Falls back to the frontend controller
    on TYPO3 12.
*   [TASK] Accept the request as third argument of "main()", as passed by
    ContentObjectRenderer::callUserFunction().
*   [TASK] Skip "addRootLineFields" on TYPO3 13, where the option has been
    removed and all pages columns are part of the rootline anyway.
*   [TASK] Added strict types and type declarations, hardened array access
    and dropped the reference assignment in the hook invocation.
*   Includes all changes of 12.0.1.

..  _changelog-12-0-1:

12.0.1
======

*   [BUGFIX] Restore compatibility with PHP 7.4 (12.0.0 failed with a parse
    error in ext_localconf.php on TYPO3 11 with PHP 7.4).
*   [BUGFIX] No PHP notices and warnings on PHP 8.x, same HTML escaping of
    the classes on all PHP versions.
*   [BUGFIX] addBackendLayoutClass = 0 also disables the backend layout class
    inherited from parent pages.
*   [TASK] Installable with Composer: package name svent/pageclasses,
    available on Packagist.
*   [TASK] Supported: TYPO3 11.5 LTS and 12.4 LTS with PHP 7.4 - 8.4, checked
    with functional tests for every combination.
*   [DOCS] Documentation in the format of docs.typo3.org.

..  _changelog-12-0-0:

12.0.0
======

*   [RELEASE]: Compatibility with TYPO3 12.

..  _changelog-11-0-4:

11.0.4
======

*   [FIX] Fixed "addRootLineFields" inclusion in ext_localconf.php.

..  _changelog-11-0-3:

11.0.3
======

*   [FIX] Fixed condition for "pageclassesCssHook".

..  _changelog-11-0-2:

11.0.2
======

*   [MAINTENANCE]: Added TYPO3 extension key to composer.json.
*   [FIX] TYPO3 9 compatibility.

..  _changelog-11-0-1:

11.0.1
======

*   [FIX]: Restore compatibility for TYPO3 9.

..  _changelog-11-0-0:

11.0.0
======

*   [RELEASE]: Update for TYPO3 11.

..  _changelog-10-0-1:

10.0.1
======

*   Maintenance release, updating extension description.

..  _changelog-10-0-0:

10.0.0
======

*   [RELEASE]: Update for TYPO3 10.
*   Thanks to Florian Elischer for encouraging and supporting updates to versions 9 and 10.

..  _changelog-9-0-0:

9.0.0
=====

*   [RELEASE]: Update for TYPO3 9. Dropped compatibility for older releases of TYPO3 (use version 8.x for TYPO3 releases 4.5-8.x).

..  _changelog-8-1-3:

8.1.3
=====

*   [Debug]: Class loading.

..  _changelog-8-1-2:

8.1.2
=====

*   [Debug]: Add composer.json to get correct autoloading and prevent v4 classes to be added to classmap.

..  _changelog-8-1-1:

8.1.1
=====

*   Documentation update only.

..  _changelog-8-1-0:

8.1.0
=====

*   Automatic TS Setup for easier integration.
*   Documentation.

..  _changelog-8-0-1:

8.0.1
=====

*   Compatibility update for TYPO3 8 LTS.
*   Bugfix for 4.5 Release

..  _changelog-8-0-0:

8.0.0
=====

*   Compatibility update for TYPO3 8 LTS.

..  _changelog-7-0-2:

7.0.2
=====

Not released to TER.

*   Updated extension title and description.

..  _changelog-7-0-1:

7.0.1
=====

*   Debug: restored support for TYPO3 4.5.
*   Added a basic description on usage in README file.

..  _changelog-7-0-0:

7.0.0
=====

*   Initial upload.
