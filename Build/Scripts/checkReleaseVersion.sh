#!/usr/bin/env bash
#
# Check that a release tag matches the extension: ext_emconf.php carries the same
# version, composer.json has no "version" field (Packagist takes it from the tag)
# and the ChangeLog has an entry for it.
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
if grep -q '^[[:space:]]*"version"[[:space:]]*:' composer.json; then
  echo "composer.json must not contain a \"version\" field" >&2; fail=1
fi
if ! grep -qx "$VERSION" ChangeLog; then
  echo "ChangeLog has no entry for $VERSION" >&2; fail=1
fi

[ $fail = 0 ] && echo "Release $VERSION: ext_emconf.php, composer.json and ChangeLog are consistent"
exit $fail
