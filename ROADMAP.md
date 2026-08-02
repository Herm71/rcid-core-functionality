# Roadmap

This file is now a **record of completed work**, not a to-do list.

It began as an audit run against the [WP plugin development](https://developer.wordpress.org/plugins/)
guardrails on 2026-08-01, verified against the code at `e17279c` (v1.2.2). Every one of its eight
sections has since been closed. Rather than keep eight stubs reading "done, see the Done section",
the sections have been collapsed into the record below.

**Open work lives in the [issue tracker](https://github.com/Herm71/rcid-core-functionality/issues).**
Anything still outstanding from the original audit — the CSP hardening that was section 7 — is
tracked there as #26, #27, #28 and #29.

## Done

Landed on the `claude-init` branch (see #7), behavior-neutral on the live site:

-   [x] `shortcodes.php` opened with `<?` instead of `<?php`. With the default `short_open_tag=Off`
        the file was not PHP at all and would have printed its own source into the page. `php -l`
        passed throughout, which hid it.
-   [x] `plugin.php` loaded `security-headers.php` twice — a bare `require_once` followed by the
        guarded `include_once`. Idempotent, but off-pattern.
-   [x] No `ABSPATH` guard existed in any of the 7 PHP files; direct requests executed file-scope code.
-   [x] `rcid_additional_securityheaders()` had no doc comment of its own — it was borrowing the
        file docblock.

Landed on the `updater` branch:

-   [x] The `Version:` header in `plugin.php` and the `version` in `package.json` had to be bumped
        together by hand. `standard-version` (unmaintained since 2022) has been replaced by its
        maintained fork `commit-and-tag-version`, and a `bumpFiles` entry now rewrites the plugin
        header through `wp-plugin-version-updater.js`, so the two cannot drift.

Landed on the `plugin-metadata` branch (see #12), closing section 5:

-   [x] The plugin header declared `License: GPL2` while `package.json` declared `ISC`. Settled on
        `GPL-2.0-or-later` in both, with a `License URI` header and the GPL-2.0 text in `LICENSE`.
        Note this relicenses from GPL-2.0-**only**: the header docblock previously said "you may NOT
        assume that you can use any other version of the GPL", and now carries the standard
        "either version 2 ... or (at your option) any later version" wording.
-   [x] `Text Domain`, `Requires at least` (6.0) and `Requires PHP` (8.0) headers were missing.

Landed on the `update-filter` branch (see #8), closing section 1:

-   [x] `rcid_custom_functionality_hidden()` in `general.php` was dead code with four compounding
        faults, including an `unserialize()` result written to as an object — a fatal under PHP 8
        that only fault 1 (an `http://` scheme test against an endpoint WordPress calls over https)
        kept from firing. Deleted rather than repaired: neither `rcid-core-functionality` nor
        `core-functionality` exists in the wordpress.org directory, so the slug collision it guarded
        against is hypothetical, and updates now come from GitHub releases via plugin-update-checker.

Landed on the `tooling` branch (see #11), closing section 4:

-   [x] `composer lint` did not run at all — with no `phpcs.xml` and no path argument it exited 3 with
        "You must supply at least one file or directory to process". A `phpcs.xml` now selects
        WordPress Coding Standards and carries a `<file>.</file>` element so the script has something
        to check. 486 findings, 475 auto-fixed by `phpcbf`; the remaining 11 were fixed by hand.
-   [x] `.editorconfig` declared tabs while the code used 4-space PEAR indentation. Adopting WPCS
        moved the code to tabs, so the two now agree without touching `.editorconfig`.
-   [x] `npm test` ran `lint-staged` with no configuration anywhere and could never fail. It now runs
        `phpcs` over staged PHP and `wp-scripts format` over staged JS/JSON/YAML/MD. Note `phpcbf`
        cannot be used as the gate: it exits 255 even when it succeeds in fixing something. No git
        hook is wired up, so it only runs when invoked.
-   [x] `wp-scripts build` targeted a nonexistent `src/`. Removed from `npm run zip` and from the
        release workflow, along with the `start`, `start:hot` and `format:src` scaffolding scripts.

Landed on the `uninstall` branch (see #10), closing section 3:

-   [x] There was no `uninstall.php` and no uninstall policy, so "content survives deletion" was
        incidental rather than decided. It is now explicit: `uninstall.php` documents that CPT
        content is never touched, and removes only plugin-update-checker's bookkeeping — the
        `external_updates-*` site option, the `puc_manual_check_errors-*` site transient and the
        `puc_cron_check_updates-*` cron event. PUC clears that cron on deactivation, but deleting a
        plugin does not always deactivate it first, and `uninstall.php` runs without the plugin
        loaded, so the names are hard-coded and tied to the slug in `buildUpdateChecker()`.

Landed on the `activation` branch (see #9), closing section 2:

-   [x] Nothing called `flush_rewrite_rules()`, so the `projects`, `press` and `testimonials`
        archives 404ed after a fresh activation until Permalinks were re-saved by hand.
        `register_activation_hook()` now registers the post types and _then_ flushes — that order
        matters, because a flush writes rules for whatever is registered at that instant and `init`
        has not run yet during activation. Deactivation unregisters first and then flushes, for the
        mirror-image reason: the plugin is still loaded at that point, so flushing alone would write
        the archive rules straight back.
-   [x] The three `add_action( 'init', ... )` calls were replaced by one aggregator,
        `rcid_register_post_types()`, shared by `init` and the activation hook, so the two paths
        cannot drift and leave a new CPT's archive unroutable.

Fixed upstream in [WordPress/agent-skills#88](https://github.com/WordPress/agent-skills/pull/88)
(see #15), closing section 8 — no change to this plugin:

-   [x] The `wp-plugin-development` skill's `scripts/detect_plugins.mjs` reported `count: 0` for this
        repo. Its header regex was `^\s*Plugin Name:`, which does not allow the leading `*` of a
        docblock, so ` * Plugin Name: ...` never matched; the looser `/Plugin Name:/i` guard passed
        first, so `plugin.php` was opened and then silently discarded. The fix allows the same leading
        comment characters WordPress core allows in `get_file_data()` (`^[ \t\/*#@]*`) and strips a
        trailing `*/` or `?>` the way core's `_cleanup_header_comment()` does. This repo is the
        verification case in that PR: `count` goes 0 → 1 with all eight header fields parsed.

Landed on the `shortcodes` branch (see #13), closing section 6:

-   [x] The question was whether to re-enable `shortcodes.php`. Answered by deleting it. Its
        `include_once` in `plugin.php` had been commented out for the plugin's whole history, so
        `show-current-year` was never a registered shortcode and any `[show-current-year]` in content
        has always rendered as literal text — removing the file changes nothing on the live site. The
        two defects the section listed (`date()` reading the server timezone, a `@return void`
        docblock on a function returning a string) are moot. References in `plugin.php`, `README.md`
        and `CLAUDE.md` went with it; the `CHANGELOG.md` entry for the old open-tag fix stays, as
        history.

## Carried forward

Section 7 was the one item never closed here — the CSP was restructured (#14) but not hardened. It
is now tracked as issues rather than as a roadmap section, because the work turned out to have a
prerequisite the audit missed:

-   **#26** — the report-only policy declares no `report-uri`/`report-to`, so violations are visible
    only in an individual visitor's console. There is nothing to collect, and so no evidence on which
    to base the flip. Blocks #28.
-   **#27** — the enforced `frame-src` omits `*.googletagmanager.com`, blocking the GTM `<noscript>`
    iframe today. A loosening, so it carries none of the risk that produced v1.1.2 and v1.1.3.
-   **#28** — flip the hardened policy from report-only to enforced, and delete the permissive one.
    Until this lands the site has no meaningful CSP protection: `default-src` carries `http://*`,
    which CSP matches against https origins too, so it permits everything.
-   **#29** — remove `'unsafe-inline'` / `'unsafe-eval'` from `script-src` by getting a nonce to the
    inline Google Tag Manager snippet, which makes it a change to `gtm.php` as much as to the CSP.
