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

Landed on the `plugin-metadata` branch (see #12), closing section 5:

- [x] The plugin header declared `License: GPL2` while `package.json` declared `ISC`. Settled on
      `GPL-2.0-or-later` in both, with a `License URI` header and the GPL-2.0 text in `LICENSE`.
      Note this relicenses from GPL-2.0-**only**: the header docblock previously said "you may NOT
      assume that you can use any other version of the GPL", and now carries the standard
      "either version 2 ... or (at your option) any later version" wording.
- [x] `Text Domain`, `Requires at least` (6.0) and `Requires PHP` (8.0) headers were missing.

Landed on the `update-filter` branch (see #8), closing section 1:

- [x] `rcid_custom_functionality_hidden()` in `general.php` was dead code with four compounding
      faults, including an `unserialize()` result written to as an object — a fatal under PHP 8
      that only fault 1 (an `http://` scheme test against an endpoint WordPress calls over https)
      kept from firing. Deleted rather than repaired: neither `rcid-core-functionality` nor
      `core-functionality` exists in the wordpress.org directory, so the slug collision it guarded
      against is hypothetical, and updates now come from GitHub releases via plugin-update-checker.

Landed on the `tooling` branch (see #11), closing section 4:

- [x] `composer lint` did not run at all — with no `phpcs.xml` and no path argument it exited 3 with
      "You must supply at least one file or directory to process". A `phpcs.xml` now selects
      WordPress Coding Standards and carries a `<file>.</file>` element so the script has something
      to check. 486 findings, 475 auto-fixed by `phpcbf`; the remaining 11 were fixed by hand.
- [x] `.editorconfig` declared tabs while the code used 4-space PEAR indentation. Adopting WPCS
      moved the code to tabs, so the two now agree without touching `.editorconfig`.
- [x] `npm test` ran `lint-staged` with no configuration anywhere and could never fail. It now runs
      `phpcs` over staged PHP and `wp-scripts format` over staged JS/JSON/YAML/MD. Note `phpcbf`
      cannot be used as the gate: it exits 255 even when it succeeds in fixing something. No git
      hook is wired up, so it only runs when invoked.
- [x] `wp-scripts build` targeted a nonexistent `src/`. Removed from `npm run zip` and from the
      release workflow, along with the `start`, `start:hot` and `format:src` scaffolding scripts.

## 1. Defuse the dead update filter — done, see the Done section

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

## 4. Fix the tooling that only looks like it works — done, see the Done section

## 5. Correct plugin metadata — done, see the Done section

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
