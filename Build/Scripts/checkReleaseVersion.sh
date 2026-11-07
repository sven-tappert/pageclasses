#!/usr/bin/env bash
#
# Check that a release tag matches the extension: ext_emconf.php,
# Documentation/guides.xml and composer.json "extra.typo3/cms.version" carry the
# same version, composer.json has no top-level "version" field (Packagist takes
# it from the tag) and the changelog has an entry for it.
#
# Usage: Build/Scripts/checkReleaseVersion.sh <version>
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/../.."
VERSION=${1:?usage: $0 <version>}
fail=0

[[ $VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]] || { echo "Not a version number: $VERSION" >&2; exit 1; }

emconf=$(sed -n "s/^[[:space:]]*'version'[[:space:]]*=>[[:space:]]*'\([^']*\)'.*/\1/p" ext_emconf.php)
if [ "$emconf" != "$VERSION" ]; then
  echo "ext_emconf.php has version '$emconf', expected '$VERSION'" >&2; fail=1
fi
if grep -q '^[[:space:]]\{0,4\}"version"[[:space:]]*:' composer.json; then
  echo "composer.json must not contain a top-level \"version\" field" >&2; fail=1
fi
extra=$(sed -n 's/^[[:space:]]\{12\}"version"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/p' composer.json)
if [ "$extra" != "$VERSION" ]; then
  echo "composer.json extra.typo3/cms.version is '$extra', expected '$VERSION'" >&2; fail=1
fi
release=$(sed -n 's/.*[[:space:]]release="\([^"]*\)".*/\1/p' Documentation/guides.xml)
if [ "$release" != "$VERSION" ]; then
  echo "Documentation/guides.xml has release '$release', expected '$VERSION'" >&2; fail=1
fi
if ! grep -qx "$VERSION" Documentation/Changelog/Index.rst; then
  echo "Documentation/Changelog/Index.rst has no entry for $VERSION" >&2; fail=1
fi

[ $fail = 0 ] && echo "Release $VERSION: ext_emconf.php, guides.xml, composer.json (extra version) and changelog are consistent"
exit $fail
