# Changelog

All notable changes to this project will be documented in this file. See [commit-and-tag-version](https://github.com/absolute-version/commit-and-tag-version) for commit guidelines.

## [1.4.0](https://github.com/Herm71/rcid-core-functionality/compare/v1.3.1...v1.4.0) (2026-08-01)

### Features

* :lock: Restructure the CSP and trial a hardened policy report-only ([#21](https://github.com/Herm71/rcid-core-functionality/issues/21)) ([2cb6a46](https://github.com/Herm71/rcid-core-functionality/commit/2cb6a469c3870e3221765698c6b1b26fe6e23150)), closes [#14](https://github.com/Herm71/rcid-core-functionality/issues/14)
* :wastebasket: Add uninstall.php and make the uninstall policy explicit ([#22](https://github.com/Herm71/rcid-core-functionality/issues/22)) ([254e30b](https://github.com/Herm71/rcid-core-functionality/commit/254e30be6955800e79ff7b43eda241c2a7fe7465)), closes [#10](https://github.com/Herm71/rcid-core-functionality/issues/10), references [#17](https://github.com/Herm71/rcid-core-functionality/issues/17)

### Bug Fixes

* :bug: Flush rewrite rules on activation so CPT archives resolve ([#23](https://github.com/Herm71/rcid-core-functionality/issues/23)) ([c023cc3](https://github.com/Herm71/rcid-core-functionality/commit/c023cc301834a13a7f91b42c2f495fdc83e287b0)), closes [#9](https://github.com/Herm71/rcid-core-functionality/issues/9)
## [1.3.1](https://github.com/Herm71/rcid-core-functionality/compare/v1.3.0...v1.3.1) (2026-08-01)

### Bug Fixes

* :art: Make the linting and build tooling actually run ([#20](https://github.com/Herm71/rcid-core-functionality/issues/20)) ([ce1104a](https://github.com/Herm71/rcid-core-functionality/commit/ce1104a9ef9d5fd7dff1fcc5bffd975abf51664a)), closes [#11](https://github.com/Herm71/rcid-core-functionality/issues/11), references [#6](https://github.com/Herm71/rcid-core-functionality/issues/6)
## [1.3.0](https://github.com/Herm71/rcid-core-functionality/compare/v1.2.2...v1.3.0) (2026-08-01)

### ⚠ Upgrade notes

* **Install this version manually.** 1.2.2 shipped without an update checker, so it cannot discover
  this release on its own. From 1.3.0 onward updates surface automatically.
* **New minimum requirements:** WordPress 6.0 and PHP 8.0.
* Licensing moves from GPL-2.0-only to **GPL-2.0-or-later**.

### Features

* :arrows_counterclockwise: Update the plugin from its own GitHub releases via
  [plugin-update-checker](https://github.com/YahnisElsts/plugin-update-checker), registered for the
  dashboard, WP-Cron and WP-CLI ([#17](https://github.com/Herm71/rcid-core-functionality/pull/17))
  ([18ef6ff](https://github.com/Herm71/rcid-core-functionality/commit/18ef6ff))
* :package: Package the plugin inside a root folder, so the zip matches the standard WordPress
  plugin layout ([#17](https://github.com/Herm71/rcid-core-functionality/pull/17))
* :wrench: Replace the unmaintained `standard-version` with `commit-and-tag-version`, and bump the
  `Version:` header in `plugin.php` automatically so it can no longer drift from `package.json`
  ([#17](https://github.com/Herm71/rcid-core-functionality/pull/17))

### Bug Fixes

* :fire: Delete the dead wordpress.org update filter ([#19](https://github.com/Herm71/rcid-core-functionality/issues/19)) ([d44f008](https://github.com/Herm71/rcid-core-functionality/commit/d44f0085702f87e1a376b9195dcc511f5b7480db)), closes [#8](https://github.com/Herm71/rcid-core-functionality/issues/8)
* :lock: Guard direct file access and repair shortcodes open tag ([09350be](https://github.com/Herm71/rcid-core-functionality/commit/09350be9eff4cf770f05d57897d0261218af91f5))
* :page_facing_up: Correct plugin metadata headers and settle the license ([#18](https://github.com/Herm71/rcid-core-functionality/issues/18)) ([2a392e3](https://github.com/Herm71/rcid-core-functionality/commit/2a392e38eef52e471da267bba8952396c8f12b01)), closes [#12](https://github.com/Herm71/rcid-core-functionality/issues/12), references [#8](https://github.com/Herm71/rcid-core-functionality/issues/8)
### [1.2.2](https://github.com/Herm71/rcid-core-functionality/compare/v1.2.1...v1.2.2) (2024-01-10)


### Bug Fixes

* :art: Adjust order of google `<script>` tags to be enqueued as high as possible. ([b83b748](https://github.com/Herm71/rcid-core-functionality/commit/b83b74880c77f7247529c490a4a010f2fc14ccfb))

### [1.2.1](https://github.com/Herm71/rcid-core-functionality/compare/v1.2.0...v1.2.1) (2024-01-10)


### Bug Fixes

* :fire: Remove Google Analytics tag script. It is replaced by the Google Tag Manager scripts ([9a1549b](https://github.com/Herm71/rcid-core-functionality/commit/9a1549b67cc796cea3008b13ba7c85bbffedc33a))

## [1.2.0](https://github.com/Herm71/rcid-core-functionality/compare/v1.1.4...v1.2.0) (2024-01-10)


### Features

* :sparkles: Add Google Tag Manager in addition to Google Analytics ([84a86b6](https://github.com/Herm71/rcid-core-functionality/commit/84a86b69a4316ec9b923be086e88299e0dc86326))

### [1.1.4](https://github.com/Herm71/rcid-core-functionality/compare/v1.1.3...v1.1.4) (2024-01-10)


### Bug Fixes

* :memo: Rewrite `README.md` and include badges from Shields.io ([03ead3b](https://github.com/Herm71/rcid-core-functionality/commit/03ead3bb222154a5eff102aa5b36f18efd755d59))

### [1.1.3](https://github.com/Herm71/rcid-core-functionality/compare/v1.1.2...v1.1.3) (2024-01-10)


### Bug Fixes

* :bug: Fix another CSP issue ([9c9664a](https://github.com/Herm71/rcid-core-functionality/commit/9c9664a1108264757b53ff0021655d5663afc414))

### [1.1.2](https://github.com/Herm71/rcid-core-functionality/compare/v1.1.1...v1.1.2) (2024-01-09)


### Bug Fixes

* :bug: releas.yml in wrong place. Fixed. ([9692ac6](https://github.com/Herm71/rcid-core-functionality/commit/9692ac64fecdb5f2d2992e491a6addba92438092))

### [1.1.1](https://github.com/Herm71/rcid-core-functionality/compare/v1.1.0...v1.1.1) (2024-01-09)


### Bug Fixes

* :art: Add proper doc block headers to all php files in lib ([7bb5cdd](https://github.com/Herm71/rcid-core-functionality/commit/7bb5cdd848f1e3d1d99240a1667f8dec3d468de5))
* :art: re-factor plugin init functions. ([3556793](https://github.com/Herm71/rcid-core-functionality/commit/355679325719379f7124e88c1f6ec9af4e1ae549))
* :bug: add blocked resources to CSP in order to unblock. ([6339cf2](https://github.com/Herm71/rcid-core-functionality/commit/6339cf2bd267f3e657deafb03f79c318ff7941de))

## [1.1.0](https://github.com/Herm71/rcid-core-functionality/compare/v1.0.0...v1.1.0) (2024-01-04)


### Features

* :fire: Refactor and clean up. Add Google Tag Manager and CSP. ([1970970](https://github.com/Herm71/rcid-core-functionality/commit/197097002292ec0d99b183867771d82357caffc6))

## 1.0.0 (2024-01-03)
