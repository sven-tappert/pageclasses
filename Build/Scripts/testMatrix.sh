#!/usr/bin/env bash
#
# Run all checks for every supported TYPO3/PHP combination and print a summary.
# Usage: Build/Scripts/testMatrix.sh
#
# Supported combinations of this branch (keep in sync with composer.json,
# ext_emconf.php and .github/workflows/tests.yml):
#   TYPO3 11.5 LTS: PHP 7.4 - 8.3
#   TYPO3 12.4 LTS: PHP 8.1 - 8.4
set -uo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/../.."
RUN=Build/Scripts/runTests.sh
MATRIX=(
  "11 7.4" "11 8.0" "11 8.1" "11 8.2" "11 8.3"
  "12 8.1" "12 8.2" "12 8.3" "12 8.4"
)
LINT_PHP=(7.4 8.0 8.1 8.2 8.3 8.4)

results=()
fail=0
check() { # check <label> <runTests args...>
  local label=$1; shift
  printf '\n##### %s\n' "$label"
  if "$RUN" "$@"; then results+=("OK    $label"); else results+=("FAIL  $label"); fail=1; fi
}

for php in "${LINT_PHP[@]}"; do
  check "lint PHP $php" -s lint -p "$php"
done
for cell in "${MATRIX[@]}"; do
  read -r typo3 php <<< "$cell"
  if "$RUN" -s composerUpdate -p "$php" -t "$typo3" > ".Build/composerUpdate-$typo3-$php.log" 2>&1; then
    check "phpstan    TYPO3 $typo3 / PHP $php" -s phpstan -p "$php" -t "$typo3"
    check "functional TYPO3 $typo3 / PHP $php" -s functional -p "$php" -t "$typo3"
  else
    results+=("FAIL  composerUpdate TYPO3 $typo3 / PHP $php (see .Build/composerUpdate-$typo3-$php.log)"); fail=1
  fi
done
# PHPCompatibility checks the whole PHP range at once (Build/phpcs.xml)
check "phpcompat  PHP 7.4-8.4" -s phpcompat -p 8.4 -t 12
check "docs" -s docs

printf '\n##### Summary\n'
printf '%s\n' "${results[@]}"
exit $fail
