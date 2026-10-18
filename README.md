# Pagewide CSS classes (pageclasses)

TYPO3 extension that adds one or more CSS classes to the body tag of the
rendered pages. Classes can be set per page and for all subpages of a page,
they are inherited down the page tree, and classes for the frontend and backend
layout of a page are added automatically:

```html
<body class="home news layout_1 backend_layout_pagets__1col">
```

## Installation

```bash
composer require svent/pageclasses
```

or install the extension `pageclasses` from the
[TYPO3 Extension Repository](https://extensions.typo3.org/extension/pageclasses),
then include the static template "Pagewide CSS Classes (pageclasses)".

| Version | TYPO3      | PHP       |
|---------|------------|-----------|
| 13.x    | 12.4, 13.4 | 8.1 - 8.5 |
| 12.x    | 11.5, 12.4 | 7.4 - 8.4 |

## Documentation

[docs.typo3.org/p/svent/pageclasses/main/en-us/](https://docs.typo3.org/p/svent/pageclasses/main/en-us/)

## Development

All checks run in docker containers, see `Build/Scripts/runTests.sh -h`.
`Build/Scripts/testMatrix.sh` runs them for every supported TYPO3/PHP
combination. The Fireworks sources of the documentation graphics are in
`Build/Sources/Documentation/Images/`.
