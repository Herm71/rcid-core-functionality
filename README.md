# Ruth Chafin Interior Design WordPress Core Functionality Plugin

![GitHub Release](https://img.shields.io/github/v/release/Herm71/rcid-core-functionality?display_name=release&logo=github&labelColor=%23362422&color=%23B95B09) ![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/Herm71/rcid-core-functionality/release.yml?style=flat&logo=github&labelColor=%23362422&color=%23B95B09) ![GitHub issues](https://img.shields.io/github/issues/Herm71/rcid-core-functionality?logo=github&labelColor=%23362422&color=%23B95B09)

This WordPress plugin contains custom functionality for the [Ruth Chafin Interior Design](https://ruthchafininteriordesign.com) site and its [WordPress Block Theme](https://github.com/Herm71/rcid-block-theme).

The concept is to keep the parts of a site that are _theme independent_ — custom post types, security headers, analytics — separate from the theme's presentation code, so that rewriting or replacing the theme never breaks the site's functionality. The rule of thumb when deciding where something belongs: if it would survive a theme change, it goes here; if it's presentation, it goes in the theme.

## Features

This plugin can be expanded as use-cases arise. It currently features the following:

-   `post-types.php` -- registers the custom post types (see below)

-   `security-headers.php` -- adds security headers such as Content Security Policy (see [Configuration](#configuration))

-   `gtm.php` -- adds the Google Tag Manager container snippet

-   `disable-xmlrpc.php` -- disables `xml-rpc` and removes from `<head>` to prevent brute force attacks on admin usernames and passwords per [WordPress best practices](https://pantheon.io/docs/wordpress-best-practices#avoid-xml-rpc-attacks)

-   `general.php` -- an intentionally empty placeholder, kept as the catch-all for theme-independent odds and ends

-   `uninstall.php` -- runs on plugin deletion (see [Uninstalling](#uninstalling))

`plugin.php` is a loader and nothing else: it `include_once`s each file in `lib/functions/`. Adding a feature means creating a file there and adding an include block; commenting an include out is the intended way to disable one.

## Custom post types

| Slug           | Label        | Archive | Block editor |
| -------------- | ------------ | ------- | ------------ |
| `projects`     | Projects     | yes     | yes          |
| `press`        | Press Items  | yes     | yes          |
| `testimonials` | Testimonials | yes     | yes          |

All three are registered with `show_in_rest => true`, which the block editor requires and which lets the theme query them, and `has_archive => true`.

A fourth post type, `team_member`, is defined in `post-types.php` but deliberately **not** registered — its `add_action( 'init', ... )` is commented out.

New post types must be added to the `rcid_register_post_types()` aggregator rather than hooked to `init` directly. That aggregator is shared by the `init` hook and the activation hook, and if the two paths diverge the new archive will 404 on a fresh activation.

## Configuration

The plugin has no settings screen. The one thing it reads is an optional constant in `wp-config.php`:

```php
// Where browsers should send Content-Security-Policy violation reports.
define( 'RCID_CSP_REPORT_URI', 'https://xxxxx.report-uri.com/r/d/csp/reportOnly' );
```

Two Content-Security-Policy headers are sent: an enforced one, and a stricter candidate sent as `Content-Security-Policy-Report-Only` so it can be evaluated against real traffic before it replaces the enforced one. Without `RCID_CSP_REPORT_URI` the report-only policy's violations appear only in an individual visitor's browser console and are lost. Setting it adds `report-uri` and `report-to` directives and a matching `Reporting-Endpoints` header, so violations from real traffic are collected somewhere you can read them.

If the constant is unset — or set to anything that is not a valid `https` URL — no reporting directives are emitted at all. The URL is kept out of the repository because collector URLs embed an account identifier.

`RCID_CSP_REPORT_TO_URI` is an optional second constant, for collectors that issue a different URL for the modern Reporting API than for the legacy `report-uri` directive. It defaults to the same endpoint.

## Activation and deactivation

Because every post type uses `has_archive => true`, each needs its own rewrite rules. Activation registers the post types and _then_ calls `flush_rewrite_rules()` — that order matters, since a flush only writes rules for whatever is registered at that moment and `init` has not run yet during activation. Without it the archives 404 until Permalinks are re-saved by hand.

Deactivation does the mirror image: it unregisters the post types first, then flushes. The plugin is still loaded at that point, so flushing on its own would simply write the archive rules straight back.

## Updates

This plugin updates itself from **its own GitHub releases**, not from the WordPress.org repository. [Yahnis Elsts' plugin-update-checker](https://github.com/YahnisElsts/plugin-update-checker) is a Composer runtime dependency, which is why `vendor/` has to be present in the packaged zip.

The updater only installs the `rcid-core-functionality.zip` release asset. Left to its default preference it would fall back to GitHub's source tarball, which ships without `vendor/` — installing that would leave the site running a copy of the plugin that can no longer update itself.

The check is registered only for admin requests, WP-Cron and WP-CLI. Front-end requests skip it.

## Uninstalling

**Content outlives the plugin.** `uninstall.php` runs when the plugin is _deleted_ (not on deactivation), and it never touches `projects`, `press` or `testimonials` posts, terms or meta. Those post types exist precisely so the site's content survives a theme rewrite, so deleting the plugin must not undo that.

Deleting the plugin only stops the post types being registered. The content becomes invisible in the admin until something registers them again — it is hidden, not destroyed.

What `uninstall.php` does remove is the plugin's own bookkeeping: plugin-update-checker's cached update state, its manual-check error transient, and its scheduled cron event. The plugin stores no options of its own.

## Development

Dependencies are not committed, so install first:

```bash
npm install
composer install
```

| Task              | Command                                 |
| ----------------- | --------------------------------------- |
| PHP lint          | `composer lint`                         |
| PHP lint autofix  | `composer lint-fix`                     |
| JS/CSS lint       | `npm run lint:js`, `npm run lint:style` |
| Format            | `npm run format`                        |
| Lint staged files | `npm test`                              |
| Package a zip     | `npm run zip`                           |
| Cut a release     | `npm run release`                       |

PHP follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/), selected by `phpcs.xml`. There is no build step and no test suite.

`npm install` also installs a husky pre-commit hook that runs `lint-staged` over staged files, so `phpcs` and the formatter run before a commit lands. The same checks run in CI on every pull request via `.github/workflows/lint.yml`, which is the gate that actually blocks — the hook is fast feedback and can be skipped with `git commit --no-verify`.

Releases are tag-driven: pushing a `v*.*.*` tag runs `.github/workflows/release.yml`, which packages the plugin and attaches the zip to a GitHub release. The version lives in both `package.json` and the `Version:` header in `plugin.php`; `commit-and-tag-version` bumps both, so neither should be edited by hand.

See [CLAUDE.md](CLAUDE.md) for the fuller architecture notes.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
