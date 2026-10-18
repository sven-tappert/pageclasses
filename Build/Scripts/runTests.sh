#!/usr/bin/env bash
#
# Run the QA checks of EXT:pageclasses in the TYPO3 core testing containers
# (ghcr.io/typo3/core-testing-php<XY>). Only docker is needed on the host.
# The same script is used locally and in the GitHub workflows.
#
# Usage: Build/Scripts/runTests.sh -s <suite> [-p <php>] [-t <typo3>] [-- <extra arguments>]
#
#   -s composerUpdate  install the dependencies for the given PHP/TYPO3 combination
#                      (composer update --with typo3/cms-*:<constraint>, composer.json stays untouched)
#      lint            php -l on all PHP files
#      phpcompat       PHPCompatibility for the whole supported PHP range (Build/phpcs.xml)
#      phpstan         PHPStan (Build/phpstan.neon) against the installed TYPO3 version
#      functional      functional tests (Build/phpunit/FunctionalTests*.xml), SQLite
#      docs            render Documentation/ with the official TYPO3 render-guides container
#   -p <php>           PHP version: 7.4, 8.0, 8.1, 8.2, 8.3, 8.4
#   -t <typo3>         TYPO3 major version: 11, 12
#   -- <arguments>     passed on to phpunit/phpstan/phpcs, e.g. -- --display-deprecations
#
# Without -p/-t the combination of the last composerUpdate is used (.Build/.installed),
# as long as composer.json did not change since (e.g. by switching branches);
# otherwise, and for composerUpdate itself, PHP 8.4 / TYPO3 12.
# phpstan, functional and phpcompat run composerUpdate first if the dependencies
# are not installed for the requested combination (log: .Build/composerUpdate.log).
set -euo pipefail

ROOT=$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)
SUITE=
PHP_VERSION=
TYPO3_VERSION=

usage() { awk 'NR > 2 && !/^#/ { exit } NR > 2 { sub(/^# ?/, ""); print }' "${BASH_SOURCE[0]}"; }

while getopts "s:p:t:h" opt; do
  case $opt in
    s) SUITE=$OPTARG ;;
    p) PHP_VERSION=$OPTARG ;;
    t) TYPO3_VERSION=$OPTARG ;;
    h) usage; exit 0 ;;
    *) usage; exit 1 ;;
  esac
done
shift $((OPTIND - 1))
EXTRA=("$@")
[ -n "$SUITE" ] || { usage; exit 1; }

# the combination installed in .Build/, valid only while composer.json is unchanged
installed=
if [ -s "$ROOT/.Build/.installed" ] \
  && [ "$(cat "$ROOT/.Build/.installed-composer" 2>/dev/null)" = "$(cksum < "$ROOT/composer.json")" ]; then
  installed=$(cat "$ROOT/.Build/.installed")
fi
if [ "$SUITE" != composerUpdate ] && [ -n "$installed" ]; then
  read -r installed_php installed_typo3 <<< "$installed"
  PHP_VERSION=${PHP_VERSION:-$installed_php}
  TYPO3_VERSION=${TYPO3_VERSION:-$installed_typo3}
fi
PHP_VERSION=${PHP_VERSION:-8.4}
TYPO3_VERSION=${TYPO3_VERSION:-12}

case $PHP_VERSION in
  7.4|8.0|8.1|8.2|8.3|8.4) ;;
  *) echo "Unsupported PHP version: $PHP_VERSION" >&2; exit 1 ;;
esac
case $TYPO3_VERSION in
  # PHPUnit is fixed per TYPO3 version, so it always matches its config in Build/phpunit/
  11) CORE_CONSTRAINT='^11.5' PHPUNIT_CONSTRAINT='^9.6' ;;
  12) CORE_CONSTRAINT='^12.4' PHPUNIT_CONSTRAINT='^10.5' ;;
  *) echo "Unsupported TYPO3 version: $TYPO3_VERSION" >&2; exit 1 ;;
esac

IMAGE="ghcr.io/typo3/core-testing-php${PHP_VERSION/./}:latest"
mkdir -p "$ROOT/.Build/.cache"

