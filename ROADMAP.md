# Roadmap

Outstanding work on `rcid-core-functionality`, from an audit run against the
[WP plugin development](https://developer.wordpress.org/plugins/) guardrails on 2026-08-01.

Every item below was verified against the code at `e17279c` (v1.2.2) — none are speculative.
Items are ordered by blast radius, not by effort.

## Done

Landed on the `claude-init` branch (see #7), behavior-neutral on the live site:

- [x] `shortcodes.php` opened with `<?` instead of `<?php`. With the default `short_open_tag=Off`
      the file was not PHP at all and would have printed its own source into the page. `php -l`
      passed throughout, which hid it.
- [x] `plugin.php` loaded `security-headers.php` twice — a bare `require_once` followed by the
      guarded `include_once`. Idempotent, but off-pattern.
- [x] No `ABSPATH` guard existed in any of the 7 PHP files; direct requests executed file-scope code.
- [x] `rcid_additional_securityheaders()` had no doc comment of its own — it was borrowing the
      file docblock.

Landed on the `updater` branch:

- [x] The `Version:` header in `plugin.php` and the `version` in `package.json` had to be bumped
      together by hand. `standard-version` (unmaintained since 2022) has been replaced by its
      maintained fork `commit-and-tag-version`, and a `bumpFiles` entry now rewrites the plugin
      header through `wp-plugin-version-updater.js`, so the two cannot drift.

## 1. Defuse the dead update filter in `lib/functions/general.php`

`rcid_custom_functionality_hidden()` has four compounding faults and currently does nothing:

1. The guard tests `strpos($url, 'http://api.wordpress.org/...')`, but WordPress requests that
   endpoint over **https** whenever SSL is supported. It bails on every request.
2. `plugin_basename(__FILE__)` resolves to `rcid-core-functionality/lib/functions/general.php`,
   while the plugin list is keyed by `rcid-core-functionality/plugin.php`. It could never have
   matched — the file was moved into `lib/` without the constant being updated.
3. Because the match fails, `array_search()` returns `false` and `unset($plugins->active[false])`
   deletes **index 0** — an unrelated active plugin, which would then stop receiving update checks.
4. `unserialize()` expects a payload WordPress no longer sends (the 1.1 endpoint is JSON), so it
   returns `false`, and the next line writes a property on a bool — fatal under PHP 8.

It is inert today *only* because fault 1 short-circuits before the rest. Anyone modernizing that
URL check trips the other three at once.

**Decision required.** Either:

- **Delete it.** The plugin updates from its own GitHub releases via plugin-update-checker, not
  from WordPress.org, so the original rationale (a public plugin colliding on slug) no longer
  applies. This is the low-risk option.
- **Repair it.** Fix the scheme check, use `plugin_basename(BB_DIR . '/plugin.php')`, guard the
  `array_search()` result before unsetting, and decode/encode JSON rather than serialize.

## 2. Add activation lifecycle and flush rewrite rules

`projects`, `press`, and `testimonials` all register with `has_archive => true`, but the repo
contains no `register_activation_hook`, `register_deactivation_hook`, or `flush_rewrite_rules`
anywhere. Their archives 404 after a fresh activation until someone re-saves Permalinks.

Per the lifecycle guardrails, register the hook at top level in `plugin.php`, call the same CPT
registration function used on `init`, *then* flush — and flush again on deactivation.

## 3. Decide and document an uninstall policy

There is no `uninstall.php` and no `register_uninstall_hook()`. Leaving CPT content in place on
uninstall is almost certainly correct for this site — the point of the plugin is that content
outlives the theme — but the decision should be explicit rather than incidental.

## 4. Fix the tooling that only looks like it works

- **`composer lint` runs the wrong standard.** `wp-coding-standards/wpcs` is a declared dev
  dependency, but there is no `phpcs.xml`, so PHPCS falls back to PEAR. That is why the codebase
  uses 4-space indents, next-line braces, and `if (! is_admin() ) {` spacing, and why every
  existing docblock reports tag-ordering errors. Adding a `phpcs.xml` that selects WPCS would
  reclassify most of the current ~90 findings; expect a large but mechanical `phpcbf` pass.
- **`.editorconfig` declares tabs**, contradicting the actual PHP formatting. Reconcile it with
  whichever standard is chosen above.
- **`npm test` runs `lint-staged` with no configuration** in `package.json` or any `.lintstagedrc`.
  It cannot fail and must not be read as a passing check. Either configure it or drop the script.
- **`npm run build` targets a `src/` directory that does not exist** (leftover block-theme
  scaffolding). Only the `plugin-zip` half of `npm run zip` is meaningful. The release workflow
  runs `npm run build` on every tag, so this is dead weight in CI too.

## 5. Correct plugin metadata

- The header declares `License: GPL2` while `package.json` declares `ISC`. Pick one.
- Missing `Text Domain`, `Requires at least`, and `Requires PHP` headers. `Requires PHP` matters
  here given the PHP 8 hazard described in item 1.

## 6. Shortcodes: decide whether to re-enable

Now that the open tag is fixed, `shortcodes.php` would actually work if its `include_once` in
`plugin.php` were uncommented. Before doing so:

- `rcid_show_current_year()` uses `date('Y')`, which reads the **server** timezone, not the site's.
  Use `wp_date('Y')` so the year rolls over correctly relative to the site's configured timezone.
- Its docblock claims `@return void`, but the function returns a string — and a shortcode callback
  must return, never echo.

## 7. Ongoing: CSP maintenance

The `Content-Security-Policy` string in `security-headers.php` is a single long inline literal and
has been the subject of several hotfix releases (v1.1.2, v1.1.3). Any newly added external script,
font, or embed must be allowlisted there or it fails silently in production. Consider splitting the
directives into an array keyed by directive name so additions are reviewable in a diff.

Note that it still carries `http://*` in `default-src`, which substantially weakens the policy, and
`X-XSS-Protection`, which is deprecated and ignored by current browsers.

## 8. Upstream: report the plugin detector bug

The `wp-plugin-development` skill's `scripts/detect_plugins.mjs` reports `count: 0` for this repo.
Its header regex is `^\s*Plugin Name:`, which does not allow the leading `*` of a docblock;
WordPress core uses `^[ \t\/*#@]*`. It will miss essentially every conventionally-formatted plugin.
