# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress "core functionality" plugin for [Ruth Chafin Interior Design](https://ruthchafininteriordesign.com). It deliberately holds only *theme-independent* behavior — custom post types, security headers, analytics, shortcodes — so that swapping or rewriting the companion block theme ([rcid-block-theme](https://github.com/Herm71/rcid-block-theme)) never breaks site functionality. When deciding where a change belongs: if it would survive a theme change, it goes here; if it's presentation, it goes in the theme.

There is no build step for the PHP that ships — this is plain procedural PHP with `add_action`/`add_filter` calls at file scope. No classes, no autoloader, no `src/` directory.

## Commands

Dependencies are not committed (`node_modules` and `vendor` are gitignored), so install first:

```bash
npm install
composer install
```

| Task | Command |
|---|---|
| PHP lint | `composer lint` (phpcs) |
| PHP lint autofix | `composer lint-fix` (phpcbf, all `.php` recursively) |
| JS/CSS lint | `npm run lint:js`, `npm run lint:style` |
| Format | `npm run format` |
| Package a zip | `npm run zip` |
| Cut a release | `npm run release` (standard-version) |

Caveats worth knowing before you run these:

- **No `phpcs.xml` ruleset exists.** `composer lint` therefore falls back to PHPCS's default (PEAR) standard, even though `wp-coding-standards/wpcs` is the declared dev dependency. This is why existing PHP uses 4-space indent, `function foo()` with the brace on the next line, and `if (! is_admin() ) {` spacing — PEAR style, not WordPress style. Match the surrounding files rather than WPCS conventions unless you're also adding a ruleset.
- **`.editorconfig` says tabs**, which contradicts the actual PHP formatting above. Tabs do apply to JSON/JS/config files.
- **`npm test` runs `lint-staged`, which has no configuration** in `package.json` or a `.lintstagedrc`. There is no test suite. Don't report "tests pass" from this command.
- **`npm run build` / `npm run zip` invoke `wp-scripts build`** against a nonexistent `src/` entry point. Only the `plugin-zip` half is meaningful today; the build is vestigial (inherited from a block-theme scaffold).

## Release process

Releases are tag-driven: `.github/workflows/release.yml` fires on a pushed `v*.*.*` tag, runs the npm build, packages with `wp-scripts plugin-zip`, and attaches `rcid-core-functionality.zip` to a GitHub release.

**The version lives in two places and only one is automated.** `standard-version` bumps `package.json` and writes `CHANGELOG.md`, but it does *not* touch the `Version:` header in [plugin.php](plugin.php) — WordPress reads that header for its update checks. Bump `plugin.php` by hand in the same feature/fix commit *before* running `npm run release`, or the shipped plugin will report a stale version. Every past release in this repo follows that pattern.

Commits use Conventional Commits with gitmoji in the subject (e.g. `fix: :art: Adjust order of google <script> tags`); standard-version parses the `feat:`/`fix:` prefix to pick the bump.

## Architecture

[plugin.php](plugin.php) is a loader and nothing else: it defines `BB_DIR` and conditionally `include_once`s each file in [lib/functions/](lib/functions/). Adding a feature means creating a file there and adding an include block. Files are enabled/disabled by commenting out their include — that is the intended mechanism, and it's why [lib/functions/shortcodes.php](lib/functions/shortcodes.php) is present but currently not loaded.

Function names are prefixed `rcid_` to avoid collisions in the global namespace.

Per-file responsibilities:

- **[post-types.php](lib/functions/post-types.php)** — registers `projects`, `press`, and `testimonials` CPTs on `init`, all with `show_in_rest => true` (required for the block editor, and for the block theme to query them). A fourth, `team_member`, is defined but its `add_action('init', ...)` is commented out; leave that intact unless asked to enable it.
- **[security-headers.php](lib/functions/security-headers.php)** — a single `wp_headers` filter setting CSP, `X-Frame-Options`, `Permissions-Policy`, etc., skipped when `is_admin()`. The CSP allowlist is a long inline string and is the most fragile thing in this repo: several past releases were CSP hotfixes after a third-party script got blocked. Any new external script, font, or iframe source must be added to the matching directive here or it will silently fail on the live site.
- **[gtm.php](lib/functions/gtm.php)** — Google Tag Manager (container `GTM-5K5NV59S`) injected via `wp_head` and `wp_body_open`, both at priority `-1` so the snippet lands as high in the document as possible. That priority is intentional (v1.2.2); don't "clean it up" to the default. GA is intentionally *not* here — it was removed in v1.2.1 in favor of GTM.
- **[disable-xmlrpc.php](lib/functions/disable-xmlrpc.php)** — empties `xmlrpc_methods` at `PHP_INT_MAX` and removes `rsd_link`, per Pantheon's brute-force guidance. The site is Pantheon-hosted, which is also why Pantheon best-practice docs are cited throughout.
- **[general.php](lib/functions/general.php)** — currently just the Mark Jaquith `http_request_args` trick that hides this plugin from wordpress.org update checks (a public plugin sharing the slug would otherwise clobber it). Catch-all for theme-independent odds and ends.

The plugin is installed/updated via `GitHub Plugin URI` in the plugin header (git-based updater), not from the WordPress.org repository.
