# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress "core functionality" plugin for [Ruth Chafin Interior Design](https://ruthchafininteriordesign.com). It deliberately holds only *theme-independent* behavior — custom post types, security headers, analytics, shortcodes — so that swapping or rewriting the companion block theme ([rcid-block-theme](https://github.com/Herm71/rcid-block-theme)) never breaks site functionality. When deciding where a change belongs: if it would survive a theme change, it goes here; if it's presentation, it goes in the theme.

There is no build step for the PHP that ships — this is plain procedural PHP with `add_action`/`add_filter` calls at file scope. No classes, no autoloader, no `src/` directory.

The site is hosted on GreenGeeks. It was previously on Pantheon, and a few Pantheon-era artifacts survive in the code (documentation links, one CSP allowlist entry) — those are noted where they appear below.

## Commands

Dependencies are not committed (`node_modules` and `vendor` are gitignored), so install first:

```bash
npm install
composer install
```

| Task | Command |
|---|---|
| PHP lint | `composer lint` (phpcs, reads [phpcs.xml](phpcs.xml)) |
| PHP lint autofix | `composer lint-fix` (phpcbf) |
| JS/CSS lint | `npm run lint:js`, `npm run lint:style` |
| Format | `npm run format` |
| Lint staged files | `npm test` (lint-staged) |
| Package a zip | `npm run zip` |
| Cut a release | `npm run release` (commit-and-tag-version) |

**PHP style is WordPress Coding Standards**, selected by [phpcs.xml](phpcs.xml) — tabs, same-line braces, `if ( ! is_admin() ) {` spacing, Yoda conditions. Match the surrounding files; `composer lint-fix` will do most of it for you. The ruleset carries a `<file>.</file>` element because `composer lint` passes no path of its own and would otherwise exit 3 without checking anything.

Caveats worth knowing before you run these:

- **`npm test` is a real gate now**, but only when invoked. It runs `phpcs` over staged `.php` and `wp-scripts format` over staged JS/JSON/YAML/MD. There is no git hook wired up, so it does *not* fire automatically on commit — add husky if you want that. There is still no test suite, so "tests pass" remains the wrong phrase for a green run.
- **`.editorconfig` declares tabs**, which now agrees with the PHP formatting rather than contradicting it.
- **`wp-scripts build` is gone.** It targeted a `src/` entry point that has never existed here (inherited from a block-theme scaffold); `npm run zip` is now just `wp-scripts plugin-zip`, and the release workflow no longer runs a build step. `start`, `start:hot` and `format:src` were removed for the same reason.

## Release process

Releases are tag-driven: `.github/workflows/release.yml` fires on a pushed `v*.*.*` tag, runs the npm build, packages with `wp-scripts plugin-zip`, and attaches `rcid-core-functionality.zip` to a GitHub release.

**The version lives in two places, and both are now automated.** `commit-and-tag-version` bumps `package.json`, `package-lock.json`, and — via the `bumpFiles` entry in `package.json` — the `Version:` header in [plugin.php](plugin.php), which is what WordPress reads for its update checks. That header is rewritten by [wp-plugin-version-updater.js](wp-plugin-version-updater.js), a small `readVersion`/`writeVersion` module; the built-in `plain-text` updater can't be used because it expects a file containing nothing but a version string. Do *not* bump `plugin.php` by hand anymore (releases through v1.2.2 did, which is why older commits look that way) — a hand-edit that lands in the same commit range just makes the two disagree.

Commits use Conventional Commits with gitmoji in the subject (e.g. `fix: :art: Adjust order of google <script> tags`); commit-and-tag-version parses the `feat:`/`fix:` prefix to pick the bump. Sanity-check a release with `npx commit-and-tag-version --dry-run` before running it for real.

## Architecture

[plugin.php](plugin.php) is a loader and nothing else: it defines `BB_DIR` and conditionally `include_once`s each file in [lib/functions/](lib/functions/). Adding a feature means creating a file there and adding an include block. Files are enabled/disabled by commenting out their include — that is the intended mechanism, and it's why [lib/functions/shortcodes.php](lib/functions/shortcodes.php) is present but currently not loaded.

Function names are prefixed `rcid_` to avoid collisions in the global namespace.

Per-file responsibilities:

- **[post-types.php](lib/functions/post-types.php)** — registers `projects`, `press`, and `testimonials` CPTs on `init`, all with `show_in_rest => true` (required for the block editor, and for the block theme to query them). A fourth, `team_member`, is defined but its `add_action('init', ...)` is commented out; leave that intact unless asked to enable it.
- **[security-headers.php](lib/functions/security-headers.php)** — a single `wp_headers` filter setting CSP, `X-Frame-Options`, `Permissions-Policy`, etc., skipped when `is_admin()`. The most fragile thing in this repo: several past releases were CSP hotfixes after a third-party script got blocked. Any new external script, font, or iframe source must be added to the matching directive or it will silently fail on the live site.

  Two policies are emitted. `rcid_csp_enforced_directives()` is what browsers act on, and it is **effectively wide open** — `default-src` carries `http://*`, and CSP matches an `http:` scheme-part against `https:` URLs, so that single token permits every origin; `'unsafe-inline'` and `'unsafe-eval'` finish the job. `rcid_csp_report_only_directives()` is the candidate replacement, sent as `Content-Security-Policy-Report-Only` so violations surface in the browser console without blocking anything.

  **The intended workflow is to watch the report-only policy against real traffic, then swap the two** (#14). Don't tighten the enforced policy directly — that is exactly what produced v1.1.2 and v1.1.3. The report-only policy deliberately omits `*.pantheonsite.io` (old host), `*.ucsc.edu`, `*.netlify.com` and `unpkg` so that any still in use announce themselves rather than being carried forward on faith.
- **[gtm.php](lib/functions/gtm.php)** — Google Tag Manager (container `GTM-5K5NV59S`) injected via `wp_head` and `wp_body_open`, both at priority `-1` so the snippet lands as high in the document as possible. That priority is intentional (v1.2.2); don't "clean it up" to the default. GA is intentionally *not* here — it was removed in v1.2.1 in favor of GTM.
- **[disable-xmlrpc.php](lib/functions/disable-xmlrpc.php)** — empties `xmlrpc_methods` at `PHP_INT_MAX` and removes `rsd_link` to blunt brute-force attacks against admin credentials. The advice is host-independent; the `pantheon.io` links cited here and in [README.md](README.md) are historical, from when the site was on Pantheon.
- **[general.php](lib/functions/general.php)** — currently empty apart from the file docblock and `ABSPATH` guard. It held the Mark Jaquith `http_request_args` trick that hid this plugin from wordpress.org update checks; that was removed in #8 (it was inert and carried a latent PHP 8 fatal, the slug is not in the .org directory, and updates now come from GitHub releases). Kept as the catch-all for theme-independent odds and ends.
- **[uninstall.php](uninstall.php)** — runs on plugin *deletion*, not deactivation. **The uninstall policy is that content outlives the plugin**: `projects`, `press` and `testimonials` posts, terms and meta are never touched, because surviving a theme rewrite is the entire point of those post types. Deleting the plugin only stops them being registered — the content is hidden, not destroyed. Anything added here that deletes a post needs a deliberate decision and an opt-in, and the `WP_UNINSTALL_PLUGIN` guard at the top must stay. What it *does* remove is plugin-update-checker's bookkeeping: the `external_updates-…` site option, the `puc_manual_check_errors-…` site transient, and the `puc_cron_check_updates-…` cron event. Those names derive from the slug passed to `buildUpdateChecker()`; change the slug and they must change too.

The plugin updates itself from its own GitHub releases, not from the WordPress.org repository. [plugin.php](plugin.php) bootstraps [Yahnis Elsts' plugin-update-checker](https://github.com/YahnisElsts/plugin-update-checker) (a Composer runtime dependency, so `vendor/` must be in the packaged zip — see the release workflow's `composer install --no-dev` step). Things to keep in mind when touching that block:

- It is registered only for `is_admin()`, `wp_doing_cron()`, and WP-CLI. `is_admin()` is false under WP-CLI, so without that third condition `wp plugin update` reports no update available.
- It is deliberately *not* gated on `wp_is_auto_update_enabled_for_type('plugin')`. That function only reports whether unattended background auto-updates are on; the update notice and the manual Update Now button both need the check to run regardless.
- Release assets are set to `REQUIRE_RELEASE_ASSETS`. The default preference falls back to GitHub's source tarball, which has no `vendor/` — installing it would strip the updater out of the running plugin.

An earlier `GitHub Plugin URI` header (for Andy Fragen's GitHub Updater) was removed when this landed; don't reintroduce it, or two updaters will write the same `update_plugins` transient.