# run <command...>: run a command in the PHP container, project mounted at the same path
run() {
  local tty=()
  [ -t 1 ] && tty=(-t)
  docker run --rm "${tty[@]}" \
    -u "$(id -u):$(id -g)" \
    -v "$ROOT:$ROOT" -w "$ROOT" \
    -e HOME="$ROOT/.Build/.cache" \
    -e COMPOSER_CACHE_DIR="$ROOT/.Build/.cache/composer" \
    -e TYPO3_PATH_ROOT="$ROOT/.Build/public" \
    -e TYPO3_PATH_APP="$ROOT/.Build" \
    -e typo3DatabaseDriver=pdo_sqlite \
    "$IMAGE" "$@"
}

case $SUITE in
  composerUpdate)
    rm -rf "$ROOT/.Build/vendor" "$ROOT/.Build/public" "$ROOT/.Build/bin" "$ROOT/composer.lock"
    # The public releases of TYPO3 11.5 and 12.4 (out of free maintenance) carry
    # security advisories, which Composer >= 2.9 refuses to install by default.
    # They are installed here only as test targets.
    run env COMPOSER_NO_BLOCKING=1 COMPOSER_NO_SECURITY_BLOCKING=1 COMPOSER_NO_AUDIT=1 \
      composer update --no-progress --no-interaction \
      --with "typo3/cms-core:$CORE_CONSTRAINT" --with "typo3/cms-frontend:$CORE_CONSTRAINT" \
      --with "phpunit/phpunit:$PHPUNIT_CONSTRAINT"
    # remember the combination and the composer.json it was installed for
    echo "$PHP_VERSION $TYPO3_VERSION" > "$ROOT/.Build/.installed"
    cksum < "$ROOT/composer.json" > "$ROOT/.Build/.installed-composer"
    # activate the pre-commit hook in .githooks/ (on the host, git is not needed in the container)
    if [ -e "$ROOT/.git" ] && command -v git > /dev/null; then
      git -C "$ROOT" config core.hooksPath .githooks
    fi
    ;;
  lint)
    run bash -c 'find . -name "*.php" -not -path "./.Build/*" -print0 \
      | xargs -0 -n1 -P4 php -d display_errors=1 -l >/dev/null \
      && echo "lint ok (PHP $(php -r "echo PHP_VERSION;"))"'
    ;;
  phpcompat|phpstan|functional)
    if [ "$installed" != "$PHP_VERSION $TYPO3_VERSION" ]; then
      echo "Installing the dependencies for TYPO3 $TYPO3_VERSION / PHP $PHP_VERSION first (log: .Build/composerUpdate.log) ..."
      "${BASH_SOURCE[0]}" -s composerUpdate -p "$PHP_VERSION" -t "$TYPO3_VERSION" > "$ROOT/.Build/composerUpdate.log" 2>&1 \
        || { echo "composerUpdate failed, see .Build/composerUpdate.log" >&2; exit 1; }
    fi
    echo "$SUITE: TYPO3 $TYPO3_VERSION / PHP $PHP_VERSION"
    if [ "$SUITE" = phpcompat ]; then
      run .Build/bin/phpcs --standard=Build/phpcs.xml "${EXTRA[@]}"
    elif [ "$SUITE" = phpstan ]; then
      run .Build/bin/phpstan analyse --no-progress --memory-limit=1G -c Build/phpstan.neon "${EXTRA[@]}"
    else
      # TYPO3 11 is tested with PHPUnit 9, TYPO3 12 with PHPUnit 10
      config=Build/phpunit/FunctionalTests.xml
      [ "$TYPO3_VERSION" = 11 ] && config=Build/phpunit/FunctionalTestsPhpUnit9.xml
      run .Build/bin/phpunit -c "$config" "${EXTRA[@]}"
    fi
    ;;
  docs)
    rm -rf "$ROOT/Documentation-GENERATED-temp"
    docker run --rm -u "$(id -u):$(id -g)" -v "$ROOT:/project" \
      ghcr.io/typo3-documentation/render-guides:latest \
      --config=Documentation --no-progress --fail-on-log
    ;;
  *)
    echo "Unknown suite: $SUITE" >&2; usage; exit 1 ;;
esac
